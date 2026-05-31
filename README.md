# TryOutKu - Aplikasi Try-Out & Bimbel CPNS Mandiri

Aplikasi web try-out dan bimbingan belajar (bimbel) mandiri untuk persiapan ujian CPNS (Calon Pegawai Negeri Sipil). Dibangun dengan PHP native + MySQLi, dilengkapi sistem anti-cheat, PWA-lite, timer ujian dengan backup localStorage, dan analisis butir soal untuk admin.

---

## Fitur Utama

### Untuk Peserta
- **Try-Out Full** - Ujian TWK, TIU, TKP dengan timer dan anti-cheat
- **Mini Try-Out** - Ujian singkat 15 soal untuk latihan cepat
- **Latihan per Topik** - Fokus latihan pada topik tertentu
- **Flashcard** - Belajar materi dengan kartu interaktif
- **Tes Psikologi** - Kraepelin, Wartegg, dan EPPS untuk seleksi kedinasan
- **Materi Ajar** - Artikel dan rumus untuk setiap topik soal
- **Forum Q&A Pintar** - Tanya jawab dengan pencarian keyword otomatis
- **Rapor & Grafik** - Statistik skor dengan Chart.js (radar chart)
- **Leaderboard** - Peringkat peserta berdasarkan skor kumulatif
- **Profil Peserta** - Edit profil dan ganti password

### Untuk Admin
- **Dashboard** - Ringkasan statistik peserta dan ujian
- **Kelola Soal** - CRUD soal dengan upload gambar dan dynamic topik
- **Kelola Materi** - CRUD materi ajar (artikel, rumus, video)
- **Kelola Paket Ujian** - Buat paket try-out dengan soal pilihan
- **Laporan Hasil Ujian** - Lihat hasil dan detail jawaban peserta
- **Analisis Butir Soal** - Indeks kesukaran & daya pembeda per soal
- **Catatan Pengajar** - Tambah catatan evaluasi per peserta
- **Jawab Forum** - Kelola pertanyaan dari peserta
- **Export CSV** - Export data peserta dan hasil ujian

### Sistem
- **Anti-Cheat** - Blokir tab switch, window blur, copy/paste, F12, Ctrl+Shift+I
- **Timer dengan Backup** - localStorage recovery jika browser crash
- **PWA-lite** - Service worker + manifest untuk install ke home screen
- **Toast Notification** - Notifikasi real-time saat ujian
- **Role-based Access** - Admin dan Peserta dengan session terpisah

---

## Tech Stack

| Layer | Teknologi |
|-------|-----------|
| Backend | PHP 8.x Native (no framework) |
| Database | MySQL 8.x + MySQLi |
| Frontend | Bootstrap 5, Bootstrap Icons, jQuery 3.6 |
| Charts | Chart.js 4.x |
| Testing | Playwright (E2E) |
| Scraper | Python 3 + BeautifulSoup + mysql-connector |

---

## Persyaratan Sistem

- PHP >= 8.0
- MySQL >= 8.0
- Apache dengan mod_rewrite (opsional)
- Python 3.10+ (untuk scraper/generator)
- Node.js 18+ (untuk Playwright testing)

---

## Instalasi

### 1. Clone / Extract ke Web Server

```bash
# XAMPP (Windows)
# Extract ke C:\xampp\htdocs\ujian\

# Atau clone
# git clone <repo> C:\xampp\htdocs\ujian
```

### 2. Setup Database

Buka browser, akses:
```
http://localhost/ujian/setup_db.php
```

Script ini akan:
1. Membuat database `db_tryout`
2. Import schema dari `db_tryout.sql`
3. Import seed data dari `db_seed.sql`

### 3. Konfigurasi

Edit `config.php` sesuai environment:

```php
<?php
// Database
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'root');
define('DB_NAME', 'db_tryout');

// Base URL
define('BASE_URL', 'http://localhost/ujian/');
define('APP_NAME', 'TryOutKu');
```

### 4. Selesai

Akses aplikasi di:
- **Landing Page**: `http://localhost/ujian/`
- **Login**: `http://localhost/ujian/login.php`

---

## Akun Demo

| Role | Email | Password |
|------|-------|----------|
| Admin | `admin@tryoutku.com` | `password` |
| Peserta | `peserta_demo@tryoutku.com` | `password` |

---

