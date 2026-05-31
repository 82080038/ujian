-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 31 Bulan Mei 2026 pada 15.59
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_tryout`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `catatan_pengajar`
--

CREATE TABLE `catatan_pengajar` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `catatan` text NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_jawaban`
--

CREATE TABLE `detail_jawaban` (
  `id` int(11) NOT NULL,
  `hasil_ujian_id` int(11) NOT NULL,
  `soal_id` int(11) NOT NULL,
  `opsi_dipilih_id` int(11) DEFAULT NULL,
  `nilai_diperoleh` int(2) NOT NULL DEFAULT 0,
  `waktu_detik` int(5) DEFAULT 0,
  `is_ragu` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `detail_jawaban`
--

INSERT INTO `detail_jawaban` (`id`, `hasil_ujian_id`, `soal_id`, `opsi_dipilih_id`, `nilai_diperoleh`, `waktu_detik`, `is_ragu`, `created_at`) VALUES
(1, 1, 1, 2, 5, 78, 0, '2026-05-31 20:17:26'),
(2, 1, 2, 7, 5, 102, 0, '2026-05-31 20:17:26'),
(3, 1, 3, 14, 5, 63, 0, '2026-05-31 20:17:26'),
(4, 1, 4, 19, 5, 63, 0, '2026-05-31 20:17:26'),
(5, 1, 5, 21, 5, 57, 0, '2026-05-31 20:17:26'),
(6, 1, 6, 26, 0, 72, 0, '2026-05-31 20:17:26'),
(7, 1, 7, 34, 5, 82, 0, '2026-05-31 20:17:26'),
(8, 1, 8, 37, 1, 107, 1, '2026-05-31 20:17:26'),
(9, 1, 9, 43, 5, 111, 0, '2026-05-31 20:17:26'),
(10, 1, 10, 46, 5, 97, 0, '2026-05-31 20:17:26'),
(11, 1, 21, 101, 5, 78, 0, '2026-05-31 20:17:26'),
(12, 1, 22, 106, 5, 86, 0, '2026-05-31 20:17:26'),
(13, 1, 29, 142, 1, 64, 0, '2026-05-31 20:17:26'),
(14, 1, 30, 147, 1, 115, 1, '2026-05-31 20:17:26'),
(15, 1, 31, 151, 5, 46, 0, '2026-05-31 20:17:26'),
(16, 2, 1, 2, 5, 113, 0, '2026-05-31 20:18:41'),
(17, 2, 2, 7, 5, 57, 1, '2026-05-31 20:18:41'),
(18, 2, 3, 14, 5, 52, 1, '2026-05-31 20:18:42'),
(19, 2, 4, 19, 5, 60, 0, '2026-05-31 20:18:42'),
(20, 2, 5, 22, 0, 114, 0, '2026-05-31 20:18:42'),
(21, 2, 6, 27, 5, 72, 0, '2026-05-31 20:18:42'),
(22, 2, 7, 34, 5, 35, 0, '2026-05-31 20:18:42'),
(23, 2, 8, 37, 1, 89, 1, '2026-05-31 20:18:42'),
(24, 2, 9, 44, 1, 67, 0, '2026-05-31 20:18:42'),
(25, 2, 10, 46, 5, 56, 0, '2026-05-31 20:18:42'),
(26, 2, 21, 101, 5, 80, 0, '2026-05-31 20:18:42'),
(27, 2, 22, 107, 0, 88, 0, '2026-05-31 20:18:42'),
(28, 2, 29, 141, 5, 31, 0, '2026-05-31 20:18:42'),
(29, 2, 30, 147, 1, 61, 0, '2026-05-31 20:18:42'),
(30, 2, 31, 152, 1, 89, 1, '2026-05-31 20:18:42'),
(31, 3, 1, 2, 5, 104, 0, '2026-05-31 20:19:33'),
(32, 3, 2, 7, 5, 83, 0, '2026-05-31 20:19:33'),
(33, 3, 3, 14, 5, 96, 0, '2026-05-31 20:19:33'),
(34, 3, 4, 19, 5, 43, 1, '2026-05-31 20:19:33'),
(35, 3, 5, 22, 0, 78, 0, '2026-05-31 20:19:33'),
(36, 3, 6, 26, 0, 108, 0, '2026-05-31 20:19:33'),
(37, 3, 7, 34, 5, 101, 0, '2026-05-31 20:19:33'),
(38, 3, 8, 37, 1, 36, 1, '2026-05-31 20:19:33'),
(39, 3, 9, 44, 1, 117, 0, '2026-05-31 20:19:33'),
(40, 3, 10, 46, 5, 70, 1, '2026-05-31 20:19:33'),
(41, 3, 21, 101, 5, 32, 0, '2026-05-31 20:19:33'),
(42, 3, 22, 107, 0, 84, 0, '2026-05-31 20:19:33'),
(43, 3, 29, 141, 5, 81, 0, '2026-05-31 20:19:33'),
(44, 3, 30, 147, 1, 104, 1, '2026-05-31 20:19:33'),
(45, 3, 31, 152, 1, 68, 0, '2026-05-31 20:19:33'),
(46, 4, 1, 1, 0, 32, 0, '2026-05-31 20:19:33'),
(47, 4, 2, 7, 5, 110, 0, '2026-05-31 20:19:33'),
(48, 4, 3, 14, 5, 55, 0, '2026-05-31 20:19:33'),
(49, 4, 4, 16, 0, 73, 1, '2026-05-31 20:19:33'),
(50, 4, 5, 22, 0, 54, 0, '2026-05-31 20:19:33'),
(51, 4, 6, 27, 5, 40, 0, '2026-05-31 20:19:33'),
(52, 4, 7, 34, 5, 65, 1, '2026-05-31 20:19:33'),
(53, 4, 8, 37, 1, 117, 0, '2026-05-31 20:19:33'),
(54, 4, 9, 44, 1, 52, 1, '2026-05-31 20:19:33'),
(55, 4, 10, 46, 5, 110, 0, '2026-05-31 20:19:33'),
(56, 4, 21, 101, 5, 94, 1, '2026-05-31 20:19:33'),
(57, 4, 22, 106, 5, 108, 1, '2026-05-31 20:19:33'),
(58, 4, 29, 141, 5, 91, 0, '2026-05-31 20:19:33'),
(59, 4, 30, 146, 5, 98, 1, '2026-05-31 20:19:33'),
(60, 4, 31, 151, 5, 40, 1, '2026-05-31 20:19:33'),
(61, 5, 30, NULL, 0, 0, 0, '2026-05-31 20:22:05'),
(62, 5, 8, NULL, 0, 0, 0, '2026-05-31 20:22:05'),
(63, 5, 1, NULL, 0, 0, 0, '2026-05-31 20:22:05'),
(64, 5, 10, NULL, 0, 0, 0, '2026-05-31 20:22:05'),
(65, 5, 5, NULL, 0, 0, 0, '2026-05-31 20:22:05'),
(66, 5, 22, NULL, 0, 0, 0, '2026-05-31 20:22:05'),
(67, 5, 2, NULL, 0, 0, 0, '2026-05-31 20:22:05'),
(68, 5, 4, NULL, 0, 0, 0, '2026-05-31 20:22:05'),
(69, 5, 6, NULL, 0, 0, 0, '2026-05-31 20:22:05'),
(70, 5, 3, NULL, 0, 0, 0, '2026-05-31 20:22:05'),
(71, 5, 29, NULL, 0, 0, 0, '2026-05-31 20:22:05'),
(72, 5, 7, NULL, 0, 0, 0, '2026-05-31 20:22:05'),
(73, 5, 31, NULL, 0, 0, 0, '2026-05-31 20:22:05'),
(74, 5, 9, NULL, 0, 0, 0, '2026-05-31 20:22:05'),
(75, 5, 21, NULL, 0, 0, 0, '2026-05-31 20:22:05'),
(76, 6, 5, NULL, 0, 0, 0, '2026-05-31 20:22:30'),
(77, 6, 8, NULL, 0, 0, 0, '2026-05-31 20:22:30'),
(78, 6, 22, NULL, 0, 0, 0, '2026-05-31 20:22:30'),
(79, 6, 29, NULL, 0, 0, 0, '2026-05-31 20:22:30'),
(80, 6, 4, NULL, 0, 0, 0, '2026-05-31 20:22:30'),
(81, 6, 7, NULL, 0, 0, 0, '2026-05-31 20:22:30'),
(82, 6, 3, NULL, 0, 0, 0, '2026-05-31 20:22:30'),
(83, 6, 10, NULL, 0, 0, 0, '2026-05-31 20:22:30'),
(84, 6, 6, NULL, 0, 0, 0, '2026-05-31 20:22:30'),
(85, 6, 30, NULL, 0, 0, 0, '2026-05-31 20:22:30'),
(86, 6, 9, NULL, 0, 0, 0, '2026-05-31 20:22:30'),
(87, 6, 21, NULL, 0, 0, 0, '2026-05-31 20:22:30'),
(88, 6, 2, NULL, 0, 0, 0, '2026-05-31 20:22:30'),
(89, 6, 31, NULL, 0, 0, 0, '2026-05-31 20:22:30'),
(90, 6, 1, NULL, 0, 0, 0, '2026-05-31 20:22:30');

-- --------------------------------------------------------

--
-- Struktur dari tabel `hasil_ujian`
--

CREATE TABLE `hasil_ujian` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `paket_ujian_id` int(11) NOT NULL,
  `tanggal_mulai` datetime DEFAULT NULL,
  `tanggal_selesai` datetime DEFAULT NULL,
  `skor_twk` int(5) NOT NULL DEFAULT 0,
  `skor_tiu` int(5) NOT NULL DEFAULT 0,
  `skor_tkp` int(5) NOT NULL DEFAULT 0,
  `skor_kumulatif` int(5) NOT NULL DEFAULT 0,
  `status_lulus` enum('lulus','gugur','proses') DEFAULT 'proses',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `hasil_ujian`
--

INSERT INTO `hasil_ujian` (`id`, `user_id`, `paket_ujian_id`, `tanggal_mulai`, `tanggal_selesai`, `skor_twk`, `skor_tiu`, `skor_tkp`, `skor_kumulatif`, `status_lulus`, `created_at`) VALUES
(1, 6, 1, '2026-05-31 20:17:26', NULL, 0, 0, 0, 0, 'proses', '2026-05-31 20:17:26'),
(2, 8, 1, '2026-05-31 20:18:41', '2026-05-31 20:18:42', 25, 15, 9, 49, 'gugur', '2026-05-31 20:18:41'),
(3, 10, 1, '2026-05-31 20:19:33', '2026-05-31 20:19:33', 25, 10, 9, 44, 'gugur', '2026-05-31 20:19:33'),
(4, 11, 1, '2026-05-31 20:19:33', '2026-05-31 20:19:33', 15, 20, 17, 52, 'gugur', '2026-05-31 20:19:33'),
(5, 10, 1, '2026-05-31 20:22:05', '2026-05-31 20:24:05', 0, 0, 0, 0, 'gugur', '2026-05-31 20:22:05'),
(6, 11, 1, '2026-05-31 20:22:30', '2026-05-31 20:24:34', 0, 0, 0, 0, 'gugur', '2026-05-31 20:22:30');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori_ujian`
--

CREATE TABLE `kategori_ujian` (
  `id` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `passing_grade_twk` int(5) NOT NULL DEFAULT 65,
  `passing_grade_tiu` int(5) NOT NULL DEFAULT 80,
  `passing_grade_tkp` int(5) NOT NULL DEFAULT 166,
  `passing_grade_kumulatif` int(5) NOT NULL DEFAULT 311,
  `waktu_pengerjaan` int(5) NOT NULL DEFAULT 90,
  `jumlah_soal` int(5) NOT NULL DEFAULT 100,
  `jumlah_soal_twk` int(5) NOT NULL DEFAULT 35,
  `jumlah_soal_tiu` int(5) NOT NULL DEFAULT 30,
  `jumlah_soal_tkp` int(5) NOT NULL DEFAULT 35,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kategori_ujian`
--

INSERT INTO `kategori_ujian` (`id`, `nama`, `deskripsi`, `passing_grade_twk`, `passing_grade_tiu`, `passing_grade_tkp`, `passing_grade_kumulatif`, `waktu_pengerjaan`, `jumlah_soal`, `jumlah_soal_twk`, `jumlah_soal_tiu`, `jumlah_soal_tkp`, `created_at`) VALUES
(1, 'CPNS SKD 2024', 'Seleksi Kompetensi Dasar CPNS 2024', 65, 80, 166, 311, 90, 100, 35, 30, 35, '2026-05-31 20:12:21'),
(2, 'Kedinasan STAN', 'SKD PKN STAN', 65, 80, 156, 311, 100, 110, 30, 35, 45, '2026-05-31 20:12:21'),
(3, 'Kedinasan STIS', 'SKD Polstat STIS', 65, 80, 156, 311, 100, 110, 30, 35, 45, '2026-05-31 20:12:21'),
(4, 'Kedinasan IPDN', 'SKD IPDN', 65, 80, 156, 311, 100, 110, 30, 35, 45, '2026-05-31 20:12:21');

-- --------------------------------------------------------

--
-- Struktur dari tabel `materi`
--

CREATE TABLE `materi` (
  `id` int(11) NOT NULL,
  `kategori_ujian_id` int(11) DEFAULT NULL,
  `judul` varchar(255) NOT NULL,
  `topik` varchar(100) NOT NULL,
  `jenis_tes` enum('twk','tiu','tkp') DEFAULT NULL,
  `konten_html` longtext DEFAULT NULL,
  `tipe` enum('artikel','video','flashcard','rumus') DEFAULT 'artikel',
  `level` enum('dasar','menengah','lanjut') DEFAULT 'dasar',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `materi`
--

INSERT INTO `materi` (`id`, `kategori_ujian_id`, `judul`, `topik`, `jenis_tes`, `konten_html`, `tipe`, `level`, `created_at`) VALUES
(1, 1, 'Pengantar Pancasila', 'Pancasila', 'twk', '<h4>Pengertian Pancasila</h4><p>Pancasila adalah dasar filsafat negara dan pandangan hidup bangsa Indonesia. Terdiri dari 5 sila yang menjadi fondasi kehidupan berbangsa dan bernegara.</p><h5>5 Sila Pancasila:</h5><ol><li>Ketuhanan Yang Maha Esa</li><li>Kemanusiaan yang Adil dan Beradab</li><li>Persatuan Indonesia</li><li>Kerakyatan yang Dipimpin oleh Hikmat Kebijaksanaan dalam Permusyawaratan/Perwakilan</li><li>Keadilan Sosial bagi Seluruh Rakyat Indonesia</li></ol>', 'artikel', 'dasar', '2026-05-31 20:12:22'),
(2, 1, 'Rumus Cepat TIU - Perbandingan', 'Perbandingan', 'tiu', '<h4>Rumus Perbandingan</h4><p><strong>Perbandingan Senilai:</strong> a/b = c/d => a x d = b x c</p><p><strong>Perbandingan Berbalik Nilai (Pekerja & Waktu):</strong></p><p style=\"background:#f0f0f0;padding:10px;border-radius:5px\"><strong>(Orang 1 x Hari 1) = (Orang 2 x Hari 2)</strong></p><p>Contoh: 6 orang menyelesaikan pekerjaan dalam 12 hari. Berapa hari jika 9 orang?</p><p>Jawaban: 6 x 12 = 9 x hari => 72/9 = 8 hari.</p>', 'rumus', 'menengah', '2026-05-31 20:12:22'),
(3, 1, 'Tips Menjawab TKP', 'Pelayanan Publik', 'tkp', '<h4>Strategi TKP</h4><ul><li><strong>Prioritaskan integritas:</strong> Jujur, akui kesalahan, bertanggung jawab.</li><li><strong>Pelayanan terbaik:</strong> Pilih opsi yang paling membantu masyarakat.</li><li><strong>Hindari:</strong> Opsi pasif, egois, menyalahkan orang lain, atau menunda penyelesaian.</li><li><strong>Skoring:</strong> 5 = terbaik, 1 = terburuk. Semua opsi memiliki nilai.</li></ul>', 'artikel', 'dasar', '2026-05-31 20:12:22'),
(4, 1, 'Materi Numerik - Aritmatika - TIU', 'Numerik - Aritmatika', 'tiu', '<h2>Pengantar Numerik - Aritmatika</h2>\n<p>Materi ini membahas topik <strong>Numerik - Aritmatika</strong> dalam ujian CPNS.</p>\n<h3>Poin-poin Penting:</h3>\n<ul>\n<li>Tidak perlu buru-buru menyelesaikannya karena pekerjaan tersebut bukan merupakan tugas pokoknya</li>\n<li>Pilihan A juga bukan pilihan yang tepat, mengingat membentuk panitia tidak membutuhkan kerja keras</li>\n<li>pilihan BE bukan tindakan yang tepat karena kata âmenunjukâ tidak sesuai dengan nilai komunikatif dan koordinatif seorang pemimpin</li>\n<li>Segera berusaha memulai dan menyelesaikan sebisanya saja yang penting selesai</li>\n<li>tdk maksimal dalam bekerja\n\n\nC</li>\n<li>(menganggap rendah pekerjaan, tidak profesional)\n\n\nD</li>\n<li>Segera berusaha memulai untuk menyelesaikan tugas itu dan berusaha menyelesaikannya sesempurna mungkin</li>\n<li>Mempertanyakan dan menegosiasi manajernya karena merasa takut hasilnya tidak maksimal</li>\n</ul>\n<h3>Ringkasan Numerik - Aritmatika</h3>\n<p>Pelajari dengan teliti materi ini agar dapat menjawab soal-soal TIU dengan baik.</p>\n', 'artikel', 'dasar', '2026-05-31 20:32:52'),
(5, 1, 'Materi UUD 1945 Sistem Pemerintahan', 'UUD 1945 - Sistem Pemerintahan', 'twk', '<h2>Sistem Pemerintahan Indonesia</h2><p>Indonesia menganut sistem pemerintahan presidensial. Kekuasaan negara terbagi: legislatif (DPR/DPD), eksekutif (Presiden), yudikatif (Mahkamah Agung).</p><ul><li>Pasal 1: Bentuk & Kedaulatan</li><li>Pasal 4: Presiden</li><li>Pasal 24: Kekuasaan Kehakiman</li></ul>', 'artikel', 'dasar', '2026-05-31 20:35:55'),
(6, 1, 'Materi Pancasila Implementasi', 'Pancasila - Implementasi', 'twk', '<h2>Implementasi Pancasila</h2><p>Setiap sila Pancasila memiliki implementasi konkret dalam kehidupan bermasyarakat, berbangsa, dan bernegara.</p><ul><li>Sila 1: Toleransi beragama</li><li>Sila 2: Penghargaan HAM</li><li>Sila 3: Rela berkorban untuk bangsa</li><li>Sila 4: Musyawarah mufakat</li><li>Sila 5: Keadilan sosial</li></ul>', 'artikel', 'dasar', '2026-05-31 20:35:55'),
(7, 1, 'Materi Sejarah Kemerdekaan', 'Sejarah - Proklamasi', 'twk', '<h2>Sejarah Kemerdekaan Indonesia</h2><p>Indonesia memproklamasikan kemerdekaan pada 17 Agustus 1945. Peristiwa penting: Sumpah Pemuda 1928, BPUPKI/PPKI 1945.</p>', 'artikel', 'dasar', '2026-05-31 20:35:55'),
(8, 1, 'Materi Numerik CPNS', 'Numerik - Persentase', 'tiu', '<h2>Trik Numerik CPNS</h2><p><strong>Persentase:</strong> Harga Asli = Harga Jual / (1 - Diskon)<br><strong>Pecahan:</strong> Total = Bagian / Fraksi<br><strong>Perbandingan:</strong> Orang1×Hari1 = Orang2×Hari2</p>', 'artikel', 'dasar', '2026-05-31 20:35:55'),
(9, 1, 'Materi Logika CPNS', 'Logika - Pola Angka', 'tiu', '<h2>Trik Logika CPNS</h2><p><strong>Fibonacci:</strong> n = (n-1) + (n-2)<br><strong>Deret kuadrat:</strong> n² ± k<br><strong>Silogisme:</strong> Semua A=B, C=A → C=B</p>', 'artikel', 'dasar', '2026-05-31 20:35:55'),
(10, 1, 'Materi TKP Profesionalisme', 'Profesionalisme', 'tkp', '<h2>Profesionalisme PNS</h2><p>Ciri profesionalisme: komitmen, integritas, pelayanan prima, adaptasi, dan kerja sama tim. Selalu pilih jawaban yang solutif dan profesional.</p>', 'artikel', 'dasar', '2026-05-31 20:35:55'),
(11, 1, 'Materi Hubungan Kerja - TKP', 'Hubungan Kerja', 'tkp', '<h2>Materi Hubungan Kerja</h2>\n<p>Materi pembahasan untuk topik <strong>Hubungan Kerja</strong> dalam ujian CPNS.</p>\n<h3>Poin-poin Penting dari Pembahasan Soal:</h3>\n<ul>\n<li>Memahami perbedaan karakter wajar, namun tetap memberitahu secara sopan untuk menghindari konflik.</li>\n</ul>\n<h3>Tips & Trik:</h3>\n<ul>\n<li>Tips: Pilih jawaban yang menunjukkan empati tapi tetap korektif. Hindari balas dendam atau pasif menerima.</li>\n</ul>\n<p><em>Pelajari materi ini dengan baik untuk mempersiapkan ujian TKP.</em></p>\n', 'artikel', 'dasar', '2026-05-31 20:36:52'),
(12, 1, 'Materi Integritas - TKP', 'Integritas', 'tkp', '<h2>Materi Integritas</h2>\n<p>Materi pembahasan untuk topik <strong>Integritas</strong> dalam ujian CPNS.</p>\n<h3>Poin-poin Penting dari Pembahasan Soal:</h3>\n<ul>\n<li>Integritas: menolak gratifikasi dalam bentuk apapun. Melaporkan ke atasan atau penyelenggara negara.</li>\n</ul>\n<h3>Tips & Trik:</h3>\n<ul>\n<li>Integritas = kejujuran, menolak suap/gratifikasi, melaporkan pelanggaran. Pilih yang paling tegas menolak dan melaporkan.</li>\n</ul>\n<p><em>Pelajari materi ini dengan baik untuk mempersiapkan ujian TKP.</em></p>\n', 'artikel', 'dasar', '2026-05-31 20:36:52'),
(13, 1, 'Materi Integritas - Konflik Kepentingan - TKP', 'Integritas - Konflik Kepentingan', 'tkp', '<h2>Materi Integritas - Konflik Kepentingan</h2>\n<p>Materi pembahasan untuk topik <strong>Integritas - Konflik Kepentingan</strong> dalam ujian CPNS.</p>\n<h3>Poin-poin Penting dari Pembahasan Soal:</h3>\n<ul>\n<li>Integritas mengharuskan kita melaporkan pelanggaran aturan, meskipun dilakukan oleh atasan. Melaporkan ke penyelenggara negara/inspektorat adalah langkah tepat.</li>\n</ul>\n<h3>Tips & Trik:</h3>\n<ul>\n<li>Integritas: laporkan pelanggaran, jangan tutupi meski dilakukan atasan. Pilih yang tegas dan prosedural.</li>\n</ul>\n<p><em>Pelajari materi ini dengan baik untuk mempersiapkan ujian TKP.</em></p>\n', 'artikel', 'dasar', '2026-05-31 20:36:52'),
(14, 1, 'Materi Komitmen - TKP', 'Komitmen', 'tkp', '<h2>Materi Komitmen</h2>\n<p>Materi pembahasan untuk topik <strong>Komitmen</strong> dalam ujian CPNS.</p>\n<h3>Poin-poin Penting dari Pembahasan Soal:</h3>\n<ul>\n<li>Komitmen: menjaga produktivitas tim. Tegur secara pribadi dan profesional, berikan solusi.</li>\n</ul>\n<h3>Tips & Trik:</h3>\n<ul>\n<li>Komitmen: pilih pendekatan persuasif dan profesional sebelum melapor. Tegur pribadi â†’ solusi â†’ lapor jika tidak berubah.</li>\n</ul>\n<p><em>Pelajari materi ini dengan baik untuk mempersiapkan ujian TKP.</em></p>\n', 'artikel', 'dasar', '2026-05-31 20:36:52'),
(15, 1, 'Materi Komitmen - Deadline - TKP', 'Komitmen - Deadline', 'tkp', '<h2>Materi Komitmen - Deadline</h2>\n<p>Materi pembahasan untuk topik <strong>Komitmen - Deadline</strong> dalam ujian CPNS.</p>\n<h3>Poin-poin Penting dari Pembahasan Soal:</h3>\n<ul>\n<li>Komitmen organisasi: komunikasikan kendala ke atasan, usahakan selesaikan dengan data tersedia, atau minta perpanjangan dengan alasan yang jelas.</li>\n</ul>\n<h3>Tips & Trik:</h3>\n<ul>\n<li>Komitmen: komunikasi + usaha maksimal. Jangan menyerah tanpa mencoba.</li>\n</ul>\n<p><em>Pelajari materi ini dengan baik untuk mempersiapkan ujian TKP.</em></p>\n', 'artikel', 'dasar', '2026-05-31 20:36:52'),
(16, 1, 'Materi Logika - Analitis - TIU', 'Logika - Analitis', 'tiu', '<h2>Materi Logika - Analitis</h2>\n<p>Materi pembahasan untuk topik <strong>Logika - Analitis</strong> dalam ujian CPNS.</p>\n<h3>Poin-poin Penting dari Pembahasan Soal:</h3>\n<ul>\n<li>Urutan: Andi &gt; Budi &gt; Cici &gt; Dedi. Andi lebih tinggi dari Dedi.</li>\n</ul>\n<h3>Tips & Trik:</h3>\n<ul>\n<li>Buat diagram urutan. Panah ke bawah = lebih pendek. Susun dari yang tertinggi.</li>\n</ul>\n<p><em>Pelajari materi ini dengan baik untuk mempersiapkan ujian TIU.</em></p>\n', 'artikel', 'dasar', '2026-05-31 20:36:52'),
(17, 1, 'Materi Logika - Silogisme - TIU', 'Logika - Silogisme', 'tiu', '<h2>Materi Logika - Silogisme</h2>\n<p>Materi pembahasan untuk topik <strong>Logika - Silogisme</strong> dalam ujian CPNS.</p>\n<h3>Poin-poin Penting dari Pembahasan Soal:</h3>\n<ul>\n<li>Jika semua karyawan berdasi DAN berjas, maka sebagian karyawan berdasi dan berjas. Tidak bisa simpulkan semua berdasi saja karena semua juga berjas.</li>\n<li>Silogisme: Semua A adalah B. C adalah A. Maka C adalah B. Budi wajib pelatihan.</li>\n</ul>\n<h3>Tips & Trik:</h3>\n<ul>\n<li>Silogisme sederhana: perhatikan subjek dan predikat. Jika premis valid, kesimpulan mengikuti pola logis.</li>\n<li>Tips: Perhatikan kata \"semua\" pada kedua premis. Simpulan yang valid harus mencakup kedua sifat.</li>\n</ul>\n<p><em>Pelajari materi ini dengan baik untuk mempersiapkan ujian TIU.</em></p>\n', 'artikel', 'dasar', '2026-05-31 20:36:52'),
(18, 1, 'Materi NKRI - TWK', 'NKRI', 'twk', '<h2>Materi NKRI</h2>\n<p>Materi pembahasan untuk topik <strong>NKRI</strong> dalam ujian CPNS.</p>\n<h3>Poin-poin Penting dari Pembahasan Soal:</h3>\n<ul>\n<li>Merah melambangkan keberanian (berani mengorbankan jiwa dan raga). Putih melambangkan kesucian (kesucian hati dan niat).</li>\n</ul>\n<h3>Tips & Trik:</h3>\n<ul>\n<li>Tips: Merah = Keberanian, Putih = Kesucian. Ingat dengan mnemonik: \"Merah Berani, Putih Suci\".</li>\n</ul>\n<p><em>Pelajari materi ini dengan baik untuk mempersiapkan ujian TWK.</em></p>\n', 'artikel', 'dasar', '2026-05-31 20:36:52'),
(19, 1, 'Materi NKRI - Bela Negara - TWK', 'NKRI - Bela Negara', 'twk', '<h2>Materi NKRI - Bela Negara</h2>\n<p>Materi pembahasan untuk topik <strong>NKRI - Bela Negara</strong> dalam ujian CPNS.</p>\n<h3>Poin-poin Penting dari Pembahasan Soal:</h3>\n<ul>\n<li>Pasal 27 ayat (3): Pembelaan negara adalah hak dan kewajiban setiap warga negara. Cara: jabatan pemerintahan, tentara, atau swakelola.</li>\n</ul>\n<h3>Tips & Trik:</h3>\n<ul>\n<li>Pasal 27 ayat (3) = hak dan kewajiban. Cara: jabatan pemerintahan, tentara, atau swakelola.</li>\n</ul>\n<p><em>Pelajari materi ini dengan baik untuk mempersiapkan ujian TWK.</em></p>\n', 'artikel', 'dasar', '2026-05-31 20:36:52'),
(20, 1, 'Materi NKRI - Bhinneka Tunggal Ika - TWK', 'NKRI - Bhinneka Tunggal Ika', 'twk', '<h2>Materi NKRI - Bhinneka Tunggal Ika</h2>\n<p>Materi pembahasan untuk topik <strong>NKRI - Bhinneka Tunggal Ika</strong> dalam ujian CPNS.</p>\n<h3>Poin-poin Penting dari Pembahasan Soal:</h3>\n<ul>\n<li>Bhinneka Tunggal Ika berasal dari kitab Sutasoma karangan Mpu Tantular (abad XIV). Artinya: Berbeda-beda tetapi tetap satu jua.</li>\n</ul>\n<h3>Tips & Trik:</h3>\n<ul>\n<li>Hafal: Mpu Tantular, kitab Sutasoma, abad XIV. Jangan tertukar dengan Negarakertagama (Mpu Prapanca).</li>\n</ul>\n<p><em>Pelajari materi ini dengan baik untuk mempersiapkan ujian TWK.</em></p>\n', 'artikel', 'dasar', '2026-05-31 20:36:52'),
(21, 1, 'Materi Numerik - Deret - TIU', 'Numerik - Deret', 'tiu', '<h2>Materi Numerik - Deret</h2>\n<p>Materi pembahasan untuk topik <strong>Numerik - Deret</strong> dalam ujian CPNS.</p>\n<h3>Poin-poin Penting dari Pembahasan Soal:</h3>\n<ul>\n<li>Pola: +4, +6, +8, +10, +12, +14. Jadi 29 + 12 = 41, dan 41 + 14 = 55.</li>\n<li>Pola: nÂ² + 1. 1Â²+1=2, 2Â²+1=5, 3Â²+1=10, 4Â²+1=17, 5Â²+1=26, maka 6Â²+1=37.</li>\n</ul>\n<h3>Tips & Trik:</h3>\n<ul>\n<li>Rumus cepat: perhatikan selisih antar angka. Selisih: 3,5,7,9 â†’ selanjutnya 11 â†’ 26+11=37.</li>\n<li>Tips: Cek selisih antar angka. Jika selisih bertambah konstan (2), berarti pola kuadratik.</li>\n</ul>\n<p><em>Pelajari materi ini dengan baik untuk mempersiapkan ujian TIU.</em></p>\n', 'artikel', 'dasar', '2026-05-31 20:36:52'),
(22, 1, 'Materi Numerik - Kecepatan - TIU', 'Numerik - Kecepatan', 'tiu', '<h2>Materi Numerik - Kecepatan</h2>\n<p>Materi pembahasan untuk topik <strong>Numerik - Kecepatan</strong> dalam ujian CPNS.</p>\n<h3>Poin-poin Penting dari Pembahasan Soal:</h3>\n<ul>\n<li>Kecepatan = Jarak / Waktu = 240 km / 4 jam = 60 km/jam.</li>\n</ul>\n<h3>Tips & Trik:</h3>\n<ul>\n<li>Rumus dasar: v = s/t.</li>\n</ul>\n<p><em>Pelajari materi ini dengan baik untuk mempersiapkan ujian TIU.</em></p>\n', 'artikel', 'dasar', '2026-05-31 20:36:52'),
(23, 1, 'Materi Numerik - Pecahan - TIU', 'Numerik - Pecahan', 'tiu', '<h2>Materi Numerik - Pecahan</h2>\n<p>Materi pembahasan untuk topik <strong>Numerik - Pecahan</strong> dalam ujian CPNS.</p>\n<h3>Poin-poin Penting dari Pembahasan Soal:</h3>\n<ul>\n<li>3/4 = 180 liter. Maka 1/4 = 60 liter. Kapasitas penuh = 4/4 = 4 × 60 = 240 liter.</li>\n</ul>\n<h3>Tips & Trik:</h3>\n<ul>\n<li>Rumus: Total = Bagian / Fraksi. 180 / (3/4) = 180 × 4/3 = 240.</li>\n</ul>\n<p><em>Pelajari materi ini dengan baik untuk mempersiapkan ujian TIU.</em></p>\n', 'artikel', 'dasar', '2026-05-31 20:36:52'),
(24, 1, 'Materi Numerik - Perbandingan - TIU', 'Numerik - Perbandingan', 'tiu', '<h2>Materi Numerik - Perbandingan</h2>\n<p>Materi pembahasan untuk topik <strong>Numerik - Perbandingan</strong> dalam ujian CPNS.</p>\n<h3>Poin-poin Penting dari Pembahasan Soal:</h3>\n<ul>\n<li>6Ã—10=60 orang-hari. 15 orang butuh: 60/15=4 hari.</li>\n</ul>\n<h3>Tips & Trik:</h3>\n<ul>\n<li>Perbandingan berbalik: Orang bertambah, hari berkurang. 6Ã—10 = 15Ã—x â†’ x=4.</li>\n</ul>\n<p><em>Pelajari materi ini dengan baik untuk mempersiapkan ujian TIU.</em></p>\n', 'artikel', 'dasar', '2026-05-31 20:36:52'),
(25, 1, 'Materi Pancasila - Sila ke-1 - TWK', 'Pancasila - Sila ke-1', 'twk', '<h2>Materi Pancasila - Sila ke-1</h2>\n<p>Materi pembahasan untuk topik <strong>Pancasila - Sila ke-1</strong> dalam ujian CPNS.</p>\n<h3>Poin-poin Penting dari Pembahasan Soal:</h3>\n<ul>\n<li>Pancasila sebagai ideologi terbuka memungkinkan perkembangan norma dengan konsensus, namun tetap melarang ideologi radikal seperti Marxisme-Leninisme dan mempertahankan stabilitas nasional. Penciptaan</li>\n<li>Sila 1: pengamalan keyakinan dalam kehidupan bermasyarakat, berbangsa, bernegara. Contoh: menghormati kebebasan beragama.</li>\n</ul>\n<h3>Tips & Trik:</h3>\n<ul>\n<li>Fokus pada implementasi nilai ketuhanan dalam kehidupan sosial dan politik.</li>\n<li>Tips: Ingat, ideologi terbuka bukan berarti tanpa batas. Konsensus tetap diperlukan untuk setiap perubahan norma.</li>\n</ul>\n<p><em>Pelajari materi ini dengan baik untuk mempersiapkan ujian TWK.</em></p>\n', 'artikel', 'dasar', '2026-05-31 20:36:52'),
(26, 1, 'Materi Pancasila - Sila ke-2 - TWK', 'Pancasila - Sila ke-2', 'twk', '<h2>Materi Pancasila - Sila ke-2</h2>\n<p>Materi pembahasan untuk topik <strong>Pancasila - Sila ke-2</strong> dalam ujian CPNS.</p>\n<h3>Poin-poin Penting dari Pembahasan Soal:</h3>\n<ul>\n<li>Sila ke-2: Kemanusiaan yang Adil dan Beradab. Contoh: menghargai HAM, tidak diskriminasi.</li>\n</ul>\n<h3>Tips & Trik:</h3>\n<ul>\n<li>Ingat: Sila 2 = Kemanusiaan. Jika soal tentang HAM â†’ jawaban Sila 2.</li>\n</ul>\n<p><em>Pelajari materi ini dengan baik untuk mempersiapkan ujian TWK.</em></p>\n', 'artikel', 'dasar', '2026-05-31 20:36:52'),
(27, 1, 'Materi Pancasila - Sila ke-3 - TWK', 'Pancasila - Sila ke-3', 'twk', '<h2>Materi Pancasila - Sila ke-3</h2>\n<p>Materi pembahasan untuk topik <strong>Pancasila - Sila ke-3</strong> dalam ujian CPNS.</p>\n<h3>Poin-poin Penting dari Pembahasan Soal:</h3>\n<ul>\n<li>Sila ke-2 Pancasila: Kemanusiaan yang Adil dan Beradab. Mengakui persamaan derajat dan martabat setiap manusia secara universal.</li>\n<li>Sila ke-3: persatuan dan kesatuan bangsa. Warga harus rela berkorban untuk kepentingan bangsa.</li>\n</ul>\n<h3>Tips & Trik:</h3>\n<ul>\n<li>Kata kunci: persatuan, kesatuan, NKRI, korban untuk bangsa.</li>\n<li>Tips: Sila ke-2 berhubungan dengan kemanusiaan universal. Jika soal tentang hubungan antar-manusia global, jawabannya Sila 2.</li>\n</ul>\n<p><em>Pelajari materi ini dengan baik untuk mempersiapkan ujian TWK.</em></p>\n', 'artikel', 'dasar', '2026-05-31 20:36:52'),
(28, 1, 'Materi Pancasila - Sila ke-4 - TWK', 'Pancasila - Sila ke-4', 'twk', '<h2>Materi Pancasila - Sila ke-4</h2>\n<p>Materi pembahasan untuk topik <strong>Pancasila - Sila ke-4</strong> dalam ujian CPNS.</p>\n<h3>Poin-poin Penting dari Pembahasan Soal:</h3>\n<ul>\n<li>Sila ke-4: kekuasaan di tangan rakyat, dijalankan melalui permusyawaratan. Dasar demokrasi Pancasila.</li>\n</ul>\n<h3>Tips & Trik:</h3>\n<ul>\n<li>Demokrasi Pancasila â‰  liberal. Ciri: musyawarah, kekeluargaan, kepentingan bersama.</li>\n</ul>\n<p><em>Pelajari materi ini dengan baik untuk mempersiapkan ujian TWK.</em></p>\n', 'artikel', 'dasar', '2026-05-31 20:36:52'),
(29, 1, 'Materi Pancasila - Sila ke-5 - TWK', 'Pancasila - Sila ke-5', 'twk', '<h2>Materi Pancasila - Sila ke-5</h2>\n<p>Materi pembahasan untuk topik <strong>Pancasila - Sila ke-5</strong> dalam ujian CPNS.</p>\n<h3>Poin-poin Penting dari Pembahasan Soal:</h3>\n<ul>\n<li>Sila ke-5: distribusi keadilan merata di bidang sosial, ekonomi, hukum.</li>\n</ul>\n<h3>Tips & Trik:</h3>\n<ul>\n<li>Kata kunci: keadilan, merata, seluruh rakyat, tidak memihak, pembangunan merata.</li>\n</ul>\n<p><em>Pelajari materi ini dengan baik untuk mempersiapkan ujian TWK.</em></p>\n', 'artikel', 'dasar', '2026-05-31 20:36:52'),
(30, 1, 'Materi Pelayanan Publik - Prioritas - TKP', 'Pelayanan Publik - Prioritas', 'tkp', '<h2>Materi Pelayanan Publik - Prioritas</h2>\n<p>Materi pembahasan untuk topik <strong>Pelayanan Publik - Prioritas</strong> dalam ujian CPNS.</p>\n<h3>Poin-poin Penting dari Pembahasan Soal:</h3>\n<ul>\n<li>Pelayanan publik harus mengutamakan kelompok rentan. Bantu lansia dengan menyediakan tempat duduk atau prioritas pelayanan sesuai ketentuan.</li>\n</ul>\n<h3>Tips & Trik:</h3>\n<ul>\n<li>Pelayanan: utamakan kelompok rentan (lansia, ibu hamil, difabel).</li>\n</ul>\n<p><em>Pelajari materi ini dengan baik untuk mempersiapkan ujian TKP.</em></p>\n', 'artikel', 'dasar', '2026-05-31 20:36:52'),
(31, 1, 'Materi Profesionalisme - Kritik - TKP', 'Profesionalisme - Kritik', 'tkp', '<h2>Materi Profesionalisme - Kritik</h2>\n<p>Materi pembahasan untuk topik <strong>Profesionalisme - Kritik</strong> dalam ujian CPNS.</p>\n<h3>Poin-poin Penting dari Pembahasan Soal:</h3>\n<ul>\n<li>Profesionalisme: dengarkan kritik, evaluasi diri, minta saran perbaikan. Jangan defensif atau menyalahkan orang lain di depan umum.</li>\n</ul>\n<h3>Tips & Trik:</h3>\n<ul>\n<li>Profesional: terima kritik dengan lapang dada, jadikan pembelajaran.</li>\n</ul>\n<p><em>Pelajari materi ini dengan baik untuk mempersiapkan ujian TKP.</em></p>\n', 'artikel', 'dasar', '2026-05-31 20:36:52'),
(32, 1, 'Materi Sejarah - Sumpah Pemuda - TWK', 'Sejarah - Sumpah Pemuda', 'twk', '<h2>Materi Sejarah - Sumpah Pemuda</h2>\n<p>Materi pembahasan untuk topik <strong>Sejarah - Sumpah Pemuda</strong> dalam ujian CPNS.</p>\n<h3>Poin-poin Penting dari Pembahasan Soal:</h3>\n<ul>\n<li>Sumpah Pemuda 28 Oktober 1928 berisi tiga ikrar: Tanah Air, Bangsa, dan Bahasa Indonesia.</li>\n</ul>\n<h3>Tips & Trik:</h3>\n<ul>\n<li>Hafalkan 3 ikrar: Tanah Air, Bangsa, Bahasa.</li>\n</ul>\n<p><em>Pelajari materi ini dengan baik untuk mempersiapkan ujian TWK.</em></p>\n', 'artikel', 'dasar', '2026-05-31 20:36:52'),
(33, 1, 'Materi Sosial Budaya - TKP', 'Sosial Budaya', 'tkp', '<h2>Materi Sosial Budaya</h2>\n<p>Materi pembahasan untuk topik <strong>Sosial Budaya</strong> dalam ujian CPNS.</p>\n<h3>Poin-poin Penting dari Pembahasan Soal:</h3>\n<ul>\n<li>Sosial Budaya: menghargai keberagaman, tidak memaksakan kebiasaan sendiri, memperlakukan setiap orang setara.</li>\n</ul>\n<h3>Tips & Trik:</h3>\n<ul>\n<li>Keberagaman: pilih jawaban yang menunjukkan penghargaan, adaptasi, dan tidak diskriminatif.</li>\n</ul>\n<p><em>Pelajari materi ini dengan baik untuk mempersiapkan ujian TKP.</em></p>\n', 'artikel', 'dasar', '2026-05-31 20:36:52'),
(34, 1, 'Materi Sosial Budaya - Adaptasi - TKP', 'Sosial Budaya - Adaptasi', 'tkp', '<h2>Materi Sosial Budaya - Adaptasi</h2>\n<p>Materi pembahasan untuk topik <strong>Sosial Budaya - Adaptasi</strong> dalam ujian CPNS.</p>\n<h3>Poin-poin Penting dari Pembahasan Soal:</h3>\n<ul>\n<li>Sosial Budaya: menghargai perbedaan, beradaptasi dengan norma setempat, tetap profesional. Ini menunjukkan fleksibilitas dan penghargaan terhadap keberagaman.</li>\n</ul>\n<h3>Tips & Trik:</h3>\n<ul>\n<li>Adaptasi: hormati norma lokal, pelajari budaya baru, tetap profesional.</li>\n</ul>\n<p><em>Pelajari materi ini dengan baik untuk mempersiapkan ujian TKP.</em></p>\n', 'artikel', 'dasar', '2026-05-31 20:36:52'),
(35, 1, 'Materi UUD 1945 - TWK', 'UUD 1945', 'twk', '<h2>Materi UUD 1945</h2>\n<p>Materi pembahasan untuk topik <strong>UUD 1945</strong> dalam ujian CPNS.</p>\n<h3>Poin-poin Penting dari Pembahasan Soal:</h3>\n<ul>\n<li>Pancasila sebagai Dasar Negara (opening UUD 1945). Menjadi fondasi bagi seluruh sistem ketatanegaraan Indonesia.</li>\n</ul>\n<h3>Tips & Trik:</h3>\n<ul>\n<li>Tips: Kalimat \"mengatur penyelenggaraan ketatanegaraan\" selalu merujuk pada kedudukan Pancasila sebagai DASAR NEGARA.</li>\n</ul>\n<p><em>Pelajari materi ini dengan baik untuk mempersiapkan ujian TWK.</em></p>\n', 'artikel', 'dasar', '2026-05-31 20:36:52'),
(36, 1, 'Materi UUD 1945 - HAM - TWK', 'UUD 1945 - HAM', 'twk', '<h2>Materi UUD 1945 - HAM</h2>\n<p>Materi pembahasan untuk topik <strong>UUD 1945 - HAM</strong> dalam ujian CPNS.</p>\n<h3>Poin-poin Penting dari Pembahasan Soal:</h3>\n<ul>\n<li>Pasal 28A UUD 1945: Setiap orang berhak untuk hidup serta berhak mempertahankan hidup dan kehidupannya.</li>\n</ul>\n<h3>Tips & Trik:</h3>\n<ul>\n<li>Pasal 28A = hak hidup. Jangan tertukar dengan Pasal 27 (warga negara) atau 28E (kebebasan).</li>\n</ul>\n<p><em>Pelajari materi ini dengan baik untuk mempersiapkan ujian TWK.</em></p>\n', 'artikel', 'dasar', '2026-05-31 20:36:52'),
(37, 1, 'Materi UUD 1945 - Ketahanan Nasional - TWK', 'UUD 1945 - Ketahanan Nasional', 'twk', '<h2>Materi UUD 1945 - Ketahanan Nasional</h2>\n<p>Materi pembahasan untuk topik <strong>UUD 1945 - Ketahanan Nasional</strong> dalam ujian CPNS.</p>\n<h3>Poin-poin Penting dari Pembahasan Soal:</h3>\n<ul>\n<li>Pasal 30 ayat (1) UUD 1945: Tiap-tiap orang berhak dan wajib ikut serta dalam upaya pembelaan negara. Pasal 30 mengatur pertahanan keamanan negara.</li>\n</ul>\n<h3>Tips & Trik:</h3>\n<ul>\n<li>Pasal 30 = pertahanan negara. Semesta = melibatkan seluruh komponen bangsa.</li>\n</ul>\n<p><em>Pelajari materi ini dengan baik untuk mempersiapkan ujian TWK.</em></p>\n', 'artikel', 'dasar', '2026-05-31 20:36:52'),
(38, 1, 'Materi UUD 1945 - Pasal 1-5 - TWK', 'UUD 1945 - Pasal 1-5', 'twk', '<h2>Materi UUD 1945 - Pasal 1-5</h2>\n<p>Materi pembahasan untuk topik <strong>UUD 1945 - Pasal 1-5</strong> dalam ujian CPNS.</p>\n<h3>Poin-poin Penting dari Pembahasan Soal:</h3>\n<ul>\n<li>Pasal 1 ayat (1): Negara Indonesia ialah Negara Kesatuan, yang berbentuk Republik.</li>\n<li>Pasal 1 ayat (2): Kedaulatan berada di tangan rakyat dan dilaksanakan menurut Undang-Undang Dasar.</li>\n<li>Pasal 4 ayat (1): Presiden Republik Indonesia memegang kekuasaan pemerintahan menurut UUD.</li>\n</ul>\n<h3>Tips & Trik:</h3>\n<ul>\n<li>Hafalkan: Pasal 1 = Bentuk & Kedaulatan. (1) Negara Kesatuan Republik. (2) Kedaulatan rakyat. (3) Negara hukum.</li>\n<li>Pasal 1 ayat (2) = Kedaulatan rakyat. Jangan tertukar dengan ayat (3) tentang negara hukum.</li>\n<li>Pasal 4 = Presiden. (1) Pemerintahan. (2) Kepala negara. Lengkapi dengan Pasal 5.</li>\n</ul>\n<p><em>Pelajari materi ini dengan baik untuk mempersiapkan ujian TWK.</em></p>\n', 'artikel', 'dasar', '2026-05-31 20:36:52'),
(39, 1, 'Materi UUD 1945 - Pasal 27-34 - TWK', 'UUD 1945 - Pasal 27-34', 'twk', '<h2>Materi UUD 1945 - Pasal 27-34</h2>\n<p>Materi pembahasan untuk topik <strong>UUD 1945 - Pasal 27-34</strong> dalam ujian CPNS.</p>\n<h3>Poin-poin Penting dari Pembahasan Soal:</h3>\n<ul>\n<li>Pasal 27 ayat (2): Tiap-tiap warga negara berhak atas pekerjaan dan penghidupan yang layak untuk kemanusiaan.</li>\n</ul>\n<h3>Tips & Trik:</h3>\n<ul>\n<li>Pasal 27 = Warga negara & penduduk. ayat (1) sama di muka hukum. ayat (2) pekerjaan layak. ayat (3) ikut pembelaan negara.</li>\n</ul>\n<p><em>Pelajari materi ini dengan baik untuk mempersiapkan ujian TWK.</em></p>\n', 'artikel', 'dasar', '2026-05-31 20:36:52'),
(40, 1, 'Materi Verbal - Analogi - TIU', 'Verbal - Analogi', 'tiu', '<h2>Materi Verbal - Analogi</h2>\n<p>Materi pembahasan untuk topik <strong>Verbal - Analogi</strong> dalam ujian CPNS.</p>\n<h3>Poin-poin Penting dari Pembahasan Soal:</h3>\n<ul>\n<li>Analogi hubungan tempat bekerja. Guru bekerja di sekolah. Dokter bekerja di rumah sakit.</li>\n</ul>\n<h3>Tips & Trik:</h3>\n<ul>\n<li>Cari pola hubungan: tempat bekerja, alat kerja, fungsi, atau lawan.</li>\n</ul>\n<p><em>Pelajari materi ini dengan baik untuk mempersiapkan ujian TIU.</em></p>\n', 'artikel', 'dasar', '2026-05-31 20:36:52'),
(41, 1, 'Materi Verbal - Antonim - TIU', 'Verbal - Antonim', 'tiu', '<h2>Materi Verbal - Antonim</h2>\n<p>Materi pembahasan untuk topik <strong>Verbal - Antonim</strong> dalam ujian CPNS.</p>\n<h3>Poin-poin Penting dari Pembahasan Soal:</h3>\n<ul>\n<li>Ekstensif = luas, merata, menyeluruh. Antonimnya intensif = mendalam, terpusat.</li>\n</ul>\n<h3>Tips & Trik:</h3>\n<ul>\n<li>Ekstensif vs Intensif sering muncul di soal CPNS. Ekstensif = luas. Intensif = mendalam.</li>\n</ul>\n<p><em>Pelajari materi ini dengan baik untuk mempersiapkan ujian TIU.</em></p>\n', 'artikel', 'dasar', '2026-05-31 20:36:52'),
(42, 1, 'Materi Verbal - Penjelasan - TIU', 'Verbal - Penjelasan', 'tiu', '<h2>Materi Verbal - Penjelasan</h2>\n<p>Materi pembahasan untuk topik <strong>Verbal - Penjelasan</strong> dalam ujian CPNS.</p>\n<h3>Poin-poin Penting dari Pembahasan Soal:</h3>\n<ul>\n<li>Paragraf tersebut menjelaskan pengaruh perkembangan teknologi informasi dan komunikasi terhadap berbagai aspek kehidupan, khususnya pendidikan.</li>\n</ul>\n<h3>Tips & Trik:</h3>\n<ul>\n<li>Cari kalimat utama yang menyatakan topik utama paragraf.</li>\n</ul>\n<p><em>Pelajari materi ini dengan baik untuk mempersiapkan ujian TIU.</em></p>\n', 'artikel', 'dasar', '2026-05-31 20:36:52'),
(43, 1, 'Materi Verbal - Sinonim - TIU', 'Verbal - Sinonim', 'tiu', '<h2>Materi Verbal - Sinonim</h2>\n<p>Materi pembahasan untuk topik <strong>Verbal - Sinonim</strong> dalam ujian CPNS.</p>\n<h3>Poin-poin Penting dari Pembahasan Soal:</h3>\n<ul>\n<li>Konsisten = tetap, tidak berubah-ubah, teguh, konsekuen.</li>\n<li>Mortasitas berasal dari kata \"mortal\" yang berarti kematian. Jadi mortalitas = angka kematian.</li>\n</ul>\n<h3>Tips & Trik:</h3>\n<ul>\n<li>Gunakan konteks kalimat. Konsisten sering dipasangkan dengan sikap, perilaku, atau komitmen.</li>\n<li>Tips: Ingat kata Latin \"mort\" = mati. Mortalitas = mortal = kematian.</li>\n</ul>\n<p><em>Pelajari materi ini dengan baik untuk mempersiapkan ujian TIU.</em></p>\n', 'artikel', 'dasar', '2026-05-31 20:36:52');

-- --------------------------------------------------------

--
-- Struktur dari tabel `opsi_jawaban`
--

CREATE TABLE `opsi_jawaban` (
  `id` int(11) NOT NULL,
  `soal_id` int(11) NOT NULL,
  `label` enum('A','B','C','D','E') NOT NULL,
  `teks_jawaban` text NOT NULL,
  `bobot_nilai` int(2) NOT NULL DEFAULT 0,
  `is_kunci` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `opsi_jawaban`
--

INSERT INTO `opsi_jawaban` (`id`, `soal_id`, `label`, `teks_jawaban`, `bobot_nilai`, `is_kunci`) VALUES
(1, 1, 'A', 'Mencegah berkembangnya paham dan ideologi liberal', 0, 0),
(2, 1, 'B', 'Penciptaan norma baru tidak perlu memiliki konsensus', 5, 1),
(3, 1, 'C', 'Larangan terhadap ideologi Marxisme, Leninisme, dan Komunisme', 0, 0),
(4, 1, 'D', 'Larangan terhadap pandangan ekstrim yang meresahkan masyarakat', 0, 0),
(5, 1, 'E', 'Menekankan pandangan stabilitas nasional yang sehat dan dinamis', 0, 0),
(6, 2, 'A', '1', 0, 0),
(7, 2, 'B', '2', 5, 1),
(8, 2, 'C', '3', 0, 0),
(9, 2, 'D', '4', 0, 0),
(10, 2, 'E', '5', 0, 0),
(11, 3, 'A', 'Pandangan hidup bangsa', 0, 0),
(12, 3, 'B', 'Moral pembangunan bangsa', 0, 0),
(13, 3, 'C', 'Jiwa kepribadian bangsa', 0, 0),
(14, 3, 'D', 'Dasar negara', 5, 1),
(15, 3, 'E', 'Perjanjian luhur bangsa', 0, 0),
(16, 4, 'A', 'Darah para pejuang nasional', 0, 0),
(17, 4, 'B', 'Kegagahan', 0, 0),
(18, 4, 'C', 'Darah para korban yang gugur di medan perang', 0, 0),
(19, 4, 'D', 'Keberanian', 5, 1),
(20, 4, 'E', 'Kesucian', 0, 0),
(21, 5, 'A', 'Angka kematian', 5, 1),
(22, 5, 'B', 'Angka kelahiran', 0, 0),
(23, 5, 'C', 'Sebangsa hewan', 0, 0),
(24, 5, 'D', 'Gerak', 0, 0),
(25, 5, 'E', 'Pukulan', 0, 0),
(26, 6, 'A', '39 dan 69', 0, 0),
(27, 6, 'B', '41 dan 71', 5, 1),
(28, 6, 'C', '35 dan 65', 0, 0),
(29, 6, 'D', '39 dan 65', 0, 0),
(30, 6, 'E', '40 dan 71', 0, 0),
(31, 7, 'A', 'Sebagian karyawan bersepatu', 0, 0),
(32, 7, 'B', 'Sebagian karyawan berdasi dan bersepatu', 0, 0),
(33, 7, 'C', 'Sebagian karyawan berdasi', 0, 0),
(34, 7, 'D', 'Sebagian karyawan berdasi dan berjas', 5, 1),
(35, 7, 'E', 'Semua berdasi dan berjas', 0, 0),
(36, 8, 'A', 'Spontan berbicara kepada atasan menjelaskan bahwa itu sudah rapuh', 2, 0),
(37, 8, 'B', 'Spontan berbicara sendiri \"duh ternyata barangnya sudah rapuh\"', 1, 0),
(38, 8, 'C', 'Langsung berbicara sendiri dengan kata-kata sopan agar atasan memahami', 3, 0),
(39, 8, 'D', 'Langsung berbicara kepada atasan meminta maaf dan menjelaskannya', 2, 0),
(40, 8, 'E', 'Langsung berbicara kepada atasan meminta maaf, menjelaskan, dan menggantinya', 5, 1),
(41, 9, 'A', 'Memahami sifatnya dan balas perlakuan yang sama', 1, 0),
(42, 9, 'B', 'Tidak terlalu memikirkan, fokus pada diri sendiri', 2, 0),
(43, 9, 'C', 'Memahami perbedaan dan memberitahu secara sopan', 5, 1),
(44, 9, 'D', 'Cuek dan hindari interaksi', 1, 0),
(45, 9, 'E', 'Laporkan ke atasan tanpa diskusi pribadi', 3, 0),
(46, 10, 'A', 'Menghormati kebebasan beragama dan kepercayaan', 5, 1),
(47, 10, 'B', 'Menjadikan agama sebagai sumber hukum negara', 0, 0),
(48, 10, 'C', 'Mewajibkan semua warga mengikuti agama mayoritas', 0, 0),
(49, 10, 'D', 'Menghapuskan perbedaan keyakinan dalam masyarakat', 0, 0),
(50, 10, 'E', 'Memisahkan agama dari kehidupan bernegara', 0, 0),
(51, 11, 'A', 'Menghargai hak asasi manusia setiap individu', 5, 1),
(52, 11, 'B', 'Mengutamakan kepentingan bangsa di atas individu', 0, 0),
(53, 11, 'C', 'Membatasi kebebasan untuk menjaga ketertiban', 0, 0),
(54, 11, 'D', 'Mewajibkan pelayanan sosial oleh negara', 0, 0),
(55, 11, 'E', 'Memperlakukan semua orang sama tanpa memandang hukum', 0, 0),
(56, 12, 'A', 'Rela berkorban untuk kepentingan bangsa dan negara', 5, 1),
(57, 12, 'B', 'Membela kepentingan daerah masing-masing', 0, 0),
(58, 12, 'C', 'Mengutamakan hak individual', 0, 0),
(59, 12, 'D', 'Membentuk organisasi berdasarkan suku', 0, 0),
(60, 12, 'E', 'Menolak kerja sama dengan daerah lain', 0, 0),
(61, 13, 'A', 'Kekuasaan berada di tangan rakyat melalui permusyawaratan', 5, 1),
(62, 13, 'B', 'Presiden memegang kekuasaan absolut atas rakyat', 0, 0),
(63, 13, 'C', 'Rakyat hanya berhak memilih tanpa berpendapat', 0, 0),
(64, 13, 'D', 'Permusyawaratan hanya dilakukan oleh elit politik', 0, 0),
(65, 13, 'E', 'Kekuasaan tertinggi ada pada partai politik', 0, 0),
(66, 14, 'A', 'Membangun dan mendistribusikan keadilan secara merata', 5, 1),
(67, 14, 'B', 'Memberikan keistimewaan pada kelompok tertentu', 0, 0),
(68, 14, 'C', 'Memusatkan pembangunan di wilayah strategis saja', 0, 0),
(69, 14, 'D', 'Mengutamakan pemodal besar dalam ekonomi', 0, 0),
(70, 14, 'E', 'Membebaskan pasar tanpa intervensi negara', 0, 0),
(71, 15, 'A', 'Negara Kesatuan yang berbentuk Republik', 5, 1),
(72, 15, 'B', 'Negara Federasi yang berbentuk Republik', 0, 0),
(73, 15, 'C', 'Negara Kesatuan yang berbentuk Kerajaan', 0, 0),
(74, 15, 'D', 'Negara Serikat yang berbentuk Republik', 0, 0),
(75, 15, 'E', 'Negara Uni yang berbentuk Republik', 0, 0),
(76, 16, 'A', 'Pasal 1 ayat (2) UUD 1945', 5, 1),
(77, 16, 'B', 'Pasal 1 ayat (3) UUD 1945', 0, 0),
(78, 16, 'C', 'Pasal 2 ayat (1) UUD 1945', 0, 0),
(79, 16, 'D', 'Pasal 3 UUD 1945', 0, 0),
(80, 16, 'E', 'Pasal 4 UUD 1945', 0, 0),
(81, 17, 'A', 'Pasal 4 ayat (1) UUD 1945', 5, 1),
(82, 17, 'B', 'Pasal 3 UUD 1945', 0, 0),
(83, 17, 'C', 'Pasal 5 ayat (1) UUD 1945', 0, 0),
(84, 17, 'D', 'Pasal 6 UUD 1945', 0, 0),
(85, 17, 'E', 'Pasal 7 UUD 1945', 0, 0),
(86, 18, 'A', 'Pasal 27 ayat (2) UUD 1945', 5, 1),
(87, 18, 'B', 'Pasal 28 ayat (1) UUD 1945', 0, 0),
(88, 18, 'C', 'Pasal 28A UUD 1945', 0, 0),
(89, 18, 'D', 'Pasal 28E ayat (1) UUD 1945', 0, 0),
(90, 18, 'E', 'Pasal 30 UUD 1945', 0, 0),
(91, 19, 'A', 'Sutasoma karya Mpu Tantular', 5, 1),
(92, 19, 'B', 'Negarakertagama karya Mpu Prapanca', 0, 0),
(93, 19, 'C', 'Arjunawiwaha karya Mpu Kanwa', 0, 0),
(94, 19, 'D', 'Bharatayuddha karya Mpu Sedah', 0, 0),
(95, 19, 'E', 'Kakawin Ramayana', 0, 0),
(96, 20, 'A', 'Mengabdi dalam jabatan pemerintahan, TNI, atau swakelola', 5, 1),
(97, 20, 'B', 'Membayar pajak lebih besar dari ketentuan', 0, 0),
(98, 20, 'C', 'Melakukan demonstrasi menentang kebijakan pemerintah', 0, 0),
(99, 20, 'D', 'Menjadi anggota ormas tertentu', 0, 0),
(100, 20, 'E', 'Memilih pemimpin daerah setiap periode', 0, 0),
(101, 21, 'A', 'Tetap', 5, 1),
(102, 21, 'B', 'Berubah-ubah', 0, 0),
(103, 21, 'C', 'Ragu-ragu', 0, 0),
(104, 21, 'D', 'Bimbang', 0, 0),
(105, 21, 'E', 'Fluktuatif', 0, 0),
(106, 22, 'A', 'Intensif', 5, 1),
(107, 22, 'B', 'Ekspansif', 0, 0),
(108, 22, 'C', 'Luas', 0, 0),
(109, 22, 'D', 'Merata', 0, 0),
(110, 22, 'E', 'Menyeluruh', 0, 0),
(111, 23, 'A', 'Rumah Sakit', 5, 1),
(112, 23, 'B', 'Pasien', 0, 0),
(113, 23, 'C', 'Obat', 0, 0),
(114, 23, 'D', 'Periksa', 0, 0),
(115, 23, 'E', 'Stetoskop', 0, 0),
(116, 24, 'A', '37', 5, 1),
(117, 24, 'B', '35', 0, 0),
(118, 24, 'C', '39', 0, 0),
(119, 24, 'D', '41', 0, 0),
(120, 24, 'E', '43', 0, 0),
(121, 25, 'A', '12 hari', 5, 1),
(122, 25, 'B', '14 hari', 0, 0),
(123, 25, 'C', '15 hari', 0, 0),
(124, 25, 'D', '16 hari', 0, 0),
(125, 25, 'E', '18 hari', 0, 0),
(126, 26, 'A', '4 hari', 5, 1),
(127, 26, 'B', '5 hari', 0, 0),
(128, 26, 'C', '6 hari', 0, 0),
(129, 26, 'D', '7 hari', 0, 0),
(130, 26, 'E', '8 hari', 0, 0),
(131, 27, 'A', 'Budi wajib mengikuti pelatihan', 5, 1),
(132, 27, 'B', 'Budi tidak wajib mengikuti pelatihan', 0, 0),
(133, 27, 'C', 'Semua yang mengikuti pelatihan adalah PNS', 0, 0),
(134, 27, 'D', 'Budi bukan PNS', 0, 0),
(135, 27, 'E', 'Tidak dapat disimpulkan', 0, 0),
(136, 28, 'A', 'Andi lebih tinggi dari Dedi', 5, 1),
(137, 28, 'B', 'Dedi lebih tinggi dari Budi', 0, 0),
(138, 28, 'C', 'Cici lebih tinggi dari Andi', 0, 0),
(139, 28, 'D', 'Budi lebih pendek dari Dedi', 0, 0),
(140, 28, 'E', 'Tidak ada kesimpulan yang pasti', 0, 0),
(141, 29, 'A', 'Mendengarkan keluhan dengan tenang dan menjelaskan alasan sambil menawarkan solusi alternatif', 5, 1),
(142, 29, 'B', 'Menjadi marah karena warga tidak menghormati petugas', 1, 0),
(143, 29, 'C', 'Mengabaikan warga dan melayani warga lain', 2, 0),
(144, 29, 'D', 'Menyuruh warga untuk datang lain hari', 3, 0),
(145, 29, 'E', 'Menegaskan bahwa keputusan sudah final', 4, 0),
(146, 30, 'A', 'Menolak hadiah dan melaporkan ke atasan/penyelenggara negara', 5, 1),
(147, 30, 'B', 'Menerima hadiah karena sudah menjadi tradisi', 1, 0),
(148, 30, 'C', 'Menerima tapi tidak memberikan keuntungan khusus', 2, 0),
(149, 30, 'D', 'Menolak secara halus tanpa melaporkan', 4, 0),
(150, 30, 'E', 'Meminta hadiah yang lebih kecil agar tidak mencurigakan', 3, 0),
(151, 31, 'A', 'Menghubungi atasan untuk diskusi penyelesaian tugas dan konfirmasi ke keluarga', 5, 1),
(152, 31, 'B', 'Menolak tugas karena sudah ada janji keluarga', 1, 0),
(153, 31, 'C', 'Meninggalkan tugas tanpa izin untuk hadir acara keluarga', 2, 0),
(154, 31, 'D', 'Menyelesaikan tugas tanpa memberi tahu keluarga', 3, 0),
(155, 31, 'E', 'Meminta rekan menyelesaikan tugas tanpa sepengetahuan atasan', 4, 0),
(156, 32, 'A', 'Mengingatkan secara pribadi dan profesional serta memberikan solusi', 5, 1),
(157, 32, 'B', 'Melaporkan langsung ke atasan tanpa peringatan', 3, 0),
(158, 32, 'C', 'Mengikuti perilakunya karena tidak ada yang peduli', 1, 0),
(159, 32, 'D', 'Menegurnya di depan umum agar malu', 2, 0),
(160, 32, 'E', 'Mengabaikan karena bukan tanggung jawab Anda', 4, 0),
(161, 33, 'A', 'Menghargai perbedaan dan beradaptasi selama tidak melanggar aturan', 5, 1),
(162, 33, 'B', 'Memintanya menyesuaikan dengan kebanyakan rekan', 2, 0),
(163, 33, 'C', 'Mengabaikan perbedaan dan fokus pada pekerjaan', 3, 0),
(164, 33, 'D', 'Melaporkan ke HRD karena mengganggu dinamika tim', 1, 0),
(165, 33, 'E', 'Menyarankan dipindahkan ke divisi lain', 4, 0),
(166, 34, 'A', 'Â  14 menit', 5, 1),
(167, 34, 'B', 'Â  21 menit', 0, 0),
(168, 34, 'C', 'Â  28 menit', 0, 0),
(169, 34, 'D', 'Â  35 menit', 0, 0),
(170, 34, 'E', 'Â  42 menit', 0, 0),
(171, 35, 'A', 'Kanada : Canberra.', 0, 0),
(172, 35, 'B', 'Ekuador : Quito.', 5, 1),
(173, 35, 'C', 'Kamerun: Astana.', 0, 0),
(174, 35, 'D', 'Maroko : Cetinje.', 0, 0),
(175, 35, 'E', 'Nigeria : Wellington.', 0, 0),
(176, 36, 'A', 'Meminta orang lain mengerjakan asalkan bisa selesai tepat waktu.', 0, 0),
(177, 36, 'B', 'Segera berusaha memulai dan menyelesaikan sebisanya saja yang penting selesai.', 0, 0),
(178, 36, 'C', 'Tidak perlu buru-buru menyelesaikannya karena pekerjaan tersebut bukan merupakan tugas pokoknya.', 0, 0),
(179, 36, 'D', 'Segera berusaha memulai untuk menyelesaikan tugas itu dan berusaha menyelesaikannya sesempurna mungkin.', 5, 1),
(180, 36, 'E', 'Mempertanyakan dan menegosiasi manajernya karena merasa takut hasilnya tidak maksimal.', 0, 0),
(181, 37, 'A', 'Bekerja keras membentuk panitia persiapan pentas seni ulang tahun perusahaan', 0, 0),
(182, 37, 'B', 'Menunjuk beberapa anggota untuk tampil pada pentas seni ulang tahun perusahaan', 0, 0),
(183, 37, 'C', 'Mengumpulkan seluruh anggota untuk membahas bersama-sama persiapan pentas seni ulang tahun perusahaan', 5, 1),
(184, 37, 'D', 'Melakukan voting untuk mengambil keputusan persiapan pentas seni ulang tahun perusahaan', 0, 0),
(185, 37, 'E', 'Menunjuk beberapa anggota untuk menampilkan pentas seni yang mengangkat kebudayaan bangsa', 0, 0),
(186, 38, 'A', 'Kebangsaan Indonesia; Internasionalisme atau perikemanusiaan; Mufakat atau demokrasi; Perdamaian abadi', 0, 0),
(187, 38, 'B', 'Peri Kebangsaan, Peri Kemanusiaan, Peri keTuhanan, Peri Kerakyatan, dan Mufakat', 0, 0),
(188, 38, 'C', 'Kebangsaan Indonesia; Internasionalisme atau perikemanusiaan; Mufakat atau demokrasi; Kesejahteraan sosial; Ketuhanan yang berkebudayaan', 5, 1),
(189, 38, 'D', 'Peri Kebangsaan, Peri Kemanusiaan, Peri keTuhanan, Peri Kerakyatan, dan Kesejahteraan Rakyat', 0, 0),
(190, 38, 'E', 'Ketuhanan YME, Peri Kemanusiaan, Kebangsaan, Kerakyatan, Keadilan Sosial', 0, 0),
(191, 39, 'A', 'Mahkamah Agung dan badan peradilan di bawahnya', 5, 1),
(192, 39, 'B', 'Presiden dan Wakil Presiden', 0, 0),
(193, 39, 'C', 'DPR dan DPD', 0, 0),
(194, 39, 'D', 'MPR sebagai lembaga tertinggi negara', 0, 0),
(195, 39, 'E', 'Komisi Yudisial semata', 0, 0),
(196, 40, 'A', 'Pasal 28A UUD 1945', 5, 1),
(197, 40, 'B', 'Pasal 27 ayat (2) UUD 1945', 0, 0),
(198, 40, 'C', 'Pasal 28E UUD 1945', 0, 0),
(199, 40, 'D', 'Pasal 28G UUD 1945', 0, 0),
(200, 40, 'E', 'Pasal 29 UUD 1945', 0, 0),
(201, 41, 'A', 'Pasal 30 UUD 1945', 5, 1),
(202, 41, 'B', 'Pasal 27 ayat (3) UUD 1945', 0, 0),
(203, 41, 'C', 'Pasal 31 UUD 1945', 0, 0),
(204, 41, 'D', 'Pasal 33 UUD 1945', 0, 0),
(205, 41, 'E', 'Pasal 34 UUD 1945', 0, 0),
(206, 42, 'A', 'Ketuhanan Yang Maha Esa', 5, 1),
(207, 42, 'B', 'Kemanusiaan yang Adil dan Beradab', 0, 0),
(208, 42, 'C', 'Persatuan Indonesia', 0, 0),
(209, 42, 'D', 'Kerakyatan yang Dipimpin oleh Hikmat Kebijaksanaan', 0, 0),
(210, 42, 'E', 'Keadilan Sosial bagi Seluruh Rakyat Indonesia', 0, 0),
(211, 43, 'A', 'Tanah Air, Bangsa, dan Bahasa Indonesia', 5, 1),
(212, 43, 'B', 'Merdeka atau Mati', 0, 0),
(213, 43, 'C', 'Persatuan Indonesia dan Islam', 0, 0),
(214, 43, 'D', 'Kemerdekaan Republik Indonesia', 0, 0),
(215, 43, 'E', 'Penghapusan feudalisme', 0, 0),
(216, 44, 'A', '17 Agustus 1945', 5, 1),
(217, 44, 'B', '10 November 1945', 0, 0),
(218, 44, 'C', '1 Juni 1945', 0, 0),
(219, 44, 'D', '18 Agustus 1945', 0, 0),
(220, 44, 'E', '20 Mei 1908', 0, 0),
(221, 45, 'A', 'Rp 300.000', 5, 1),
(222, 45, 'B', 'Rp 288.000', 0, 0),
(223, 45, 'C', 'Rp 320.000', 0, 0),
(224, 45, 'D', 'Rp 250.000', 0, 0),
(225, 45, 'E', 'Rp 280.000', 0, 0),
(226, 46, 'A', '240 liter', 5, 1),
(227, 46, 'B', '200 liter', 0, 0),
(228, 46, 'C', '270 liter', 0, 0),
(229, 46, 'D', '220 liter', 0, 0),
(230, 46, 'E', '210 liter', 0, 0),
(231, 47, 'A', '60 km/jam', 5, 1),
(232, 47, 'B', '48 km/jam', 0, 0),
(233, 47, 'C', '72 km/jam', 0, 0),
(234, 47, 'D', '56 km/jam', 0, 0),
(235, 47, 'E', '64 km/jam', 0, 0),
(236, 48, 'A', '21', 5, 1),
(237, 48, 'B', '20', 0, 0),
(238, 48, 'C', '22', 0, 0),
(239, 48, 'D', '18', 0, 0),
(240, 48, 'E', '19', 0, 0),
(241, 49, 'A', 'Pengaruh perkembangan teknologi terhadap kehidupan manusia', 5, 1),
(242, 49, 'B', 'Sejarah teknologi informasi', 0, 0),
(243, 49, 'C', 'Masalah dalam bidang pendidikan', 0, 0),
(244, 49, 'D', 'Perkembangan teknologi di masa depan', 0, 0),
(245, 49, 'E', 'Manfaat pendidikan bagi manusia', 0, 0),
(246, 50, 'A', 'Melaporkan ke inspektorat atau penyelenggara negara', 5, 1),
(247, 50, 'B', 'Menganggap itu urusan pribadi atasan', 1, 0),
(248, 50, 'C', 'Menyebarkan ke rekan kerja agar semua tahu', 2, 0),
(249, 50, 'D', 'Meminta bagian dari fasilitas tersebut', 3, 0),
(250, 50, 'E', 'Mengabaikan karena takut kehilangan pekerjaan', 4, 0),
(251, 51, 'A', 'Menyediakan kursi dan memberikan prioritas pelayanan sesuai ketentuan', 5, 1),
(252, 51, 'B', 'Mengabaikan karena semua orang sama di muka hukum', 1, 0),
(253, 51, 'C', 'Menyuruh lansia datang lagi besok saat tidak ramai', 2, 0),
(254, 51, 'D', 'Menyarankan lansia meminta bantuan keluarga', 3, 0),
(255, 51, 'E', 'Melanjutkan pelayanan antrean tanpa memperhatikan lansia', 4, 0),
(256, 52, 'A', 'Melaporkan kendala ke atasan dan berusaha menyelesaikan dengan data tersedia', 5, 1),
(257, 52, 'B', 'Menunggu data lengkap tanpa melapor ke atasan', 1, 0),
(258, 52, 'C', 'Mengklaim tugas selesai meski data belum lengkap', 2, 0),
(259, 52, 'D', 'Menolak tugas karena deadline tidak realistis', 3, 0),
(260, 52, 'E', 'Mengalihkan tanggung jawab ke rekan kerja', 4, 0),
(261, 53, 'A', 'Mendengarkan kritik, mencatat, dan meminta saran perbaikan setelah rapat', 5, 1),
(262, 53, 'B', 'Membela diri dan menjelaskan alasannya di depan umum', 1, 0),
(263, 53, 'C', 'Menolak kritik karena merasa sudah bekerja keras', 2, 0),
(264, 53, 'D', 'Menyalahkan rekan kerja yang tidak mendukung', 3, 0),
(265, 53, 'E', 'Meninggalkan rapat karena merasa malu', 4, 0),
(266, 54, 'A', 'Mempelajari dan menghormati budaya setempat sambil tetap profesional', 5, 1),
(267, 54, 'B', 'Menolak tugas karena tidak nyaman dengan budaya tersebut', 1, 0),
(268, 54, 'C', 'Memaksakan budaya asal Anda ke lingkungan baru', 2, 0),
(269, 54, 'D', 'Mengisolasi diri dan tidak berinteraksi dengan masyarakat', 3, 0),
(270, 54, 'E', 'Menganggap budaya daerah tersebut inferior', 4, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `paket_soal`
--

CREATE TABLE `paket_soal` (
  `id` int(11) NOT NULL,
  `paket_ujian_id` int(11) NOT NULL,
  `soal_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `paket_soal`
--

INSERT INTO `paket_soal` (`id`, `paket_ujian_id`, `soal_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(6, 1, 5),
(7, 1, 6),
(8, 1, 7),
(11, 1, 8),
(12, 1, 9),
(5, 1, 10),
(9, 1, 21),
(10, 1, 22),
(13, 1, 29),
(14, 1, 30),
(15, 1, 31);

-- --------------------------------------------------------

--
-- Struktur dari tabel `paket_ujian`
--

CREATE TABLE `paket_ujian` (
  `id` int(11) NOT NULL,
  `kategori_ujian_id` int(11) NOT NULL,
  `nama_paket` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `jumlah_soal_twk` int(5) NOT NULL DEFAULT 0,
  `jumlah_soal_tiu` int(5) NOT NULL DEFAULT 0,
  `jumlah_soal_tkp` int(5) NOT NULL DEFAULT 0,
  `waktu_menit` int(5) NOT NULL DEFAULT 90,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `paket_ujian`
--

INSERT INTO `paket_ujian` (`id`, `kategori_ujian_id`, `nama_paket`, `deskripsi`, `jumlah_soal_twk`, `jumlah_soal_tiu`, `jumlah_soal_tkp`, `waktu_menit`, `status`, `created_at`) VALUES
(1, 1, 'Paket CPNS Simulasi', NULL, 5, 5, 5, 30, 'aktif', '2026-05-31 20:16:48');

-- --------------------------------------------------------

--
-- Struktur dari tabel `rekomendasi_belajar`
--

CREATE TABLE `rekomendasi_belajar` (
  `id` int(11) NOT NULL,
  `hasil_ujian_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `topik` varchar(100) NOT NULL,
  `jenis_tes` enum('twk','tiu','tkp') NOT NULL,
  `skor_persentase` decimal(5,2) DEFAULT 0.00,
  `saran_materi_id` int(11) DEFAULT NULL,
  `tingkat_kesulitan_rekomendasi` enum('mudah','sedang','sulit') DEFAULT 'mudah',
  `status` enum('belum_dikerjakan','selesai') DEFAULT 'belum_dikerjakan',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `rekomendasi_belajar`
--

INSERT INTO `rekomendasi_belajar` (`id`, `hasil_ujian_id`, `user_id`, `topik`, `jenis_tes`, `skor_persentase`, `saran_materi_id`, `tingkat_kesulitan_rekomendasi`, `status`, `created_at`) VALUES
(1, 3, 10, 'Verbal - Analogi', 'tiu', 40.00, NULL, 'sedang', 'belum_dikerjakan', '2026-05-31 20:19:33'),
(2, 4, 11, 'UUD 1945 - Pasal 1-5', 'twk', 60.00, NULL, 'sedang', 'belum_dikerjakan', '2026-05-31 20:19:33'),
(3, 5, 10, 'NKRI', 'twk', 0.00, NULL, 'mudah', 'belum_dikerjakan', '2026-05-31 20:24:05'),
(4, 5, 10, 'Pancasila - Sila ke-1', 'twk', 0.00, NULL, 'mudah', 'belum_dikerjakan', '2026-05-31 20:24:05'),
(5, 5, 10, 'Pancasila - Sila ke-3', 'twk', 0.00, NULL, 'mudah', 'belum_dikerjakan', '2026-05-31 20:24:05'),
(6, 5, 10, 'UUD 1945', 'twk', 0.00, NULL, 'mudah', 'belum_dikerjakan', '2026-05-31 20:24:05'),
(7, 5, 10, 'Logika - Silogisme', 'tiu', 0.00, NULL, 'mudah', 'belum_dikerjakan', '2026-05-31 20:24:05'),
(8, 5, 10, 'Numerik - Deret', 'tiu', 0.00, NULL, 'mudah', 'belum_dikerjakan', '2026-05-31 20:24:05'),
(9, 5, 10, 'Verbal - Antonim', 'tiu', 0.00, NULL, 'mudah', 'belum_dikerjakan', '2026-05-31 20:24:05'),
(10, 5, 10, 'Verbal - Sinonim', 'tiu', 0.00, NULL, 'mudah', 'belum_dikerjakan', '2026-05-31 20:24:05'),
(11, 5, 10, 'Hubungan Kerja', 'tkp', 0.00, NULL, 'mudah', 'belum_dikerjakan', '2026-05-31 20:24:05'),
(12, 5, 10, 'Integritas', 'tkp', 0.00, NULL, 'mudah', 'belum_dikerjakan', '2026-05-31 20:24:05'),
(13, 5, 10, 'Pelayanan Publik', 'tkp', 0.00, 3, 'mudah', 'belum_dikerjakan', '2026-05-31 20:24:05'),
(14, 5, 10, 'Profesionalisme', 'tkp', 0.00, NULL, 'mudah', 'belum_dikerjakan', '2026-05-31 20:24:05'),
(15, 6, 11, 'NKRI', 'twk', 0.00, NULL, 'mudah', 'belum_dikerjakan', '2026-05-31 20:24:34'),
(16, 6, 11, 'Pancasila - Sila ke-1', 'twk', 0.00, NULL, 'mudah', 'belum_dikerjakan', '2026-05-31 20:24:34'),
(17, 6, 11, 'Pancasila - Sila ke-3', 'twk', 0.00, NULL, 'mudah', 'belum_dikerjakan', '2026-05-31 20:24:34'),
(18, 6, 11, 'UUD 1945', 'twk', 0.00, NULL, 'mudah', 'belum_dikerjakan', '2026-05-31 20:24:34'),
(19, 6, 11, 'Logika - Silogisme', 'tiu', 0.00, NULL, 'mudah', 'belum_dikerjakan', '2026-05-31 20:24:34'),
(20, 6, 11, 'Numerik - Deret', 'tiu', 0.00, NULL, 'mudah', 'belum_dikerjakan', '2026-05-31 20:24:34'),
(21, 6, 11, 'Verbal - Antonim', 'tiu', 0.00, NULL, 'mudah', 'belum_dikerjakan', '2026-05-31 20:24:34'),
(22, 6, 11, 'Verbal - Sinonim', 'tiu', 0.00, NULL, 'mudah', 'belum_dikerjakan', '2026-05-31 20:24:34'),
(23, 6, 11, 'Hubungan Kerja', 'tkp', 0.00, NULL, 'mudah', 'belum_dikerjakan', '2026-05-31 20:24:34'),
(24, 6, 11, 'Integritas', 'tkp', 0.00, NULL, 'mudah', 'belum_dikerjakan', '2026-05-31 20:24:34'),
(25, 6, 11, 'Pelayanan Publik', 'tkp', 0.00, 3, 'mudah', 'belum_dikerjakan', '2026-05-31 20:24:34'),
(26, 6, 11, 'Profesionalisme', 'tkp', 0.00, NULL, 'mudah', 'belum_dikerjakan', '2026-05-31 20:24:34');

-- --------------------------------------------------------

--
-- Struktur dari tabel `riwayat_materi`
--

CREATE TABLE `riwayat_materi` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `materi_id` int(11) NOT NULL,
  `progress_persen` int(3) DEFAULT 0,
  `waktu_baca_menit` int(5) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `soal`
--

CREATE TABLE `soal` (
  `id` int(11) NOT NULL,
  `kategori_ujian_id` int(11) DEFAULT NULL,
  `jenis_tes` enum('twk','tiu','tkp') NOT NULL,
  `topik` varchar(100) NOT NULL,
  `pertanyaan` text NOT NULL,
  `gambar_url` varchar(255) DEFAULT NULL,
  `tingkat_kesulitan` enum('sangat_mudah','mudah','sedang','sulit','sangat_sulit') DEFAULT 'sedang',
  `pembahasan` longtext DEFAULT NULL,
  `tips_triks` text DEFAULT NULL,
  `sumber` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `soal`
--

INSERT INTO `soal` (`id`, `kategori_ujian_id`, `jenis_tes`, `topik`, `pertanyaan`, `gambar_url`, `tingkat_kesulitan`, `pembahasan`, `tips_triks`, `sumber`, `created_at`) VALUES
(1, 1, 'twk', 'Pancasila - Sila ke-1', 'Pancasila sebagai ideologi terbuka memiliki batas-batas berikut, kecuali...', NULL, 'sedang', 'Pancasila sebagai ideologi terbuka memungkinkan perkembangan norma dengan konsensus, namun tetap melarang ideologi radikal seperti Marxisme-Leninisme dan mempertahankan stabilitas nasional. Penciptaan norma baru tetap memerlukan konsensus.', 'Tips: Ingat, ideologi terbuka bukan berarti tanpa batas. Konsensus tetap diperlukan untuk setiap perubahan norma.', NULL, '2026-05-31 20:12:22'),
(2, 1, 'twk', 'Pancasila - Sila ke-3', 'Mengembangkan sikap bahwa bangsa Indonesia merupakan bagian dari seluruh umat manusia merupakan perwujudan sila ke...', NULL, 'mudah', 'Sila ke-2 Pancasila: Kemanusiaan yang Adil dan Beradab. Mengakui persamaan derajat dan martabat setiap manusia secara universal.', 'Tips: Sila ke-2 berhubungan dengan kemanusiaan universal. Jika soal tentang hubungan antar-manusia global, jawabannya Sila 2.', NULL, '2026-05-31 20:12:22'),
(3, 1, 'twk', 'UUD 1945', 'Pancasila digunakan sebagai dasar untuk mengatur penyelenggaraan ketatanegaraan negara, hal ini sesuai dengan kedudukan Pancasila sebagai...', NULL, 'mudah', 'Pancasila sebagai Dasar Negara (opening UUD 1945). Menjadi fondasi bagi seluruh sistem ketatanegaraan Indonesia.', 'Tips: Kalimat \"mengatur penyelenggaraan ketatanegaraan\" selalu merujuk pada kedudukan Pancasila sebagai DASAR NEGARA.', NULL, '2026-05-31 20:12:22'),
(4, 1, 'twk', 'NKRI', 'Warna merah dalam bendera Republik Indonesia melambangkan...', NULL, 'sangat_mudah', 'Merah melambangkan keberanian (berani mengorbankan jiwa dan raga). Putih melambangkan kesucian (kesucian hati dan niat).', 'Tips: Merah = Keberanian, Putih = Kesucian. Ingat dengan mnemonik: \"Merah Berani, Putih Suci\".', NULL, '2026-05-31 20:12:22'),
(5, 1, 'tiu', 'Verbal - Sinonim', 'Mortasitas = ...', NULL, 'sedang', 'Mortasitas berasal dari kata \"mortal\" yang berarti kematian. Jadi mortalitas = angka kematian.', 'Tips: Ingat kata Latin \"mort\" = mati. Mortalitas = mortal = kematian.', NULL, '2026-05-31 20:12:22'),
(6, 1, 'tiu', 'Numerik - Deret', '1, 5, 11, 19, 29, ..., 55', NULL, 'sedang', 'Pola: +4, +6, +8, +10, +12, +14. Jadi 29 + 12 = 41, dan 41 + 14 = 55.', 'Tips: Cek selisih antar angka. Jika selisih bertambah konstan (2), berarti pola kuadratik.', NULL, '2026-05-31 20:12:22'),
(7, 1, 'tiu', 'Logika - Silogisme', 'Premis 1: Semua karyawan berdasi. Premis 2: Semua karyawan berjas. Simpulan: ...', NULL, 'mudah', 'Jika semua karyawan berdasi DAN berjas, maka sebagian karyawan berdasi dan berjas. Tidak bisa simpulkan semua berdasi saja karena semua juga berjas.', 'Tips: Perhatikan kata \"semua\" pada kedua premis. Simpulan yang valid harus mencakup kedua sifat.', NULL, '2026-05-31 20:12:22'),
(8, 1, 'tkp', 'Pelayanan Publik', 'Apabila Anda tidak sengaja merusak fasilitas perusahaan dan atasan mengetahuinya, sikap Anda adalah...', NULL, 'sedang', 'Jawaban terbaik menunjukkan integritas, tanggung jawab, dan inisiatif perbaikan langsung.', 'Tips: TKP = pilih jawaban yang PALING PROAKTIF, jujur, dan bertanggung jawab. Langsung minta maaf + jelaskan + ganti = skor tertinggi.', NULL, '2026-05-31 20:12:22'),
(9, 1, 'tkp', 'Hubungan Kerja', 'Rekan kerja Anda egois dan sombong. Sikap Anda...', NULL, 'sedang', 'Memahami perbedaan karakter wajar, namun tetap memberitahu secara sopan untuk menghindari konflik.', 'Tips: Pilih jawaban yang menunjukkan empati tapi tetap korektif. Hindari balas dendam atau pasif menerima.', NULL, '2026-05-31 20:12:22'),
(10, 1, 'twk', 'Pancasila - Sila ke-1', 'Ketuhanan Yang Maha Esa sebagai sila pertama diwujudkan melalui...', NULL, 'mudah', 'Sila 1: pengamalan keyakinan dalam kehidupan bermasyarakat, berbangsa, bernegara. Contoh: menghormati kebebasan beragama.', 'Fokus pada implementasi nilai ketuhanan dalam kehidupan sosial dan politik.', NULL, '2026-05-31 20:12:52'),
(11, 1, 'twk', 'Pancasila - Sila ke-2', 'Berikut yang merupakan perwujudan sila kedua Pancasila adalah...', NULL, 'mudah', 'Sila ke-2: Kemanusiaan yang Adil dan Beradab. Contoh: menghargai HAM, tidak diskriminasi.', 'Ingat: Sila 2 = Kemanusiaan. Jika soal tentang HAM â†’ jawaban Sila 2.', NULL, '2026-05-31 20:12:52'),
(12, 1, 'twk', 'Pancasila - Sila ke-3', 'Persatuan Indonesia menuntut setiap warga negara untuk...', NULL, 'mudah', 'Sila ke-3: persatuan dan kesatuan bangsa. Warga harus rela berkorban untuk kepentingan bangsa.', 'Kata kunci: persatuan, kesatuan, NKRI, korban untuk bangsa.', NULL, '2026-05-31 20:12:52'),
(13, 1, 'twk', 'Pancasila - Sila ke-4', 'Kerakyatan yang dipimpin oleh hikmat kebijaksanaan dalam permusyawaratan/perwakilan berarti...', NULL, 'sedang', 'Sila ke-4: kekuasaan di tangan rakyat, dijalankan melalui permusyawaratan. Dasar demokrasi Pancasila.', 'Demokrasi Pancasila â‰  liberal. Ciri: musyawarah, kekeluargaan, kepentingan bersama.', NULL, '2026-05-31 20:12:52'),
(14, 1, 'twk', 'Pancasila - Sila ke-5', 'Keadilan sosial bagi seluruh rakyat Indonesia diwujudkan dengan cara...', NULL, 'mudah', 'Sila ke-5: distribusi keadilan merata di bidang sosial, ekonomi, hukum.', 'Kata kunci: keadilan, merata, seluruh rakyat, tidak memihak, pembangunan merata.', NULL, '2026-05-31 20:12:52'),
(15, 1, 'twk', 'UUD 1945 - Pasal 1-5', 'Menurut UUD 1945 Pasal 1 ayat (1), Negara Indonesia adalah...', NULL, 'mudah', 'Pasal 1 ayat (1): Negara Indonesia ialah Negara Kesatuan, yang berbentuk Republik.', 'Hafalkan: Pasal 1 = Bentuk & Kedaulatan. (1) Negara Kesatuan Republik. (2) Kedaulatan rakyat. (3) Negara hukum.', NULL, '2026-05-31 20:12:52'),
(16, 1, 'twk', 'UUD 1945 - Pasal 1-5', 'Kedaulatan berada di tangan rakyat dan dilaksanakan menurut UUD merupakan bunyi dari...', NULL, 'sangat_mudah', 'Pasal 1 ayat (2): Kedaulatan berada di tangan rakyat dan dilaksanakan menurut Undang-Undang Dasar.', 'Pasal 1 ayat (2) = Kedaulatan rakyat. Jangan tertukar dengan ayat (3) tentang negara hukum.', NULL, '2026-05-31 20:12:52'),
(17, 1, 'twk', 'UUD 1945 - Pasal 1-5', 'Presiden memegang kekuasaan pemerintahan menurut UUD diatur dalam...', NULL, 'mudah', 'Pasal 4 ayat (1): Presiden Republik Indonesia memegang kekuasaan pemerintahan menurut UUD.', 'Pasal 4 = Presiden. (1) Pemerintahan. (2) Kepala negara. Lengkapi dengan Pasal 5.', NULL, '2026-05-31 20:12:52'),
(18, 1, 'twk', 'UUD 1945 - Pasal 27-34', 'Setiap warga negara berhak atas pekerjaan dan penghidupan yang layak untuk kemanusiaan diatur dalam...', NULL, 'sedang', 'Pasal 27 ayat (2): Tiap-tiap warga negara berhak atas pekerjaan dan penghidupan yang layak untuk kemanusiaan.', 'Pasal 27 = Warga negara & penduduk. ayat (1) sama di muka hukum. ayat (2) pekerjaan layak. ayat (3) ikut pembelaan negara.', NULL, '2026-05-31 20:12:52'),
(19, 1, 'twk', 'NKRI - Bhinneka Tunggal Ika', 'Semboyan Bhinneka Tunggal Ika berasal dari kitab...', NULL, 'mudah', 'Bhinneka Tunggal Ika berasal dari kitab Sutasoma karangan Mpu Tantular (abad XIV). Artinya: Berbeda-beda tetapi tetap satu jua.', 'Hafal: Mpu Tantular, kitab Sutasoma, abad XIV. Jangan tertukar dengan Negarakertagama (Mpu Prapanca).', NULL, '2026-05-31 20:12:52'),
(20, 1, 'twk', 'NKRI - Bela Negara', 'Pembelaan negara dilakukan dengan cara...', NULL, 'mudah', 'Pasal 27 ayat (3): Pembelaan negara adalah hak dan kewajiban setiap warga negara. Cara: jabatan pemerintahan, tentara, atau swakelola.', 'Pasal 27 ayat (3) = hak dan kewajiban. Cara: jabatan pemerintahan, tentara, atau swakelola.', NULL, '2026-05-31 20:12:52'),
(21, 1, 'tiu', 'Verbal - Sinonim', 'Sinonim dari kata KONSISTEN adalah...', NULL, 'mudah', 'Konsisten = tetap, tidak berubah-ubah, teguh, konsekuen.', 'Gunakan konteks kalimat. Konsisten sering dipasangkan dengan sikap, perilaku, atau komitmen.', NULL, '2026-05-31 20:12:52'),
(22, 1, 'tiu', 'Verbal - Antonim', 'Antonim dari kata EKSTENSIF adalah...', NULL, 'sedang', 'Ekstensif = luas, merata, menyeluruh. Antonimnya intensif = mendalam, terpusat.', 'Ekstensif vs Intensif sering muncul di soal CPNS. Ekstensif = luas. Intensif = mendalam.', NULL, '2026-05-31 20:12:52'),
(23, 1, 'tiu', 'Verbal - Analogi', 'GURU : SEKOLAH = DOKTER : ...', NULL, 'mudah', 'Analogi hubungan tempat bekerja. Guru bekerja di sekolah. Dokter bekerja di rumah sakit.', 'Cari pola hubungan: tempat bekerja, alat kerja, fungsi, atau lawan.', NULL, '2026-05-31 20:12:52'),
(24, 1, 'tiu', 'Numerik - Deret', 'Deret: 2, 5, 10, 17, 26, ... Berikutnya adalah?', NULL, 'sedang', 'Pola: nÂ² + 1. 1Â²+1=2, 2Â²+1=5, 3Â²+1=10, 4Â²+1=17, 5Â²+1=26, maka 6Â²+1=37.', 'Rumus cepat: perhatikan selisih antar angka. Selisih: 3,5,7,9 â†’ selanjutnya 11 â†’ 26+11=37.', NULL, '2026-05-31 20:12:52'),
(25, 1, 'tiu', 'Numerik - Aritmatika', '12 pekerja dapat menyelesaikan proyek dalam 20 hari. Jika ditambah 8 pekerja, berapa hari selesai?', NULL, 'sedang', 'Orang Ã— Hari = konstan. 12Ã—20=240. Pekerja baru=20. Hari=240/20=12.', 'Rumus cepat: Orang1 Ã— Hari1 = Orang2 Ã— Hari2. 12Ã—20 = 20Ã—x â†’ x=12.', NULL, '2026-05-31 20:12:52'),
(26, 1, 'tiu', 'Numerik - Perbandingan', '6 orang menyelesaikan pekerjaan dalam 10 hari, maka 15 orang menyelesaikan dalam...', NULL, 'mudah', '6Ã—10=60 orang-hari. 15 orang butuh: 60/15=4 hari.', 'Perbandingan berbalik: Orang bertambah, hari berkurang. 6Ã—10 = 15Ã—x â†’ x=4.', NULL, '2026-05-31 20:12:52'),
(27, 1, 'tiu', 'Logika - Silogisme', 'Semua PNS wajib pelatihan. Budi adalah PNS. Kesimpulan: ...', NULL, 'mudah', 'Silogisme: Semua A adalah B. C adalah A. Maka C adalah B. Budi wajib pelatihan.', 'Silogisme sederhana: perhatikan subjek dan predikat. Jika premis valid, kesimpulan mengikuti pola logis.', NULL, '2026-05-31 20:12:52'),
(28, 1, 'tiu', 'Logika - Analitis', 'Andi > Budi > Cici. Dedi < Cici. Kesimpulan: ...', NULL, 'sedang', 'Urutan: Andi > Budi > Cici > Dedi. Andi lebih tinggi dari Dedi.', 'Buat diagram urutan. Panah ke bawah = lebih pendek. Susun dari yang tertinggi.', NULL, '2026-05-31 20:12:52'),
(29, 1, 'tkp', 'Pelayanan Publik', 'Warga datang marah karena permohonan tertolak. Sikap Anda...', NULL, 'sedang', 'Pelayanan publik: tetap tenang, dengarkan keluhan, jelaskan sabar, berikan solusi alternatif.', 'TKP: Pilih jawaban yang menunjukkan sikap profesional, empati, dan solusi. Hindari pasif, defensif, atau arogan.', NULL, '2026-05-31 20:12:52'),
(30, 1, 'tkp', 'Integritas', 'Ditawari hadiah besar oleh vendor proyek. Sikap Anda...', NULL, 'sedang', 'Integritas: menolak gratifikasi dalam bentuk apapun. Melaporkan ke atasan atau penyelenggara negara.', 'Integritas = kejujuran, menolak suap/gratifikasi, melaporkan pelanggaran. Pilih yang paling tegas menolak dan melaporkan.', NULL, '2026-05-31 20:12:52'),
(31, 1, 'tkp', 'Profesionalisme', 'Tugas mendadak Jumat sore harus lembur, padahal ada janji keluarga. Anda...', NULL, 'mudah', 'Profesionalisme: memprioritaskan tugas kedinasan sambil menghargai komitmen pribadi.', 'Profesionalisme â‰  mengorbankan keluarga terus-menerus. Cari win-win: komunikasi dengan atasan + komitmen menyelesaikan.', NULL, '2026-05-31 20:12:52'),
(32, 1, 'tkp', 'Komitmen', 'Rekan kerja sering datang terlambat. Sebagai kolega, Anda...', NULL, 'sedang', 'Komitmen: menjaga produktivitas tim. Tegur secara pribadi dan profesional, berikan solusi.', 'Komitmen: pilih pendekatan persuasif dan profesional sebelum melapor. Tegur pribadi â†’ solusi â†’ lapor jika tidak berubah.', NULL, '2026-05-31 20:12:52'),
(33, 1, 'tkp', 'Sosial Budaya', 'Ada rekan dari suku berbeda dengan kebiasaan berbeda. Sikap Anda...', NULL, 'mudah', 'Sosial Budaya: menghargai keberagaman, tidak memaksakan kebiasaan sendiri, memperlakukan setiap orang setara.', 'Keberagaman: pilih jawaban yang menunjukkan penghargaan, adaptasi, dan tidak diskriminatif.', NULL, '2026-05-31 20:12:52'),
(34, 1, 'tiu', 'Numerik - Aritmatika', 'Sari dan Ratih memiliki suatu pekerjaan. Waktu yang dibutuhkan oleh Sari dalam menghasilkan uang adalah 21 menit, sedangkan Ratih membutuhkan waktu 42 menit. Jika Sari dan Ratih bekerja bersama-sama untuk menghasilkan uang, waktu yang dibutuhkan adalah â¦', NULL, 'sedang', '', 'Perhatikan pola logika pada soal Numerik - Aritmatika', 'Internet - Auto Scraped', '2026-05-31 20:32:03'),
(35, 1, 'tiu', 'Numerik - Aritmatika', 'Afrika Selatan : Pretoria = … : …', NULL, 'sedang', 'Jawaban: BACDE\n\n\njejaring kerja kerja sama dan kolaborasi dengan panitia pemungutan suara\n\n\nHak pilih adalah sesuatu hak yang bersifat personal dan tidak bisa diwakili oleh siapapun.\n\n\nPilihan E, Membiarkan hak suara nenek hangus tentu tidak bersifat solutif dalam menghadapi masalah tersebut.\n\n\nPilihan DC, juga bukan pilihan yang tepat, mengingat hak pilih tidak bisa diwakilkan.\n\n\nPilihan terbaik ada pada pilihan AB.\n\n\nDan tentu saja pilihan B adalah pilihan yang lebih baik daripada A, karena tidak hanya melaporkan kondisi tersebut kepada panitia pemungutan suara namun lebih dari itu juga mempertimbangkan langkah yang diambil untuk mengatasi masalah tersebut.\n\n\nâ', 'Perhatikan pola logika pada soal Numerik - Aritmatika', 'Internet - Auto Scraped', '2026-05-31 20:32:03'),
(36, 1, 'tiu', 'Numerik - Aritmatika', 'Edi baru saja diterima bekerja di salah satu pabrik pengolahan kayu. Sebagai pegawai baru, tentu Edi belum terlalu mengenal jenis-jenis pekerjaan dan cara menyelesaikannya. Suatu malam, tiba-tiba Edi ditugaskan manajernya untuk menyelesaikan tugas seorang rekannya yang tiba-tiba memutuskan untuk keluar dan berhenti bekerja. Edi jelas kaget dan kesulitan dengan penugasan itu, tapi ia tidak punya pilihan lain selain menjalankan perintah atasan. Apalagi manajer tadi memang memberikan tugas tersebut', NULL, 'sedang', 'Jawaban: DEBAC\n\n\nA. Meminta orang lain mengerjakan asalkan bisa selesai tepat waktu. tdk profesional\n\n\nB. Segera berusaha memulai dan menyelesaikan sebisanya saja yang penting selesai. tdk maksimal dalam bekerja\n\n\nC. Tidak perlu buru-buru menyelesaikannya karena pekerjaan tersebut bukan merupakan tugas pokoknya. (menganggap rendah pekerjaan, tidak profesional)\n\n\nD. Segera berusaha memulai untuk menyelesaikan tugas itu dan berusaha menyelesaikannya sesempurna mungkin.\n\n\nE. Mempertanyakan dan menegosiasi manajernya karena merasa takut hasilnya tidak maksimal. (melaksanakan jejaring kerja, tetapi tdk PD)\n\n\nprofesionalisme ~> melaksanakan tupoksi sebaik mungkin secara maksimal\n\n\nprofesionalisme dan semangat berprestasi\n\n\nBerdasarkan ketentuan diatas maka seseorang dituntut untuk melaksanakan tanggung jawab kerja berdasarkan tugas dan fungsinya sekaligus berupaya untuk memberikan yang terbaik dalam setiap tugas yang diberikan.\n\n\nâ', 'Perhatikan pola logika pada soal Numerik - Aritmatika', 'Internet - Auto Scraped', '2026-05-31 20:32:04'),
(37, 1, 'tiu', 'Numerik - Aritmatika', 'Anda ditunjuk sebagai ketua kegiatan. Atasanmu memberikan tugas untuk menyiapkan pentas seni acara ulang tahun perusahaanmu yang ke-21 dikarenakan tiap-tiap kantor cabang harus menampilkan pertunjukannya. Tindakan yang anda lakukan….', NULL, 'sedang', 'Jawaban: CDAEB\n\n\nSebagaimana telah dijelaskan sebelumnya: âDalam kaitan dengan topik kepemimpinan, maka pastikan bahwa komunikasi dan koordinasi menjadi kata kunci dari setiap kebijakan atau tindakan yang akan diambil oleh seorang pemimpinâ.\n\n\npilihan BE bukan tindakan yang tepat karena kata âmenunjukâ tidak sesuai dengan nilai komunikatif dan koordinatif seorang pemimpin.\n\n\nPilihan A juga bukan pilihan yang tepat, mengingat membentuk panitia tidak membutuhkan kerja keras.\n\n\nDisamping itu membentuk panitia saja tidak dapat menyelesaikan masalah karena tentu membutuhkan kebersamaan untuk membahas dan mendiskusikan tentang pelaksanaan pentas seni.\n\n\nPilihan D, melakukan voting bisa menjadi alternatif penyelesaian masalah untuk menuju mufakat. Namun, tentu saja voting bukan tindakan awal yang bisa ditempuh karena \nvoting\n hanya dilakukan ketika ada perbedaan pendapat yang tidak terpecahkan. Jadi, pilihan c adalah pilihan yang terbaik karena menunjukkan komunikasi dan koordinasi de', 'Perhatikan pola logika pada soal Numerik - Aritmatika', 'Internet - Auto Scraped', '2026-05-31 20:32:04'),
(38, 1, 'tiu', 'Numerik - Aritmatika', '“Sesudah tiga hari berturut-turut anggota-anggota Dokuritsu Zyunbi Tyoosakai mengeluarkan pendapat-pendapatnya, maka sekarang saya mendapat kehormatan dari Paduka Tuan Ketua yang mulia untuk mengemukakan pendapat saya. Saya akan menepati permintaan Paduka Tuan Ketua yang mulia. Apakah permintaan Paduka Tuan Ketua yang mulia? Paduka Tuan Ketua yang mulia minta kepada sidang Dokuritsu Zyunbi Tyoosakai untuk mengemukakan dasar Indonesia Merdeka. Dasar inilah nanti akan saya kemukakan di dalam pidat', NULL, 'sedang', '', 'Perhatikan pola logika pada soal Numerik - Aritmatika', 'Internet - Auto Scraped', '2026-05-31 20:32:05'),
(39, 1, 'twk', 'UUD 1945 - Sistem Pemerintahan', 'Dalam sistem pemerintahan Indonesia, kekuasaan kehakiman dilakukan oleh...', NULL, 'sedang', 'Pasal 24 UUD 1945: Kekuasaan kehakiman dilakukan oleh sebuah Mahkamah Agung dan badan peradilan yang berada di bawahnya dalam susunan peradilan umum, peradilan agama, peradilan militer, peradilan tata usaha negara, dan Mahkamah Konstitusi.', 'Hafalkan: Pasal 24 = Kekuasaan kehakiman. Mahkamah Agung + badan peradilan di bawahnya.', 'AI Knowledge', '2026-05-31 20:35:50'),
(40, 1, 'twk', 'UUD 1945 - HAM', 'Setiap orang berhak untuk hidup serta berhak mempertahankan hidup dan kehidupannya diatur dalam...', NULL, 'mudah', 'Pasal 28A UUD 1945: Setiap orang berhak untuk hidup serta berhak mempertahankan hidup dan kehidupannya.', 'Pasal 28A = hak hidup. Jangan tertukar dengan Pasal 27 (warga negara) atau 28E (kebebasan).', 'AI Knowledge', '2026-05-31 20:35:50'),
(41, 1, 'twk', 'UUD 1945 - Ketahanan Nasional', 'Upaya pertahanan dan keamanan negara dilaksanakan melalui sistem pertahanan dan keamanan rakyat semesta yang diatur dalam...', NULL, 'sedang', 'Pasal 30 ayat (1) UUD 1945: Tiap-tiap orang berhak dan wajib ikut serta dalam upaya pembelaan negara. Pasal 30 mengatur pertahanan keamanan negara.', 'Pasal 30 = pertahanan negara. Semesta = melibatkan seluruh komponen bangsa.', 'AI Knowledge', '2026-05-31 20:35:50'),
(42, 1, 'twk', 'Pancasila - Implementasi', 'Toleransi antar umat beragama yang diterapkan di Indonesia merupakan implementasi dari sila...', NULL, 'mudah', 'Sila ke-1 Ketuhanan Yang Maha Esa mengharuskan saling menghormati dan toleransi antar pemeluk agama. Ini adalah implementasi nilai ketuhanan dalam kehidupan bermasyarakat.', 'Sila 1 = Ketuhanan. Implementasi: toleransi, kebebasan beragama, tidak memaksakan keyakinan.', 'AI Knowledge', '2026-05-31 20:35:51'),
(43, 1, 'twk', 'Sejarah - Sumpah Pemuda', 'Sumpah Pemuda tahun 1928 berisi ikrar tentang...', NULL, 'mudah', 'Sumpah Pemuda 28 Oktober 1928 berisi tiga ikrar: Tanah Air, Bangsa, dan Bahasa Indonesia.', 'Hafalkan 3 ikrar: Tanah Air, Bangsa, Bahasa.', 'AI Knowledge', '2026-05-31 20:35:51'),
(44, 1, 'twk', 'Sejarah - Proklamasi', 'Proklamasi Kemerdekaan Indonesia dilaksanakan pada tanggal...', NULL, 'sangat_mudah', 'Proklamasi Kemerdekaan Indonesia dibacakan oleh Soekarno dan Mohammad Hatta pada tanggal 17 Agustus 1945 di Jalan Pegangsaan Timur No. 56 Jakarta.', 'Hafal: 17 Agustus 1945. Soekarno & Hatta. Jalan Pegangsaan Timur 56.', 'AI Knowledge', '2026-05-31 20:35:51'),
(45, 1, 'tiu', 'Numerik - Persentase', 'Sebuah barang dijual dengan harga Rp 240.000 setelah mendapat diskon 20%. Berapa harga asli barang tersebut?', NULL, 'sedang', 'Jika diskon 20%, maka harga jual = 80% dari harga asli. Harga asli = 240.000 / 0.8 = 300.000.', 'Rumus: Harga Asli = Harga Jual / (100% - Diskon%). 240.000 / 0.8 = 300.000.', 'AI Knowledge', '2026-05-31 20:35:52'),
(46, 1, 'tiu', 'Numerik - Pecahan', 'Jika 3/4 dari sebuah tangki berisi 180 liter air, berapa liter kapasitas penuh tangki tersebut?', NULL, 'mudah', '3/4 = 180 liter. Maka 1/4 = 60 liter. Kapasitas penuh = 4/4 = 4 × 60 = 240 liter.', 'Rumus: Total = Bagian / Fraksi. 180 / (3/4) = 180 × 4/3 = 240.', 'AI Knowledge', '2026-05-31 20:35:52'),
(47, 1, 'tiu', 'Numerik - Kecepatan', 'Sebuah mobil menempuh jarak 240 km dalam 4 jam. Berapa kecepatan rata-rata mobil tersebut?', NULL, 'sangat_mudah', 'Kecepatan = Jarak / Waktu = 240 km / 4 jam = 60 km/jam.', 'Rumus dasar: v = s/t.', 'AI Knowledge', '2026-05-31 20:35:52'),
(48, 1, 'tiu', 'Logika - Pola Angka', 'Pola: 1, 1, 2, 3, 5, 8, 13, ... Berikutnya adalah?', NULL, 'sedang', 'Pola Fibonacci: setiap angka adalah jumlah dua angka sebelumnya. 5+8=13, maka 8+13=21.', 'Fibonacci: 1, 1, 2, 3, 5, 8, 13, 21, 34...', 'AI Knowledge', '2026-05-31 20:35:53'),
(49, 1, 'tiu', 'Verbal - Penjelasan', 'Paragraf berikut membahas tentang...\n\n\"Perkembangan teknologi informasi dan komunikasi telah membawa perubahan besar dalam berbagai aspek kehidupan manusia, termasuk dalam bidang pendidikan.\"', NULL, 'mudah', 'Paragraf tersebut menjelaskan pengaruh perkembangan teknologi informasi dan komunikasi terhadap berbagai aspek kehidupan, khususnya pendidikan.', 'Cari kalimat utama yang menyatakan topik utama paragraf.', 'AI Knowledge', '2026-05-31 20:35:53'),
(50, 1, 'tkp', 'Integritas - Konflik Kepentingan', 'Anda mengetahui bahwa atasan Anda menggunakan kendaraan dinas untuk keperluan pribadi secara rutin. Tindakan Anda adalah...', NULL, 'sedang', 'Integritas mengharuskan kita melaporkan pelanggaran aturan, meskipun dilakukan oleh atasan. Melaporkan ke penyelenggara negara/inspektorat adalah langkah tepat.', 'Integritas: laporkan pelanggaran, jangan tutupi meski dilakukan atasan. Pilih yang tegas dan prosedural.', 'AI Knowledge', '2026-05-31 20:35:53'),
(51, 1, 'tkp', 'Pelayanan Publik - Prioritas', 'Di kantor Anda sedang ramai dengan antrean panjang. Seorang lansia terlihat kelelahan berdiri. Tindakan terbaik Anda adalah...', NULL, 'mudah', 'Pelayanan publik harus mengutamakan kelompok rentan. Bantu lansia dengan menyediakan tempat duduk atau prioritas pelayanan sesuai ketentuan.', 'Pelayanan: utamakan kelompok rentan (lansia, ibu hamil, difabel).', 'AI Knowledge', '2026-05-31 20:35:53'),
(52, 1, 'tkp', 'Komitmen - Deadline', 'Anda diberi tugas dengan deadline ketat, namun data yang dibutuhkan belum lengkap. Yang Anda lakukan adalah...', NULL, 'sedang', 'Komitmen organisasi: komunikasikan kendala ke atasan, usahakan selesaikan dengan data tersedia, atau minta perpanjangan dengan alasan yang jelas.', 'Komitmen: komunikasi + usaha maksimal. Jangan menyerah tanpa mencoba.', 'AI Knowledge', '2026-05-31 20:35:54'),
(53, 1, 'tkp', 'Profesionalisme - Kritik', 'Atasan Anda memberikan kritik keras atas hasil kerja Anda dalam rapat umum. Reaksi terbaik Anda adalah...', NULL, 'sedang', 'Profesionalisme: dengarkan kritik, evaluasi diri, minta saran perbaikan. Jangan defensif atau menyalahkan orang lain di depan umum.', 'Profesional: terima kritik dengan lapang dada, jadikan pembelajaran.', 'AI Knowledge', '2026-05-31 20:35:54'),
(54, 1, 'tkp', 'Sosial Budaya - Adaptasi', 'Anda ditugaskan di daerah dengan budaya yang sangat berbeda dari daerah asal Anda. Sikap Anda adalah...', NULL, 'mudah', 'Sosial Budaya: menghargai perbedaan, beradaptasi dengan norma setempat, tetap profesional. Ini menunjukkan fleksibilitas dan penghargaan terhadap keberagaman.', 'Adaptasi: hormati norma lokal, pelajari budaya baru, tetap profesional.', 'AI Knowledge', '2026-05-31 20:35:54');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tanya_admin`
--

CREATE TABLE `tanya_admin` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `pertanyaan` text NOT NULL,
  `jawaban` text DEFAULT NULL,
  `dijawab_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `dijawab_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `role` enum('admin','peserta') NOT NULL DEFAULT 'peserta',
  `target_ujian` enum('cpns','stan','stis','ipdn') DEFAULT 'cpns',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `nama`, `email`, `password`, `no_hp`, `role`, `target_ujian`, `created_at`) VALUES
(1, 'Admin TryOut', 'admin@tryoutku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '081234567890', 'admin', 'cpns', '2026-05-31 20:12:22'),
(2, 'Peserta Demo', 'peserta_demo@tryoutku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '081234567891', 'peserta', 'cpns', '2026-05-31 20:12:22'),
(3, 'Comprehensive Test', 'comp_1780233231994@test.com', '$2y$10$mChVM83lDLV9ekJcpJrNX.dtiW5Tq.ObBu2B4mxXstyS8bPqyTjOK', '081111111111', 'peserta', 'cpns', '2026-05-31 20:13:52'),
(4, 'Peserta Simulasi 1', 'simulasi1780233408@test.com', '$2y$10$NB.KlxpXtG7SKKjBPiVGMOmNkuXBn28/.1bZmH6eilfUCirzAvGNG', '081234567891', 'peserta', 'cpns', '2026-05-31 20:16:48'),
(6, 'Peserta Simulasi 1', 'simulasi1_1780233446_6a1c34e624881@test.com', '$2y$10$ROb/XanC42OiVHesByV50O18miPvSdovAwuPj4xx35L0f87DJjEGu', '081234567891', 'peserta', 'cpns', '2026-05-31 20:17:26'),
(7, 'Peserta Simulasi 2', 'simulasi2_1780233446_6a1c34e637a06@test.com', '$2y$10$gS0JiSPpPGJU2j8QZS7BDu2MVgLHf60Umv3QsJ3eunfCkweAZE.5K', '081234567892', 'peserta', 'cpns', '2026-05-31 20:17:26'),
(8, 'Peserta Simulasi 1', 'simulasi1_1780233521_6a1c3531c8a4b@test.com', '$2y$10$ovN8N5ZCrkzEy6zd4CZWYetgKRQh7ZHw3JYESHSXCLl9R0q/q06CG', '081234567891', 'peserta', 'cpns', '2026-05-31 20:18:41'),
(9, 'Peserta Simulasi 2', 'simulasi2_1780233521_6a1c3531db032@test.com', '$2y$10$SA.zPMi081FidDrG4FI3redVJT77e3/l/QE/3EEXz7EBiZVkPSQFS', '081234567892', 'peserta', 'cpns', '2026-05-31 20:18:41'),
(10, 'Peserta Simulasi 1', 'simulasi1_1780233573_6a1c356544ee7@test.com', '$2y$10$zxcNPDtUo6YMfuxtM7S.Zeke8Zv.YQtXdKOvOQJXY1tmW19Vb.ALm', '081234567891', 'peserta', 'cpns', '2026-05-31 20:19:33'),
(11, 'Peserta Simulasi 2', 'simulasi2_1780233573_6a1c356553ea6@test.com', '$2y$10$zUa32FyWu8gcvhbvL.PP3.XjqwfZVrG3arTyExzQb6d3hBW7I1bDa', '081234567892', 'peserta', 'cpns', '2026-05-31 20:19:33'),
(12, 'Comprehensive Test', 'comp_1780233984659@test.com', '$2y$10$PJg46IpkiBtMmGE/32YdXe6Ns/8Y6rOFXsTjU28l33xZC8awKe5Hi', '081111111111', 'peserta', 'cpns', '2026-05-31 20:26:24');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `catatan_pengajar`
--
ALTER TABLE `catatan_pengajar`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user` (`user_id`);

--
-- Indeks untuk tabel `detail_jawaban`
--
ALTER TABLE `detail_jawaban`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hasil_ujian_id` (`hasil_ujian_id`),
  ADD KEY `soal_id` (`soal_id`);

--
-- Indeks untuk tabel `hasil_ujian`
--
ALTER TABLE `hasil_ujian`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `paket_ujian_id` (`paket_ujian_id`);

--
-- Indeks untuk tabel `kategori_ujian`
--
ALTER TABLE `kategori_ujian`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `materi`
--
ALTER TABLE `materi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kategori_ujian_id` (`kategori_ujian_id`);

--
-- Indeks untuk tabel `opsi_jawaban`
--
ALTER TABLE `opsi_jawaban`
  ADD PRIMARY KEY (`id`),
  ADD KEY `soal_id` (`soal_id`);

--
-- Indeks untuk tabel `paket_soal`
--
ALTER TABLE `paket_soal`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `paket_soal_unique` (`paket_ujian_id`,`soal_id`),
  ADD KEY `soal_id` (`soal_id`);

--
-- Indeks untuk tabel `paket_ujian`
--
ALTER TABLE `paket_ujian`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kategori_ujian_id` (`kategori_ujian_id`);

--
-- Indeks untuk tabel `rekomendasi_belajar`
--
ALTER TABLE `rekomendasi_belajar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `hasil_ujian_id` (`hasil_ujian_id`),
  ADD KEY `rb_materi` (`saran_materi_id`);

--
-- Indeks untuk tabel `riwayat_materi`
--
ALTER TABLE `riwayat_materi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `materi_id` (`materi_id`);

--
-- Indeks untuk tabel `soal`
--
ALTER TABLE `soal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kategori_ujian_id` (`kategori_ujian_id`),
  ADD KEY `jenis_tes` (`jenis_tes`),
  ADD KEY `topik` (`topik`);

--
-- Indeks untuk tabel `tanya_admin`
--
ALTER TABLE `tanya_admin`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `catatan_pengajar`
--
ALTER TABLE `catatan_pengajar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `detail_jawaban`
--
ALTER TABLE `detail_jawaban`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT untuk tabel `hasil_ujian`
--
ALTER TABLE `hasil_ujian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `kategori_ujian`
--
ALTER TABLE `kategori_ujian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `materi`
--
ALTER TABLE `materi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT untuk tabel `opsi_jawaban`
--
ALTER TABLE `opsi_jawaban`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=271;

--
-- AUTO_INCREMENT untuk tabel `paket_soal`
--
ALTER TABLE `paket_soal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `paket_ujian`
--
ALTER TABLE `paket_ujian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `rekomendasi_belajar`
--
ALTER TABLE `rekomendasi_belajar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT untuk tabel `riwayat_materi`
--
ALTER TABLE `riwayat_materi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `soal`
--
ALTER TABLE `soal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT untuk tabel `tanya_admin`
--
ALTER TABLE `tanya_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `detail_jawaban`
--
ALTER TABLE `detail_jawaban`
  ADD CONSTRAINT `dj_hu` FOREIGN KEY (`hasil_ujian_id`) REFERENCES `hasil_ujian` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `dj_soal` FOREIGN KEY (`soal_id`) REFERENCES `soal` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `hasil_ujian`
--
ALTER TABLE `hasil_ujian`
  ADD CONSTRAINT `hu_paket` FOREIGN KEY (`paket_ujian_id`) REFERENCES `paket_ujian` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hu_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `materi`
--
ALTER TABLE `materi`
  ADD CONSTRAINT `materi_kategori` FOREIGN KEY (`kategori_ujian_id`) REFERENCES `kategori_ujian` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `opsi_jawaban`
--
ALTER TABLE `opsi_jawaban`
  ADD CONSTRAINT `opsi_soal` FOREIGN KEY (`soal_id`) REFERENCES `soal` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `paket_soal`
--
ALTER TABLE `paket_soal`
  ADD CONSTRAINT `ps_paket` FOREIGN KEY (`paket_ujian_id`) REFERENCES `paket_ujian` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ps_soal` FOREIGN KEY (`soal_id`) REFERENCES `soal` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `paket_ujian`
--
ALTER TABLE `paket_ujian`
  ADD CONSTRAINT `paket_kategori` FOREIGN KEY (`kategori_ujian_id`) REFERENCES `kategori_ujian` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `rekomendasi_belajar`
--
ALTER TABLE `rekomendasi_belajar`
  ADD CONSTRAINT `rb_hu` FOREIGN KEY (`hasil_ujian_id`) REFERENCES `hasil_ujian` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rb_materi` FOREIGN KEY (`saran_materi_id`) REFERENCES `materi` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `rb_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `riwayat_materi`
--
ALTER TABLE `riwayat_materi`
  ADD CONSTRAINT `rm_materi` FOREIGN KEY (`materi_id`) REFERENCES `materi` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rm_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `soal`
--
ALTER TABLE `soal`
  ADD CONSTRAINT `soal_kategori` FOREIGN KEY (`kategori_ujian_id`) REFERENCES `kategori_ujian` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
