# Analisis Komprehensif TryOutKu v1.0.0

Tanggal: 2026-06-01
Skop: Seluruh codebase PHP Native + Bootstrap + jQuery

---

## 1. Arsitektur Aplikasi

### Stack Teknologi
- **Backend**: PHP 8.2 (Native, no framework)
- **Database**: MySQL/MariaDB (InnoDB, utf8mb4)
- **Frontend**: Bootstrap 5.3.2, jQuery 3.7.1, Bootstrap Icons 1.11.1
- **Charts**: Chart.js (radar & line graphs)
- **Testing**: Playwright E2E
- **PWA**: manifest.json + service worker (cache static assets)

### Struktur Modular
```
index.php (landing)
  +-- login.php / register.php
       +-- admin/*  (role = admin)
       +-- peserta/* (role = peserta)
            +-- dashboard (stats, recommendations, history)
            +-- tryout_list -> tryout_kerja -> tryout_hasil
            +-- mini_tryout -> mini_tryout_kerja -> mini_tryout_hasil
            +-- latihan_topik -> latihan_kerja
            +-- belajar -> belajar_detail
            +-- flashcard -> flashcard_detail
            +-- rapor (charts & analysis)
            +-- leaderboard
            +-- forum
            +-- psikologi -> psikologi_kerja/kraepelin -> psikologi_hasil
            +-- profil
```

---

## 2. Alur Bisnis (End-to-End)

### 2.1 Autentikasi
1. Register (nama, email, password, no_hp, target_ujian)
   - Cek kuota: MAX_PESERTA (10)
   - Hash password dengan `password_hash()`
2. Login → set session (user_id, nama, email, role)
3. Role-based redirect: admin → admin/dashboard, peserta → peserta/dashboard
4. No session_regenerate_id() after login (security gap)

### 2.2 Try-Out Ujian (Core Feature)
1. **Inisiasi** (`tryout_kerja.php`):
   - Cek sesi aktif (hasil_ujian.status = 'proses')
   - Kalau belum ada → INSERT hasil_ujian + INSERT detail_jawaban per soal
   - Soal diacak (ORDER BY RAND()) saat pembuatan sesi
2. **Pengerjaan**:
   - 1 soal per halaman (refresh-based navigation)
   - Klik opsi → AJAX simpan ke `api/simpan_jawaban_temp.php`
   - Auto-navigate ke soal berikutnya (400ms delay)
   - Timer client-side dengan localStorage backup per paket
   - Navigasi nomor soal dengan warna: abu/belum, hijau/dijawab, kuning/ragu
3. **Submit**:
   - `api/submit_ujian.php` menghitung skor TWK/TIU/TKP
   - Update hasil_ujian (status: lulus/gugur)
   - Generate rekomendasi_belajar untuk topik lemah (< 50%)
4. **Hasil**:
   - `tryout_hasil.php` tampilkan skor per subtest, status, pembahasan

### 2.3 Mini Try-Out
- Session-based (tidak pakai DB hasil_ujian)
- Pilih jenis tes, jumlah soal, topik, level → kerjakan → hasil
- Simpan jawaban di `$_SESSION['mini_jawaban']`

### 2.4 Latihan Topik
- Mirip mini try-out tapi bisa lihat pembahasan langsung
- `reveal` flag untuk membuka pembahasan + kunci jawaban
- Session-based: `$_SESSION['latihan_jawaban']`

### 2.5 Psikologi
- 3 jenis: Kraepelin (Pauli), Wartegg, EPPS
- Kraepelin: grid angka, client-side scoring
- Wartegg/EPPS: soal dari DB (jenis_tes = 'psikologi'), hasil simpan ke hasil_ujian paket_id=0
- Hasil ada halaman khusus `psikologi_hasil.php`

### 2.6 Forum Tanya
- User input pertanyaan → search keyword di materi, soal, flashcard
- Kalau tidak ketemu → simpan ke `tanya_admin` untuk dijawab manual
- Admin jawab via `admin/jawab_forum.php`

---

## 3. Temuan Bug & Issues

### 🔴 Critical (Security)