## Struktur Direktori

```
ujian/
|-- index.php                  # Landing page
|-- login.php                  # Login (admin & peserta)
|-- register.php               # Register peserta
|-- config.php                 # Konfigurasi DB & app
|-- setup_db.php               # Setup database otomatis
|-- db_tryout.sql              # Schema database
|-- db_seed.sql                # Data awal (kategori, paket, soal seed)
|
|-- admin/                     # Panel Admin
|   |-- dashboard.php          # Dashboard admin
|   |-- kelola_soal.php        # List soal
|   |-- kelola_soal_form.php   # Form tambah/edit soal
|   |-- kelola_materi.php      # List materi
|   |-- kelola_materi_form.php # Form materi
|   |-- kelola_paket.php       # Kelola paket ujian
|   |-- laporan.php            # Laporan hasil ujian
|   |-- analisis_butir.php     # Analisis soal (difficulty/discrimination)
|   |-- catatan_pengajar.php   # Catatan evaluasi peserta
|   |-- jawab_forum.php        # Jawab pertanyaan forum
|   |-- export.php             # Export CSV
|   |-- profil.php             # Profil admin
|
|-- peserta/                   # Panel Peserta
|   |-- dashboard.php          # Dashboard peserta
|   |-- tryout_list.php        # Pilih paket try-out
|   |-- tryout_kerja.php       # Halaman ujian (timer + anti-cheat)
|   |-- tryout_hasil.php       # Hasil ujian
|   |-- mini_tryout.php        # Mini try-out
|   |-- latihan_kerja.php      # Latihan per topik
|   |-- belajar.php            # Daftar materi
|   |-- flashcard.php          # Flashcard list
|   |-- flashcard_detail.php   # Flashcard detail
|   |-- rapor.php              # Rapor + grafik
|   |-- leaderboard.php        # Peringkat peserta
|   |-- forum.php              # Forum Q&A
|   |-- profil.php             # Edit profil & password
|
|-- api/                       # AJAX Endpoints
|   |-- get_topik_by_jenis.php # Dynamic topik select
|   |-- simpan_jawaban.php     # Simpan jawaban peserta
|   |-- submit_ujian.php       # Submit & hitung skor
|   |-- upload_image.php       # Upload gambar soal
|
|-- includes/
|   |-- functions.php           # Helper functions, auth, flash message
|   |-- header.php              # Header HTML + CSS/JS includes
|   |-- footer.php              # Footer + JS scripts
|   |-- navbar_admin.php        # Navbar admin
|   |-- navbar_peserta.php      # Navbar peserta
|   |-- auth_middleware.php     # Middleware role check
|
|-- assets/
|   |-- css/style.css           # Custom styles
|   |-- js/app.js               # Timer, anti-cheat, AJAX, toast
|
|-- tests/                      # E2E Playwright Tests
|   |-- e2e_comprehensive.spec.js # 20 test coverage
|   |-- simulasi_ujian.spec.js   # Simulasi ujian 2 peserta
|   |-- playwright.config.js
|   |-- package.json
|
|-- generate_soal_cpns.php      # CLI: Generate soal CPNS seed
|-- import_soal_ai.py           # Python: Import soal dari AI knowledge
|-- scraper_cpns.py             # Python: Scraping soal dari internet
|-- generate_materi_otomatis.py # Python: Generate materi dari pembahasan
|-- verifikasi_materi.py        # Python: Verifikasi coverage materi
|-- insert_materi_berkualitas.py # Python: Insert materi ajar berkualitas per topik
|-- insert_bank_twk.py           # Python: Insert 50 soal TWK
|-- insert_bank_tiu.py           # Python: Insert 38 soal TIU
|-- insert_bank_tkp.py           # Python: Insert 34 soal TKP
|-- insert_soal_psikologi.py     # Python: Insert 35 soal Psikologi (Wartegg + EPPS)
|-- insert_materi_psikologi.py   # Python: Insert materi Psikologi
|-- alter_enum_psikologi.php     # PHP: Migration tambah 'psikologi' ke enum DB
|
|-- manifest.json               # PWA manifest
|-- sw.js                       # Service Worker (PWA-lite)
|-- README.md                   # Dokumentasi ini
```

---

## Skrip CLI (Terminal)

