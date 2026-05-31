# PROMPT LENGKAP: APLIKASI TRY-OUT & BIMBEL ONLINE (PHP NATIVE, jQuery, Bootstrap, MySQLi)

## 1. VISI & TUJUAN APLIKASI
Bangun aplikasi web try-out ujian (CPNS, Sekolah Kedinasan, dan ujian masuk PNS) yang **terintegrasi dengan sistem bimbel/belajar mandiri**. Aplikasi harus mampu:
- Menyelenggarakan simulasi ujian CAT (Computer Assisted Test) real-time.
- Menganalisis hasil ujian secara otomatis dan memberikan **rekomendasi pembelajaran personal** ( seperti guru privat ) berdasarkan kelemahan setiap peserta.
- Menyediakan bank soal lengkap dengan tingkat kesulitan, pembahasan, tips & trik per soal, dan materi ajar.
- Support multi-perangkat dengan prioritas mobile-first (HP) namun tetap responsif di tablet/desktop.
- Diperuntukkan untuk **maksimal 10 peserta aktif** dengan data hasil dan kemampuan yang dipersonalisasi per individu.

## 2. TEKNOLOGI YANG DIGUNAKAN
- **Backend**: PHP Native (procedural atau OOP sederhana), tanpa framework besar.
- **Database**: MySQLi (PHP Native).
- **Frontend**: HTML5, CSS3, JavaScript (jQuery untuk AJAX & DOM manipulation).
- **UI Framework**: Bootstrap 5 (prioritas komponen responsif, card, accordion, offcanvas untuk mobile).
- **Server**: XAMPP / Apache (sesuai environment user).
- **Keamanan**: Session-based auth, password_hash(), prepared statements MySQLi, CSRF token sederhana.

## 3. KATEGORI UJIAN & KISI-KISI (Berdasarkan Analisis Internet)
### A. CPNS (Seleksi Kompetensi Dasar - SKD)
- **TWK**: 35 soal | Passing Grade: 65 | Bobot: Benar=5, Salah=0 | Waktu: ~20-25 menit
  Materi: Pancasila (5 sila), UUD 1945 & Amandemen, NKRI, Bhinneka Tunggal Ika, Sejarah Nasional, Bela Negara.
- **TIU**: 30 soal | Passing Grade: 80 | Bobot: Benar=5, Salah=0 | Waktu: ~30-40 menit
  Materi: Kemampuan Verbal (sinonim, antonim, analogi), Kemampuan Numerik (deret, aritmatika, perbandingan), Kemampuan Logika (silogisme, analitis), Penalaran Spasial.
- **TKP**: 35 soal | Passing Grade: 166 | Bobot: Skoring 1-5 per opsi, Kosong=0 | Waktu: ~20-25 menit
  Materi: Pelayanan Publik, Hubungan Kerja, Sosial Budaya, Profesionalisme, Integritas, Komitmen.
- **Total**: 100 soal | 90 menit | Passing Grade Kumulatif: 311 (umum)

### B. SEKOLAH KEDINASAN (SKD)
- Sama dengan CPNS namun tingkat kesulitan TIU lebih tinggi (HOTS, 2-3 langkah penyelesaian).
- Total soal: 110 soal | Waktu: 100 menit.
- Passing Grade: TWK 65 | TIU 80 | TKP 156.
- Nilai Kumulatif Maks: 550 (TKP 225, TIU 175, TWK 150).
- **Karakteristik khusus**:
  - **STAN**: Fokus Keuangan Negara, syarat UTBK & SKD sangat tinggi.
  - **IPDN**: Tes fisik (kesamaptaan) dan mental ideologi sangat ketat.
  - **STIS**: Matematika murni jauh lebih sulit dari TIU biasa, banyak soal cerita & statistik.
  - **Poltekim/Poltekip**: Tambahan tes fisik dan kesehatan.