| # | File | Line | Issue | Risk | Status |
|---|------|------|-------|------|--------|
| 1 | `api/get_topik_by_jenis.php` | 13 | `$jenis` langsung di-query tanpa prepared statement | SQL Injection | **FIXED** |
| 2 | `peserta/latihan_kerja.php` | 14 | `$jenis` + `$topik` dalam string query | SQL Injection | **FIXED** |
| 3 | `peserta/mini_tryout_kerja.php` | 16 | `$jenis` dalam WHERE clause string | SQL Injection | **FIXED** |
| 4 | `peserta/profil.php` | 45 | Update password pakai raw query | SQL Injection (meski hash) | **FIXED** |
| 5 | `admin/kelola_soal.php` | ~95 | WHERE clause built dari user input | SQL Injection | **TODO** |
| 6 | Semua | - | Tidak ada rate limiting | Brute force login | **FIXED** (session-based, 5 attempts, 15min lockout) |
| 7 | `login.php` | - | Tidak ada session_regenerate_id() | Session fixation | **FIXED** |
| 8 | `api/simpan_jawaban_temp.php` | - | Tidak ada CSRF token | CSRF attack potential | **TODO** |

### 🟠 High (Functional)

| # | File | Issue | Impact | Status |
|---|------|-------|--------|--------|
| 1 | `peserta/mini_tryout_kerja.php` | `location.reload()` setelah setiap jawaban | UX buruk, flashing screen | **TODO** (needs SPA refactor) |
| 2 | `peserta/latihan_kerja.php` | `location.reload()` setelah setiap jawaban | UX buruk | **TODO** (needs SPA refactor) |
| 3 | `peserta/tryout_list.php` | "Kerjakan Lagi" tetap ke `tryout_kerja.php` meski sudah selesai | Bisa mengerjakan ulang yang sudah selesai | **FIXED** (redirect ke hasil) |
| 4 | `peserta/tryout_kerja.php` | Saat refresh, timer bisa reset jika localStorage key tidak ketemu | Waktu exam tidak akurat | **FIXED** (per-paket localStorage key) |
| 5 | `api/submit_psikologi.php` | `paket_ujian_id = 0` untuk psikologi | Collision risk jika ada paket id=0 | **TODO** (use nullable or separate table) |
| 6 | `peserta/leaderboard.php` | SQL ORDER BY aggregate | MySQL strict mode error | **FIXED** |

### 🟡 Medium (UX/Mobile)

| # | File | Issue | Status |
|---|------|-------|--------|
| 1 | `peserta/latihan_kerja.php` | Tidak ada bottom-nav-mobile | **FIXED** |
| 2 | `peserta/psikologi_kraepelin.php` | Tidak ada bottom-nav-mobile | **FIXED** |
| 3 | `peserta/psikologi_kerja.php` | Tidak ada bottom-nav-mobile | N/A (exam page, no nav needed) |
| 4 | `admin/*` | Tidak ada responsive bottom nav | **TODO** (low priority) |
| 5 | `peserta/rapor.php` | Chart.js external CDN — offline tidak bekerja | **TODO** (self-host or CDN fallback) |
| 6 | `peserta/tryout_hasil.php` | Skor cards layout inconsistency | **FIXED** (col-12 col-sm-4) |
| 7 | Semua | Tidak ada halaman 404 | **FIXED** (404.php created) |

### 🟢 Low (Code Quality)

| # | File | Issue | Status |
|---|------|-------|--------|
| 1 | `includes/functions.php` | `escapeLike()` didefinisikan tapi tidak pernah dipakai | **TODO** (remove or use) |
| 2 | `includes/functions.php` | `generateCSRF()` / `verifyCSRF()` ada tapi tidak dipakai di form | **TODO** (implement in forms) |
| 3 | `peserta/dashboard.php` | `$rekom->data_seek(0)` setelah `$rekom->num_rows` | **TODO** (verify cursor position) |
| 4 | `admin/analisis_butir.php` | Daya pembeda hanya dihitung kalau `total >= 10` | **BY DESIGN** |
| 5 | `peserta/forum.php` | Search pakai `LIKE '%keyword%'` bisa lambat tanpa FULLTEXT index | **TODO** (add FULLTEXT or optimize) |

---

## 4. Performance Analysis

### Query yang Berpotensi Lambat
1. `rapor.php` radar chart: `AVG(CASE WHEN...)` dengan JOIN 3 tabel
2. `leaderboard.php`: `LEFT JOIN` + `GROUP BY` + `ORDER BY` AVG
3. `forum.php`: Multiple `LIKE '%keyword%'` queries tanpa index
4. `analisis_butir.php`: Subquery untuk daya pembeda per soal

