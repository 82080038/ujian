# ACUAN PEMBANGUNAN APLIKASI TRY-OUT & BIMBEL ONLINE

## FASE 1: FOUNDATION & SETUP (Minggu 1)
### 1.1 Struktur Folder Project
```
/ujian
  /assets
    /css (Bootstrap 5 + custom)
    /js (jQuery 3.x + custom)
    /images
    /uploads (soal gambar, materi)
  /includes
    db.php (koneksi MySQLi)
    functions.php (helper umum)
    auth_check.php (validasi sesi)
  /admin
    dashboard.php
    kelola_soal.php
    kelola_materi.php
    kelola_paket.php
    laporan.php
  /peserta
    dashboard.php
    tryout_list.php
    tryout_kerja.php
    tryout_hasil.php
    belajar.php
    rapor.php
  /api
    submit_jawaban.php (AJAX endpoint)
    get_soal.php (AJAX endpoint)
    get_rekomendasi.php
  index.php (landing/login)
  login.php
  register.php
  logout.php
```

### 1.2 Database Setup
- Jalankan XAMPP (Apache + MySQL).
- Buat database `db_tryout`.
- Import struktur tabel sesuai rancangan di PROMPT_APLIKASI_TRYOUT.md bagian 5.
- Buat user admin pertama via PHPMyAdmin atau seed script.

### 1.3 Core Files
- `db.php`: Koneksi MySQLi dengan OOP atau prosedural, enable error reporting saat dev.
- `functions.php`: `redirect()`, `flashMessage()`, `formatWaktu()`, `hitungSkor()`, `generateCSRF()`.
- `auth_check.php`: Cek `$_SESSION['user_id']` & role, redirect ke login jika tidak valid.

---

## FASE 2: AUTH & MANAJEMEN PENGGUNA (Minggu 1-2)
### 2.1 Fitur
- Registrasi peserta (nama, email, password, no_hp, target_ujian).
- Login multi-role (Admin / Peserta).
- Logout & session destroy.
- Profil peserta (ganti password, lihat target ujian).

### 2.2 Teknis
- Gunakan `password_hash($password, PASSWORD_DEFAULT)` dan `password_verify()`.
- Simpan `role` di session untuk routing ke dashboard yang sesuai.
- Gunakan Bootstrap modal untuk form login (mobile-friendly).

---

## FASE 3: BANK SOAL & MATERI (ADMIN) (Minggu 2)
### 3.1 CRUD Soal
- Form input soal: textarea CKEditor/TinyMCE sederhana (atau textarea biasa) untuk pertanyaan & pembahasan.
- Upload gambar soal (opsional) ke folder `/uploads/soal/`.
- Input opsi A-E, centang kunci jawaban, atur bobot nilai (khusus TKP).
- Dropdown: Kategori Ujian, Jenis Tes (TWK/TIU/TKP), Topik, Tingkat Kesulitan.
- **Tips**: Buat select topik dinamis berdasarkan jenis tes (jQuery AJAX).

### 3.2 CRUD Materi Ajar
- Form input materi: judul, kategori, topik, konten HTML, tipe (artikel/video/flashcard).
- Textarea besar untuk konten, bisa embed YouTube jika tipe video.
- Flashcard: simpan sebagai JSON atau tabel terpisah (`flashcard_front`, `flashcard_back`).

### 3.3 CRUD Paket Ujian
- Admin pilih jumlah soal TWK, TIU, TKP yang akan diujikan.
- Sistem auto-pick soal dari bank berdasarkan kategori & jumlah, atau admin pilih manual.
- Atur waktu pengerjaan (menit) dan status aktif/nonaktif.

---

## FASE 4: SIMULASI TRY-OUT (PESERTA) (Minggu 3)
### 4.1 List Try-Out
- Tampilkan paket ujian yang aktif dalam bentuk card (Bootstrap).
- Info: Nama paket, jumlah soal, waktu, passing grade, status (sudah/belum dikerjakan).

### 4.2 Halaman Kerja Soal (CRITICAL)
- **Layout Mobile**: 1 soal per layar. Navigasi swipe-like atau tombol besar Previous/Next.
- **Timer**: JavaScript countdown (`setInterval`), simpan `timeLeft` di `localStorage` sebagai backup jika refresh.
- **Navigasi Nomor**: Grid 5x? (atau scroll horizontal) di bagian bawah, warnai: hijau=sudah jawab, kuning=ragu, abu=belum.
- **Tombol Ragu-ragu**: Toggle flag, tandai di navigasi.
- **Submit**: Konfirmasi modal sebelum submit. Auto-submit saat timer habis.