### Generate Soal CPNS (PHP)
```bash
cd C:\xampp\htdocs\ujian
C:\xampp\php\php.exe generate_soal_cpns.php
```
Menghasilkan 24 soal TWK/TIU/TKP dengan pembahasan dan tips.

### Import Soal dari AI Knowledge (Python)
```bash
cd C:\xampp\htdocs\ujian
python import_soal_ai.py
```
Menghasilkan 16+ soal unik dari AI knowledge dengan rate limiting.

### Scraping Soal dari Internet (Python)
```bash
cd C:\xampp\htdocs\ujian
python scraper_cpns.py
```
Scrape soal dari kitalulus.com, skillacademy.com, dll.

### Generate Materi Ajar Otomatis (Python)
```bash
cd C:\xampp\htdocs\ujian
python generate_materi_otomatis.py
```
Generate materi ajar dari pembahasan soal yang ada di database.

### Verifikasi Materi vs Soal (Python)
```bash
cd C:\xampp\htdocs\ujian
python verifikasi_materi.py
```
Cek coverage: apakah semua topik soal sudah punya materi.

### Insert Materi Ajar Berkualitas (Python)
```bash
cd C:\xampp\htdocs\ujian
python insert_materi_berkualitas.py
```
Insert 10 materi ajar berkualitas per topik besar (Pancasila, UUD 1945, Sejarah, NKRI, Verbal, Numerik, Logika, Profesionalisme, Integritas, Pelayanan).

### Tambah Bank Soal Bulk (Python)
```bash
cd C:\xampp\htdocs\ujian
python insert_bank_twk.py
python insert_bank_tiu.py
python insert_bank_tkp.py
python insert_soal_psikologi.py
```
Insert 50 TWK + 38 TIU + 34 TKP + 35 Psikologi = 157 soal baru secara batch.

### Migration Psikologi ke Database
```bash
cd C:\xampp\htdocs\ujian
C:\xampp\php\php.exe alter_enum_psikologi.php
python insert_soal_psikologi.py
python insert_materi_psikologi.py
```
Tambah enum 'psikologi' ke DB, insert soal Wartegg & EPPS, dan materi ajar.

---

## Testing (Playwright)

### Setup
```bash
cd C:\xampp\htdocs\ujian\tests
npm install
npx playwright install
```

### Jalankan Test
```bash
# Headless (cepat)
npx playwright test e2e_comprehensive.spec.js --reporter=line

# Headed (lihat browser)
npx playwright test e2e_comprehensive.spec.js --headed

# Simulasi ujian 2 peserta
npx playwright test simulasi_ujian.spec.js --headed --workers=1
```

### Coverage Test
- Landing page
- Admin login & dashboard
- Peserta register & login
- Kelola Soal, Materi, Paket, Laporan, Export
- Peserta Dashboard, Try-Out, Mini Try-Out, Latihan
- Belajar, Flashcard, Leaderboard, Forum, Rapor
- Navbar links validation

---

## Passing Grade (Default)

| Jenis | Minimal |
|-------|---------|
| TWK | 65 |
| TIU | 80 |
| TKP | 156 |
| Kumulatif | 311 |

> Dapat diubah di `config.php` dan tabel `kategori_ujian`.

---

## Penanganan Duplikat Soal

Setiap soal di-hash dengan MD5 dari pertanyaan. Saat insert (via PHP maupun Python), sistem akan:
1. Hitung MD5 dari teks pertanyaan
2. Cek ke database: `SELECT id FROM soal WHERE MD5(pertanyaan) = hash`
3. Jika sudah ada → **skip**
4. Jika belum ada → **insert**

Ini memastikan tidak ada soal duplikat meski script dijalankan berulang kali.

---

## Tips Penggunaan

1. **Setup database** selalu lewat `setup_db.php` pertama kali
2. **Insert materi berkualitas** dengan `insert_materi_berkualitas.py`
3. **Tambah bank soal** dengan `insert_bank_twk.py`, `insert_bank_tiu.py`, `insert_bank_tkp.py`
4. **Verifikasi** dengan `verifikasi_materi.py` untuk cek coverage
5. **Testing** dengan Playwright sebelum deploy

---

## Lisensi

Open Source - bebas digunakan untuk keperluan edukasi dan ujian mandiri.

---

## Dibangun Oleh

TryOutKu Team - Aplikasi Bimbel & Try-Out CPNS Mandiri