### Missing Indexes
- `hasil_ujian(user_id, paket_ujian_id, status_lulus)` — paling sering di-query
- `soal(jenis_tes, topik)` — untuk filtering latihan
- `materi(tipe, jenis_tes)` — untuk flashcard/belajar
- `detail_jawaban(hasil_ujian_id, soal_id)` — untuk submit scoring

---

## 5. Mobile/PWA Assessment

### ✅ Implemented
- Viewport meta tag (no zoom)
- Manifest.json (standalone mode)
- Service worker (basic cache)
- Bottom nav on 15+ peserta pages
- Offcanvas navbar (Bootstrap 5)
- Touch-friendly inputs (min 44px, 16px font)
- Swipe gesture for exam navigation
- Responsive grid (col-6 / col-md-4, etc.)

### ⚠️ Needs Improvement
- `psikologi_kraepelin.php`: Grid 20x20 angka — horizontal scroll di HP
- `admin/*`: Tables overflow di mobile (scroll horizontal)
- Chart.js CDN: offline tidak tampil
- `tryout_kerja.php`: Timer display bisa terlalu kecil di iPhone SE
- `latihan_kerja.php`: No bottom nav

---

## 6. Test Coverage

### E2E Tests (Playwright)
| Module | Status | Coverage |
|--------|--------|----------|
| Auth (login/register) | ✅ | Full |
| Landing page | ✅ | Full |
| Admin CRUD pages load | ✅ | Smoke test |
| Peserta pages load | ✅ | Smoke test |
| Console/Network check | ✅ | All pages |
| Exam flow (try-out) | ⚠️ | Only page load, no answer/submit flow |
| Mobile responsive | ❌ | Not tested |
| PWA features | ❌ | Not tested |
| API endpoints | ❌ | Not tested directly |

---

## 7. Rekomendasi Prioritas

### Sprint 1 (Fix Critical)
1. Fix SQL Injection di `get_topik_by_jenis.php`, `latihan_kerja.php`, `mini_tryout_kerja.php`
2. Tambah `session_regenerate_id()` setelah login
3. Tambah rate limiting di login (max 5 attempts per IP)

### Sprint 2 (Fix Functional)
4. Ganti `location.reload()` di mini_tryout_kerja & latihan_kerja dengan DOM update
5. Fix "Kerjakan Lagi" behavior — arahkan ke hasil kalau sudah selesai
6. Simpan `detail_jawaban.waktu_detik` (hitung waktu per soal via JS timestamp)

### Sprint 3 (Enhancement)
7. Tambah bottom nav di semua exam pages
8. Tambah 404 error page
9. Buat audit log table untuk activity tracking
10. Index database untuk performance

### Sprint 4 (Feature)
11. Bulk import soal dari Excel/CSV
12. Email notification (PHPMailer)
13. Export PDF hasil ujian
14. Real-time leaderboard update (polling/AJAX)

---

## 8. File Reference Map

| Area | Key Files |
|------|-----------|
| Config | `config.php`, `includes/db.php` |
| Auth | `login.php`, `register.php`, `includes/functions.php` |
| Exam Engine | `peserta/tryout_kerja.php`, `api/simpan_jawaban_temp.php`, `api/submit_ujian.php` |
| Results | `peserta/tryout_hasil.php`, `peserta/mini_tryout_hasil.php` |
| Learning | `peserta/belajar.php`, `peserta/belajar_detail.php`, `peserta/flashcard.php` |
| Analysis | `peserta/rapor.php` (Chart.js), `admin/analisis_butir.php` |
| Admin CRUD | `admin/kelola_soal.php`, `admin/kelola_soal_form.php`, `admin/kelola_materi.php`, `admin/kelola_paket.php` |
| Reports | `admin/laporan.php`, `admin/detail_peserta.php`, `admin/export.php` |
| Forum | `peserta/forum.php`, `admin/jawab_forum.php` |
| Mobile/PWA | `assets/css/custom.css`, `assets/js/app.js`, `manifest.json`, `sw.js` |
| Tests | `tests/e2e_comprehensive.spec.js` |