### 4.3 AJAX Flow (jQuery)
```javascript
// Saat peserta klik opsi jawaban
$(document).on('click', '.opsi-jawaban', function(){
  let soal_id = $(this).data('soal');
  let opsi_id = $(this).data('opsi');
  let is_ragu = $('#ragu-checkbox').is(':checked');
  
  $.post('api/simpan_jawaban_temp.php', {
    soal_id: soal_id,
    opsi_id: opsi_id,
    is_ragu: is_ragu,
    waktu_digunakan: elapsedSeconds
  }, function(response){
    // Update navigasi warna
    updateNavigasi(soal_id, 'dijawab');
  });
});
```
- Simpan jawaban sementara di tabel `detail_jawaban` dengan `hasil_ujian_id` dan `is_final=0`.
- Saat submit, update `is_final=1` dan jalankan `hitungSkor()`.

### 4.4 Anti-Cheat (Sederhana)
- `document.addEventListener('contextmenu', e => e.preventDefault());`
- `document.addEventListener('visibilitychange', () => { if(document.hidden) tabSwitchCount++; });`
- Jika tab switch > 3 kali, tampilkan peringatan (atau auto-submit untuk versi ketat).

---

## FASE 5: PENILAIAN & HASIL UJIAN (Minggu 3-4)
### 5.1 Auto-Grading (PHP)
```php
function hitungSkorTIU_TWK($jawaban_benar) { return $jawaban_benar * 5; }
function hitungSkorTKP($detail_jawaban) {
  $total = 0;
  foreach($detail_jawaban as $j) { $total += $j['bobot_nilai']; }
  return $total;
}
```
- Simpan hasil di tabel `hasil_ujian`.
- Bandingkan dengan passing grade tabel `kategori_ujian`.

### 5.2 Tampilan Hasil
- **Ringkasan**: Card besar skor kumulatif + status LULUS/GUGUR.
- **Per Subtes**: TWK, TIU, TKP (progress bar vs passing grade).
- **Detail**: Tabel soal yang salah + kunci jawaban + pembahasan + tips trik.
- Tombol "Lihat Rapor Lengkap" → redirect ke halaman Rapor.

---

## FASE 6: ANALISIS & REKOMENDASI (Fitur Bimbel) (Minggu 4)
### 6.1 Analisis Topik Lemah (PHP Query)
```sql
SELECT soal.topik, soal.jenis_tes, 
  COUNT(*) as total_soal,
  SUM(CASE WHEN dj.nilai_diperoleh > 0 THEN 1 ELSE 0 END) as benar,
  ROUND(AVG(dj.waktu_detik)) as rata_waktu
FROM detail_jawaban dj
JOIN soal ON dj.soal_id = soal.id
WHERE dj.hasil_ujian_id = ?
GROUP BY soal.topik, soal.jenis_tes
ORDER BY (benar/total_soal) ASC;
```
- Tampilkan topik dengan persentase benar terendah.

### 6.2 Generate Rekomendasi (PHP)
- Loop hasil analisis: jika persentase benar < 50%, insert ke `rekomendasi_belajar`.
- Mapping topik ke `materi_id` yang relevan.
- Saran tingkat kesulitan: jika masih sangat lemah → mulai dari Mudah; jika sedang → Sedang/Sulit.

### 6.3 Halaman Rapor (Peserta)
- **Grafik Batang**: Skor 5 try-out terakhir (jQuery + Canvas atau library kecil seperti Chart.js via CDN).
- **Tabel Topik**: Sortable, warna merah (lemah), kuning (cukup), hijau (kuat).
- **List Rekomendasi**: Card dengan tombol "Mulai Belajar" → link ke materi.
- **Saran Personal**: Text dinamis berdasarkan hasil analisis. Contoh:
  - "Anda lemah di TIU - Logika Matematika. Rata-rata waktu 120 detik/soal. Tips: Gunakan rumus cepat perbandingan."
  - "TKP Anda cukup bagus, tapi masih ragu di topik Integritas. Pelajari contoh kasus di materi X."

---

## FASE 7: DASHBOARD ADMIN & LAPORAN (Minggu 4-5)
### 7.1 Dashboard Ringkasan
- Card: Total peserta, rata-rata skor kumulatif, peserta lulus/gugur.
- Grafik distribusi nilai (Chart.js).

### 7.2 Detail Per Peserta
- Tabel peserta dengan filter & search.
- Klik nama → modal/halaman detail progress, riwayat ujian, topik kelemahan.
- Admin bisa tambahkan catatan manual untuk peserta (simpan di tabel `catatan_pengajar`).

