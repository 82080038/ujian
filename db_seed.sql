-- SEED DATA: Admin, Soal Demo, Materi Demo
-- Jalankan setelah import db_tryout.sql

USE db_tryout;

-- Admin default
INSERT INTO users (nama, email, password, no_hp, role, target_ujian) VALUES
('Admin TryOut', 'admin@tryoutku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '081234567890', 'admin', 'cpns');
-- password default: password

-- Demo Peserta (for E2E testing)
INSERT INTO users (nama, email, password, no_hp, role, target_ujian) VALUES
('Peserta Demo', 'peserta_demo@tryoutku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '081234567891', 'peserta', 'cpns');

-- Soal TWK Demo
INSERT INTO soal (kategori_ujian_id, jenis_tes, topik, pertanyaan, tingkat_kesulitan, pembahasan, tips_triks) VALUES
(1, 'twk', 'Pancasila - Sila ke-1', 'Pancasila sebagai ideologi terbuka memiliki batas-batas berikut, kecuali...', 'sedang', 'Pancasila sebagai ideologi terbuka memungkinkan perkembangan norma dengan konsensus, namun tetap melarang ideologi radikal seperti Marxisme-Leninisme dan mempertahankan stabilitas nasional. Penciptaan norma baru tetap memerlukan konsensus.','Tips: Ingat, ideologi terbuka bukan berarti tanpa batas. Konsensus tetap diperlukan untuk setiap perubahan norma.'),
(1, 'twk', 'Pancasila - Sila ke-3', 'Mengembangkan sikap bahwa bangsa Indonesia merupakan bagian dari seluruh umat manusia merupakan perwujudan sila ke...', 'mudah', 'Sila ke-2 Pancasila: Kemanusiaan yang Adil dan Beradab. Mengakui persamaan derajat dan martabat setiap manusia secara universal.','Tips: Sila ke-2 berhubungan dengan kemanusiaan universal. Jika soal tentang hubungan antar-manusia global, jawabannya Sila 2.'),
(1, 'twk', 'UUD 1945', 'Pancasila digunakan sebagai dasar untuk mengatur penyelenggaraan ketatanegaraan negara, hal ini sesuai dengan kedudukan Pancasila sebagai...', 'mudah', 'Pancasila sebagai Dasar Negara (opening UUD 1945). Menjadi fondasi bagi seluruh sistem ketatanegaraan Indonesia.','Tips: Kalimat "mengatur penyelenggaraan ketatanegaraan" selalu merujuk pada kedudukan Pancasila sebagai DASAR NEGARA.'),
(1, 'twk', 'NKRI', 'Warna merah dalam bendera Republik Indonesia melambangkan...', 'sangat_mudah', 'Merah melambangkan keberanian (berani mengorbankan jiwa dan raga). Putih melambangkan kesucian (kesucian hati dan niat).','Tips: Merah = Keberanian, Putih = Kesucian. Ingat dengan mnemonik: "Merah Berani, Putih Suci".');

-- Opsi TWK
-- Soal 1
INSERT INTO opsi_jawaban (soal_id, label, teks_jawaban, bobot_nilai, is_kunci) VALUES
(1, 'A', 'Mencegah berkembangnya paham dan ideologi liberal', 0, 0),
(1, 'B', 'Penciptaan norma baru tidak perlu memiliki konsensus', 5, 1),
(1, 'C', 'Larangan terhadap ideologi Marxisme, Leninisme, dan Komunisme', 0, 0),
(1, 'D', 'Larangan terhadap pandangan ekstrim yang meresahkan masyarakat', 0, 0),
(1, 'E', 'Menekankan pandangan stabilitas nasional yang sehat dan dinamis', 0, 0);

-- Soal 2
INSERT INTO opsi_jawaban (soal_id, label, teks_jawaban, bobot_nilai, is_kunci) VALUES
(2, 'A', '1', 0, 0),
(2, 'B', '2', 5, 1),
(2, 'C', '3', 0, 0),
(2, 'D', '4', 0, 0),
(2, 'E', '5', 0, 0);

-- Soal 3
INSERT INTO opsi_jawaban (soal_id, label, teks_jawaban, bobot_nilai, is_kunci) VALUES
(3, 'A', 'Pandangan hidup bangsa', 0, 0),
(3, 'B', 'Moral pembangunan bangsa', 0, 0),
(3, 'C', 'Jiwa kepribadian bangsa', 0, 0),
(3, 'D', 'Dasar negara', 5, 1),
(3, 'E', 'Perjanjian luhur bangsa', 0, 0);

-- Soal 4
INSERT INTO opsi_jawaban (soal_id, label, teks_jawaban, bobot_nilai, is_kunci) VALUES
(4, 'A', 'Darah para pejuang nasional', 0, 0),
(4, 'B', 'Kegagahan', 0, 0),
(4, 'C', 'Darah para korban yang gugur di medan perang', 0, 0),
(4, 'D', 'Keberanian', 5, 1),
(4, 'E', 'Kesucian', 0, 0);

-- Soal TIU Demo
INSERT INTO soal (kategori_ujian_id, jenis_tes, topik, pertanyaan, tingkat_kesulitan, pembahasan, tips_triks) VALUES
(1, 'tiu', 'Verbal - Sinonim', 'Mortasitas = ...', 'sedang', 'Mortasitas berasal dari kata "mortal" yang berarti kematian. Jadi mortalitas = angka kematian.','Tips: Ingat kata Latin "mort" = mati. Mortalitas = mortal = kematian.'),
(1, 'tiu', 'Numerik - Deret', '1, 5, 11, 19, 29, ..., 55', 'sedang', 'Pola: +4, +6, +8, +10, +12, +14. Jadi 29 + 12 = 41, dan 41 + 14 = 55.','Tips: Cek selisih antar angka. Jika selisih bertambah konstan (2), berarti pola kuadratik.'),
(1, 'tiu', 'Logika - Silogisme', 'Premis 1: Semua karyawan berdasi. Premis 2: Semua karyawan berjas. Simpulan: ...', 'mudah', 'Jika semua karyawan berdasi DAN berjas, maka sebagian karyawan berdasi dan berjas. Tidak bisa simpulkan semua berdasi saja karena semua juga berjas.','Tips: Perhatikan kata "semua" pada kedua premis. Simpulan yang valid harus mencakup kedua sifat.');

INSERT INTO opsi_jawaban (soal_id, label, teks_jawaban, bobot_nilai, is_kunci) VALUES
(5, 'A', 'Angka kematian', 5, 1),
(5, 'B', 'Angka kelahiran', 0, 0),
(5, 'C', 'Sebangsa hewan', 0, 0),
(5, 'D', 'Gerak', 0, 0),
(5, 'E', 'Pukulan', 0, 0);

INSERT INTO opsi_jawaban (soal_id, label, teks_jawaban, bobot_nilai, is_kunci) VALUES
(6, 'A', '39 dan 69', 0, 0),
(6, 'B', '41 dan 71', 5, 1),
(6, 'C', '35 dan 65', 0, 0),
(6, 'D', '39 dan 65', 0, 0),
(6, 'E', '40 dan 71', 0, 0);

INSERT INTO opsi_jawaban (soal_id, label, teks_jawaban, bobot_nilai, is_kunci) VALUES
(7, 'A', 'Sebagian karyawan bersepatu', 0, 0),
(7, 'B', 'Sebagian karyawan berdasi dan bersepatu', 0, 0),
(7, 'C', 'Sebagian karyawan berdasi', 0, 0),
(7, 'D', 'Sebagian karyawan berdasi dan berjas', 5, 1),
(7, 'E', 'Semua berdasi dan berjas', 0, 0);

-- Soal TKP Demo
INSERT INTO soal (kategori_ujian_id, jenis_tes, topik, pertanyaan, tingkat_kesulitan, pembahasan, tips_triks) VALUES
(1, 'tkp', 'Pelayanan Publik', 'Apabila Anda tidak sengaja merusak fasilitas perusahaan dan atasan mengetahuinya, sikap Anda adalah...', 'sedang', 'Jawaban terbaik menunjukkan integritas, tanggung jawab, dan inisiatif perbaikan langsung.','Tips: TKP = pilih jawaban yang PALING PROAKTIF, jujur, dan bertanggung jawab. Langsung minta maaf + jelaskan + ganti = skor tertinggi.'),
(1, 'tkp', 'Hubungan Kerja', 'Rekan kerja Anda egois dan sombong. Sikap Anda...', 'sedang', 'Memahami perbedaan karakter wajar, namun tetap memberitahu secara sopan untuk menghindari konflik.','Tips: Pilih jawaban yang menunjukkan empati tapi tetap korektif. Hindari balas dendam atau pasif menerima.');

INSERT INTO opsi_jawaban (soal_id, label, teks_jawaban, bobot_nilai, is_kunci) VALUES
(8, 'A', 'Spontan berbicara kepada atasan menjelaskan bahwa itu sudah rapuh', 2, 0),
(8, 'B', 'Spontan berbicara sendiri "duh ternyata barangnya sudah rapuh"', 1, 0),
(8, 'C', 'Langsung berbicara sendiri dengan kata-kata sopan agar atasan memahami', 3, 0),
(8, 'D', 'Langsung berbicara kepada atasan meminta maaf dan menjelaskannya', 2, 0),
(8, 'E', 'Langsung berbicara kepada atasan meminta maaf, menjelaskan, dan menggantinya', 5, 1);

INSERT INTO opsi_jawaban (soal_id, label, teks_jawaban, bobot_nilai, is_kunci) VALUES
(9, 'A', 'Memahami sifatnya dan balas perlakuan yang sama', 1, 0),
(9, 'B', 'Tidak terlalu memikirkan, fokus pada diri sendiri', 2, 0),
(9, 'C', 'Memahami perbedaan dan memberitahu secara sopan', 5, 1),
(9, 'D', 'Cuek dan hindari interaksi', 1, 0),
(9, 'E', 'Laporkan ke atasan tanpa diskusi pribadi', 3, 0);

-- Materi Demo
INSERT INTO materi (kategori_ujian_id, judul, topik, jenis_tes, konten_html, tipe, level) VALUES
(1, 'Pengantar Pancasila', 'Pancasila', 'twk', '<h4>Pengertian Pancasila</h4><p>Pancasila adalah dasar filsafat negara dan pandangan hidup bangsa Indonesia. Terdiri dari 5 sila yang menjadi fondasi kehidupan berbangsa dan bernegara.</p><h5>5 Sila Pancasila:</h5><ol><li>Ketuhanan Yang Maha Esa</li><li>Kemanusiaan yang Adil dan Beradab</li><li>Persatuan Indonesia</li><li>Kerakyatan yang Dipimpin oleh Hikmat Kebijaksanaan dalam Permusyawaratan/Perwakilan</li><li>Keadilan Sosial bagi Seluruh Rakyat Indonesia</li></ol>', 'artikel', 'dasar'),
(1, 'Rumus Cepat TIU - Perbandingan', 'Perbandingan', 'tiu', '<h4>Rumus Perbandingan</h4><p><strong>Perbandingan Senilai:</strong> a/b = c/d => a x d = b x c</p><p><strong>Perbandingan Berbalik Nilai (Pekerja & Waktu):</strong></p><p style="background:#f0f0f0;padding:10px;border-radius:5px"><strong>(Orang 1 x Hari 1) = (Orang 2 x Hari 2)</strong></p><p>Contoh: 6 orang menyelesaikan pekerjaan dalam 12 hari. Berapa hari jika 9 orang?</p><p>Jawaban: 6 x 12 = 9 x hari => 72/9 = 8 hari.</p>', 'rumus', 'menengah'),
(1, 'Tips Menjawab TKP', 'Pelayanan Publik', 'tkp', '<h4>Strategi TKP</h4><ul><li><strong>Prioritaskan integritas:</strong> Jujur, akui kesalahan, bertanggung jawab.</li><li><strong>Pelayanan terbaik:</strong> Pilih opsi yang paling membantu masyarakat.</li><li><strong>Hindari:</strong> Opsi pasif, egois, menyalahkan orang lain, atau menunda penyelesaian.</li><li><strong>Skoring:</strong> 5 = terbaik, 1 = terburuk. Semua opsi memiliki nilai.</li></ul>', 'artikel', 'dasar');