### C. TINGKAT KESULITAN SOAL (Teori Tes Klasik)
Setiap soal wajib memiliki label tingkat kesulitan berdasarkan daya pembeda:
- **Sangat Mudah** (Indeks Kesukaran: 0.81 - 1.00)
- **Mudah** (0.61 - 0.80)
- **Sedang** (0.41 - 0.60)
- **Sulit** (0.21 - 0.40)
- **Sangat Sulit** (0.00 - 0.20)
*Note: Indeks dihitung dari proporsi peserta yang menjawab benar.*

## 4. FITUR UTAMA APLIKASI
### A. MANAJEMEN PENGGUNA (Role-Based)
- **Admin/Pengajar**: CRUD soal, kelola materi, atur jadwal try-out, lihat laporan analitik seluruh peserta.
- **Peserta**: Daftar/login, ikut try-out, lihat riwayat, akses materi & pembahasan, lihat rapor personal.

### B. BANK SOAL (Soal Master)
- Fields wajib per soal:
  - ID Soal, Kategori Ujian (CPNS/Kedinasan/STAN/STIS/IPDN), Jenis Tes (TWK/TIU/TKP), Topik/Subtopik.
  - Pertanyaan, Gambar (opsional), Opsi A-E, Kunci Jawaban (untuk TKP: bobot per opsi 1-5).
  - Tingkat Kesulitan (Sangat Mudah - Sangat Sulit).
  - Pembahasan Lengkap (teks + gambar), Tips & Trik khusus soal tersebut.
  - Sumber/Konteks (jika ada).
- **Soal Acak**: Sistem mampu mengacak soal dan opsi jawaban per sesi ujian.

### C. SESI TRY-OUT / UJIAN (Simulasi CAT)
- **Mode Ujian**:
  - Mini Try-Out (per subtes: 20 soal, 20 menit).
  - Full Try-Out (110 soal, 100 menit / 100 soal, 90 menit sesuai jenis).
  - Try-Out Mandiri (peserta pilih sendiri jumlah soal & topik).
- **Timer**: Countdown real-time, auto-submit saat waktu habis.
- **Navigasi Soal**: Daftar nomor soal dengan status (sudah dijawab/belum/ragu-ragu).
- **Tampilan**: Satu soal per layar (mobile-friendly), hindari scroll berlebihan.
- **Anti-Cheat Sederhana**: Disable right-click, detect tab switch (opsional), 1 sesi aktif per akun.

### D. PENILAIAN & HASIL UJIAN (Real-Time)
- Hitung skor otomatis sesuai bobot (TWK/TIU: benar=5, salah=0; TKP: skala 1-5).
- Tampilkan skor per subtes dan skor kumulatif.
- Bandingkan dengan Passing Grade (progress bar visual).
- Status: LULUS / GUGUR per subtes & kumulatif.
- **Grafik Perkembangan**: Chart.js atau grafik sederhana (Bootstrap + Canvas/jQuery) menampilkan riwayat skor peserta per minggu/bulan.

### E. ANALISIS & REKOMENDASI (Fitur Bimbel / AI-Like)
> Ini adalah diferensiator utama: aplikasi tidak sekadar nilai, tapi memberikan solusi belajar.
- **Analisis Kelemahan**:
  - Identifikasi topik/subtopik mana yang paling banyak salah (misal: "Logika Matematika - Perbandingan", "Pancasila - Sila ke-3").
  - Identifikasi tingkat kesulitan soal yang masih sering salah (peserta lemah di Soal Sulit?).
  - Identifikasi waktu pengerjaan (subtes mana yang paling lama?).
- **Rapor Personal**:
  - Skor, Peringkat internal (di antara 10 peserta), Persentase keberhasilan.
  - Daftar 5 topik terlemah & 5 topik terkuat.
  - Grafik radar (TWK, TIU, TKP, Kecepatan, Ketelitian).