### 7.3 Analisis Butir Soal
- Query per soal:
```sql
SELECT soal.pertanyaan,
  COUNT(dj.id) as total_dijawab,
  SUM(CASE WHEN dj.nilai_diperoleh > 0 THEN 1 ELSE 0 END) as total_benar,
  ROUND(total_benar / total_dijawab, 2) as indeks_kesukaran
FROM soal
LEFT JOIN detail_jawaban dj ON soal.id = dj.soal_id
WHERE soal.paket_ujian_id = ?
GROUP BY soal.id;
```
- Klasifikasi: indeks > 0.8 = Mudah, 0.4-0.8 = Sedang, < 0.4 = Sulit.
- Daya Pembeda: bandingkan % benar kelompok atas (skor tinggi) vs kelompok bawah (skor rendah).
- Tampilkan sebagai tabel dengan badge warna.

---

## FASE 8: UI/UX POLISH & RESPONSIVE (Minggu 5)
### 8.1 Mobile-First Checklist
- [ ] Semua tombol touch-friendly (min height 44px).
- [ ] Font-size minimum 16px di input (hindari zoom iOS).
- [ ] Gunakan Bootstrap Offcanvas untuk menu navigasi mobile.
- [ ] Halaman kerja soal: test di HP Android/iOS (Chrome DevTools mobile view).
- [ ] Hindari horizontal scroll.

### 8.2 PWA-lite (Opsional tapi Recommended)
- Buat `manifest.json` sederhana (nama app, icon, theme color).
- Tambah service worker basic (cache static assets).
- Bisa "Add to Home Screen" di HP peserta untuk akses cepat.

### 8.3 Notifikasi (Opsional)
- Gunakan Bootstrap Toast atau custom notifikasi untuk:
  - "Waktu tersisa 10 menit!"
  - "Rekomendasi belajar baru tersedia."
  - "Jadwal try-out besok pukul 08:00."

---

## FASE 9: TESTING & DEPLOYMENT (Minggu 5-6)
### 9.1 Testing Scenarios
- **Test Case Ujian**:
  - Peserta login → pilih try-out → kerjakan 5 soal → submit → cek hasil & pembahasan.
  - Refresh halaman saat ujian → apakah timer & jawaban tersimpan (localStorage + DB)?
  - Timer habis → apakah auto-submit & skor terekam?
- **Test Case Analisis**:
  - Kerjakan ujian dengan sengaja salah di topik X → cek apakah rekomendasi muncul dengan benar.
- **Test Case Admin**:
  - Input soal TKP dengan bobot 1-5 → cek apakah penilaian TKP akurat.
  - Generate paket ujian → cek apakah jumlah soal sesuai.

### 9.2 Performance
- Indexing database pada kolom yang sering di-query: `soal.jenis_tes`, `soal.topik`, `detail_jawaban.hasil_ujian_id`, `hasil_ujian.user_id`.
- Gunakan AJAX untuk submit jawaban agar tidak reload halaman.
- Minify CSS/JS jika diperlukan (opsional untuk skala kecil).

### 9.3 Backup & Maintenance
- Export database SQL rutin (mingguan).
- Simpan backup di folder `/backup/` di luar public_html jika online.

---

## CHECKLIST AKHIR (Sebelum Launch)
- [ ] Semua query menggunakan prepared statements.
- [ ] Password di-hash, tidak ada plain text.
- [ ] Session validation di setiap halaman terproteksi.
- [ ] Tampilan mobile sudah dicek di device HP sungguhan.
- [ ] Bank soal sudah terisi minimal 200 soal per kategori (CPNS & Kedinasan).
- [ ] Materi ajar sudah terisi dasar (Pancasila, UUD 1945, Rumus TIU, Tips TKP).
- [ ] Admin sudah bisa melihat laporan per peserta.
- [ ] Peserta sudah bisa melihat rekomendasi setelah try-out.
- [ ] Timer & auto-submit berfungsi dengan baik.
- [ ] Tidak ada error PHP notice/warning di mode production.

---

## SARAN PENGEMBANGAN LANJUTAN (V2)
- Tambahkan mode "Ujian Offline" (simpan jawaban di localStorage, sync saat online).
- Integrasi WhatsApp Gateway untuk pengingat jadwal try-out & hasil.
- Leaderboard internal (hanya 10 peserta) dengan badge/poin gamification.
- Import soal bulk via Excel (PHPSpreadsheet) agar admin tidak input manual 1 per 1.
- Video call / live session sederhana (Google Meet embed) untuk sesi tanya jawab.
