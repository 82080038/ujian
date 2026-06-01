# TryOutKu Development Rules

## Project Overview
- **Name**: TryOutKu - Bimbel Online CPNS
- **Tech Stack**: PHP 8.x Native, MySQL 8.x, Bootstrap 5, jQuery, Chart.js, Playwright
- **Database**: db_tryout (password: '' on Linux, '8208' on Windows)
- **Base URL**: http://localhost/ujian/

## Architecture
- **Entry Points**: index.php (landing), login.php, register.php
- **Routing**: File-based routing with role-based redirects
- **Controllers**: Direct PHP files in admin/ and peserta/ directories
- **API**: AJAX endpoints in api/ directory
- **Database**: MySQLi with prepared statements

## Key Flows
1. **Authentication**: login.php → session → redirect based on role
2. **Exam Flow**: tryout_list.php → tryout_kerja.php → simpan_jawaban_temp.php → submit_ujian.php → tryout_hasil.php
3. **Admin Flow**: dashboard.php → CRUD operations (soal, materi, paket)
4. **Anti-Cheat**: JavaScript in app.js (tab switch, blur, copy/paste prevention)

## Database Schema
- **users**: id, nama, email, password, role (admin/peserta), target_ujian
- **soal**: id, jenis_tes (twk/tiu/tkp/psikologi), topik, pertanyaan, gambar_url, pembahasan
- **opsi_jawaban**: id, soal_id, label (A-E), teks_jawaban, bobot_nilai, is_kunci
- **paket_ujian**: id, kategori_ujian_id, nama_paket, waktu_menit, status
- **paket_soal**: paket_ujian_id, soal_id (many-to-many)
- **hasil_ujian**: id, user_id, paket_ujian_id, skor_twk/tiu/tkp, status_lulus
- **detail_jawaban**: id, hasil_ujian_id, soal_id, opsi_dipilih_id, nilai_diperoleh, is_ragu
- **materi**: id, judul, topik, jenis_tes, konten_html, tipe, level
- **kategori_ujian**: id, nama, passing_grade values, jumlah_soal per jenis

## Scoring Logic
- **TWK/TIU**: 5 points per correct answer
- **TKP**: 1-5 points per answer (based on option weight)
- **Passing Grade**: TWK≥65, TIU≥80, TKP≥166, Kumulatif≥311

## Development Guidelines
- Use prepared statements for all SQL queries
- Implement CSRF protection for forms
- Validate and sanitize all user inputs
- Follow role-based access control (requireAdmin(), requirePeserta())
- Use helper functions from includes/functions.php
- Maintain anti-cheat features in exam mode

## Testing
- Use Playwright for E2E testing
- Test files in tests/ directory
- Run with: npx playwright test --headed
- Coverage: authentication, exam flow, admin operations, participant features

## Common Issues
- Database password differs by environment (Linux: '', Windows: '8208')
- XAMPP services must be running (Apache, MySQL)
- MAX_PESERTA limit (default: 10)
- Timer uses localStorage for recovery