- **Rekomendasi Belajar Otomatis**:
  - Berdasarkan hasil analisis, sistem menyarankan materi pembelajaran spesifik dari library.
  - Saran latihan soal ekstra pada topik yang masih lemah, dengan tingkat kesulitan bertahap (mudah → sedang → sulit).
  - **Tips & Trik Personal**: Berikan strategi khusus berdasarkan profil peserta. Contoh:
    - Jika TIU lama: "Tips: Gunakan rumus cepat perbandingan berbalik nilai (Orang1×Hari1 = Orang2×Hari2)".
    - Jika TKP rendah: "Tips: Pilih jawaban yang menunjukkan pelayanan terbaik, integritas, dan profesionalisme tertinggi. Hindaki opsi yang pasif atau arogan."
    - Jika TWK rendah: "Fokus pelajari UUD 1945 Pasal 1-37 dan 4 Pilar Negara. Gunakan flashcard."
- **Materi Pembelajaran (Library)**:
  - Artikel/HTML materi per topik (bisa berisi teks, gambar, video embed).
  - Flashcard (kartu belajar) untuk TWK (Pancasila, UUD 1945).
  - Rumus Cepat TIU (PDF/HTML).
  - Video Pembahasan (link/embed jika ada).

### F. BIMBEL / PEMBELAJARAN MANDIRI
- **Dashboard Belajar**: Progress bar per topik materi.
- **Latihan Soal per Topik**: Peserta bisa latihan soal dari bank berdasarkan topik yang direkomendasikan.
- **Pembahasan Soal**: Setelah try-out, tampilkan soal yang salah beserta pembahasan dan tips.
- **Forum/Tanya Pengajar**: (Opsional) Area diskusi sederhana per topik.

### G. LAPORAN ADMIN / PENGAJAR
- **Dashboard Ringkasan**: Total peserta, rata-rata skor, grafik distribusi nilai.
- **Analisis Per Peserta**: Lihat detail hasil, progress, dan topik kelemahan setiap peserta.
- **Analisis Butir Soal**:
  - Statistik per soal: berapa % peserta yang jawab benar/salah tiap opsi.
  - Daya Pembeda otomatis (hitung indeks kesukaran & daya pembeda berdasarkan hasil try-out).
- **Export Hasil**: Export ke PDF/Excel (opsional, bisa menggunakan library PHP sederhana).

## 5. ARSITEKTUR DATABASE (MySQLi) - Rancangan Tabel
```sql
-- Users (Peserta & Admin)
users (id, nama, email, password_hash, role ENUM('admin','peserta'), no_hp, target_ujian ENUM('cpns','stan','stis','ipdn'), created_at)

-- Kategori Ujian
kategori_ujian (id, nama, deskripsi, passing_grade_twk, passing_grade_tiu, passing_grade_tkp, waktu_pengerjaan, jumlah_soal)

-- Materi / Bahan Ajar
materi (id, kategori_ujian_id, judul, topik, konten_html, tipe ENUM('artikel','video','flashcard','rumus'), level ENUM('dasar','menengah','lanjut'), created_at)

-- Bank Soal
soal (id, kategori_ujian_id, jenis_tes ENUM('twk','tiu','tkp'), topik, pertanyaan, gambar_url, tingkat_kesulitan ENUM('sangat_mudah','mudah','sedang','sulit','sangat_sulit'), pembahasan, tips_triks, created_at)

-- Opsi Jawaban (terpisah untuk fleksibilitas, terutama TKP dengan bobot)
opsi_jawaban (id, soal_id, label ENUM('A','B','C','D','E'), teks_jawaban, bobot_nilai INT DEFAULT 0, is_kunci BOOLEAN DEFAULT 0)

-- Sesi Try-Out / Paket Ujian
paket_ujian (id, kategori_ujian_id, nama_paket, jumlah_soal_twk, jumlah_soal_tiu, jumlah_soal_tkp, waktu_menit, status ENUM('aktif','nonaktif'))

-- Relasi Paket dengan Soal (Many-to-Many)
paket_soal (id, paket_ujian_id, soal_id)

-- Hasil Ujian Peserta
hasil_ujian (id, user_id, paket_ujian_id, tanggal_mulai, tanggal_selesai, skor_twk, skor_tiu, skor_tkp, skor_kumulatif, status_lulus ENUM('lulus','gugur'), created_at)

-- Detail Jawaban Peserta
detail_jawaban (id, hasil_ujian_id, soal_id, opsi_dipilih_id, nilai_diperoleh, waktu_detik INT, is_ragu BOOLEAN, created_at)

-- Rekomendasi Belajar (auto-generate setelah ujian)
rekomendasi_belajar (id, hasil_ujian_id, user_id, topik, jenis_tes, skor_persentase, saran_materi_id, tingkat_kesulitan_rekomendasi, status ENUM('belum_dikerjakan','selesai'))

-- Riwayat Akses Materi
riwayat_materi (id, user_id, materi_id, progress_persen, waktu_baca_menit, created_at)
```

## 6. ALUR KERJA APLIKASI
1. **Admin** login → Buat kategori ujian → Input materi ajar → Input bank soal (dengan opsi & bobot) → Buat paket ujian → Pilih soal dari bank → Publish.
2. **Peserta** login → Lihat dashboard (progress, rekomendasi terbaru) → Pilih Try-Out → Kerjakan soal dengan timer & navigasi → Submit / Auto-submit.
3. **Sistem** auto-grade → Hitung skor per subtes & kumulatif → Bandingkan passing grade → Simpan hasil & detail jawaban.
4. **Sistem** analisis kelemahan per topik → Generate rekomendasi belajar → Tampilkan rapor lengkap + grafik.
5. **Peserta** akses menu "Belajar" → Lihat rekomendasi & materi → Latihan soal topik yang lemah → Ulangi try-out.
6. **Admin** pantau dashboard analitik → Lihat statistik butir soal → Lihat progress per peserta → Berikan arahan manual jika perlu.

## 7. UI/UX PRIORITAS (Mobile-First)
- **Halaman Ujian**: Satu soal per layar, tombol navigasi besar (Previous/Next/Ragu-ragu), progress bar waktu di atas.
- **Halaman Hasil**: Card ringkasan skor berwarna (hijau=lulus, merah=gugur), accordion untuk detail per subtes.
- **Halaman Belajar**: List materi dengan badge progress, search/filter per topik.
- **Navigasi Bawah (Bottom Nav) untuk Mobile**: Beranda, Try-Out, Belajar, Rapor, Profil.
- **Font & Warna**: Font besar & jelas (min 16px), kontras tinggi, hindari elemen terlalu kecil di HP.
- **Loading**: Gunakan spinner/loading overlay saat submit jawaban agar peserta tidak double-click.

## 8. TIPS & TRIK YANG DIINTEGRASIKAN (Berdasarkan Analisis)
- **Strategi Waktu**:
  - TKP dulu (15-20 menit) → TWK (20-25 menit) → TIU terakhir (40-45 menit).
  - Atau: Kerjakan soal mudah dulu, loncati yang sulit.
- **Tips TKP**: Pilih jawaban yang paling proaktif, pelayanan publik terbaik, integritas tinggi. Hindari opsi pasif/ego/individualis.
- **Tips TIU**: Perbanyak latihan rumus cepat (perbandingan, deret, silogisme). Jangan menghitung manual panjang.
- **Tips TWK**: Flashcard Pancasila & UUD 1945. Kaitkan jawaban dengan nilai-nilai Pancasila.
- **Tips Umum**: Simulasi CAT rutin 30 menit/hari, tidur cukup, jaga kesehatan.

## 9. KEAMANAN & STABILITAS
- Validasi input di server-side (PHP).
- Prepared statements untuk semua query database.
- Session timeout setelah 30 menit tidak aktif.
- Backup database rutin (karena hanya 10 peserta, bisa manual/export).
- Cookie-based "remember me" opsional untuk kenyamanan login.

## 10. BATASAN & SKALA
- Maksimal 10 peserta aktif bersamaan.
- Tidak perlu real-time websocket kompleks; gunakan AJAX polling sederhana jika diperlukan.
- Hosting: XAMPP local atau shared hosting sederhana.
- Tidak perlu payment gateway (karena private/internal use).
