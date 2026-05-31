SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `riwayat_materi`;
DROP TABLE IF EXISTS `rekomendasi_belajar`;
DROP TABLE IF EXISTS `detail_jawaban`;
DROP TABLE IF EXISTS `hasil_ujian`;
DROP TABLE IF EXISTS `paket_soal`;
DROP TABLE IF EXISTS `paket_ujian`;
DROP TABLE IF EXISTS `opsi_jawaban`;
DROP TABLE IF EXISTS `soal`;
DROP TABLE IF EXISTS `materi`;
DROP TABLE IF EXISTS `kategori_ujian`;
DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `role` enum('admin','peserta') NOT NULL DEFAULT 'peserta',
  `target_ujian` enum('cpns','stan','stis','ipdn') DEFAULT 'cpns',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `kategori_ujian` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) NOT NULL,
  `deskripsi` text,
  `passing_grade_twk` int(5) NOT NULL DEFAULT 65,
  `passing_grade_tiu` int(5) NOT NULL DEFAULT 80,
  `passing_grade_tkp` int(5) NOT NULL DEFAULT 166,
  `passing_grade_kumulatif` int(5) NOT NULL DEFAULT 311,
  `waktu_pengerjaan` int(5) NOT NULL DEFAULT 90,
  `jumlah_soal` int(5) NOT NULL DEFAULT 100,
  `jumlah_soal_twk` int(5) NOT NULL DEFAULT 35,
  `jumlah_soal_tiu` int(5) NOT NULL DEFAULT 30,
  `jumlah_soal_tkp` int(5) NOT NULL DEFAULT 35,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `kategori_ujian` VALUES
(1,'CPNS SKD 2024','Seleksi Kompetensi Dasar CPNS 2024',65,80,166,311,90,100,35,30,35,NOW()),
(2,'Kedinasan STAN','SKD PKN STAN',65,80,156,311,100,110,30,35,45,NOW()),
(3,'Kedinasan STIS','SKD Polstat STIS',65,80,156,311,100,110,30,35,45,NOW()),
(4,'Kedinasan IPDN','SKD IPDN',65,80,156,311,100,110,30,35,45,NOW());

CREATE TABLE `materi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kategori_ujian_id` int(11) DEFAULT NULL,
  `judul` varchar(255) NOT NULL,
  `topik` varchar(100) NOT NULL,
  `jenis_tes` enum('twk','tiu','tkp') DEFAULT NULL,
  `konten_html` longtext,
  `tipe` enum('artikel','video','flashcard','rumus') DEFAULT 'artikel',
  `level` enum('dasar','menengah','lanjut') DEFAULT 'dasar',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `kategori_ujian_id` (`kategori_ujian_id`),
  CONSTRAINT `materi_kategori` FOREIGN KEY (`kategori_ujian_id`) REFERENCES `kategori_ujian` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `soal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kategori_ujian_id` int(11) DEFAULT NULL,
  `jenis_tes` enum('twk','tiu','tkp') NOT NULL,
  `topik` varchar(100) NOT NULL,
  `pertanyaan` text NOT NULL,
  `gambar_url` varchar(255) DEFAULT NULL,
  `tingkat_kesulitan` enum('sangat_mudah','mudah','sedang','sulit','sangat_sulit') DEFAULT 'sedang',
  `pembahasan` longtext,
  `tips_triks` text,
  `sumber` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `kategori_ujian_id` (`kategori_ujian_id`),
  KEY `jenis_tes` (`jenis_tes`),
  KEY `topik` (`topik`),
  CONSTRAINT `soal_kategori` FOREIGN KEY (`kategori_ujian_id`) REFERENCES `kategori_ujian` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `opsi_jawaban` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `soal_id` int(11) NOT NULL,
  `label` enum('A','B','C','D','E') NOT NULL,
  `teks_jawaban` text NOT NULL,
  `bobot_nilai` int(2) NOT NULL DEFAULT 0,
  `is_kunci` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `soal_id` (`soal_id`),
  CONSTRAINT `opsi_soal` FOREIGN KEY (`soal_id`) REFERENCES `soal` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `paket_ujian` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kategori_ujian_id` int(11) NOT NULL,
  `nama_paket` varchar(100) NOT NULL,
  `deskripsi` text,
  `jumlah_soal_twk` int(5) NOT NULL DEFAULT 0,
  `jumlah_soal_tiu` int(5) NOT NULL DEFAULT 0,
  `jumlah_soal_tkp` int(5) NOT NULL DEFAULT 0,
  `waktu_menit` int(5) NOT NULL DEFAULT 90,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `kategori_ujian_id` (`kategori_ujian_id`),
  CONSTRAINT `paket_kategori` FOREIGN KEY (`kategori_ujian_id`) REFERENCES `kategori_ujian` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `paket_soal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `paket_ujian_id` int(11) NOT NULL,
  `soal_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `paket_soal_unique` (`paket_ujian_id`,`soal_id`),
  KEY `soal_id` (`soal_id`),
  CONSTRAINT `ps_paket` FOREIGN KEY (`paket_ujian_id`) REFERENCES `paket_ujian` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ps_soal` FOREIGN KEY (`soal_id`) REFERENCES `soal` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `hasil_ujian` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `paket_ujian_id` int(11) NOT NULL,
  `tanggal_mulai` datetime DEFAULT NULL,
  `tanggal_selesai` datetime DEFAULT NULL,
  `skor_twk` int(5) NOT NULL DEFAULT 0,
  `skor_tiu` int(5) NOT NULL DEFAULT 0,
  `skor_tkp` int(5) NOT NULL DEFAULT 0,
  `skor_kumulatif` int(5) NOT NULL DEFAULT 0,
  `status_lulus` enum('lulus','gugur','proses') DEFAULT 'proses',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `paket_ujian_id` (`paket_ujian_id`),
  CONSTRAINT `hu_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `hu_paket` FOREIGN KEY (`paket_ujian_id`) REFERENCES `paket_ujian` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `detail_jawaban` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hasil_ujian_id` int(11) NOT NULL,
  `soal_id` int(11) NOT NULL,
  `opsi_dipilih_id` int(11) DEFAULT NULL,
  `nilai_diperoleh` int(2) NOT NULL DEFAULT 0,
  `waktu_detik` int(5) DEFAULT 0,
  `is_ragu` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `hasil_ujian_id` (`hasil_ujian_id`),
  KEY `soal_id` (`soal_id`),
  CONSTRAINT `dj_hu` FOREIGN KEY (`hasil_ujian_id`) REFERENCES `hasil_ujian` (`id`) ON DELETE CASCADE,
  CONSTRAINT `dj_soal` FOREIGN KEY (`soal_id`) REFERENCES `soal` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `rekomendasi_belajar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hasil_ujian_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `topik` varchar(100) NOT NULL,
  `jenis_tes` enum('twk','tiu','tkp') NOT NULL,
  `skor_persentase` decimal(5,2) DEFAULT 0.00,
  `saran_materi_id` int(11) DEFAULT NULL,
  `tingkat_kesulitan_rekomendasi` enum('mudah','sedang','sulit') DEFAULT 'mudah',
  `status` enum('belum_dikerjakan','selesai') DEFAULT 'belum_dikerjakan',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `hasil_ujian_id` (`hasil_ujian_id`),
  CONSTRAINT `rb_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `rb_hu` FOREIGN KEY (`hasil_ujian_id`) REFERENCES `hasil_ujian` (`id`) ON DELETE CASCADE,
  CONSTRAINT `rb_materi` FOREIGN KEY (`saran_materi_id`) REFERENCES `materi` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `riwayat_materi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `materi_id` int(11) NOT NULL,
  `progress_persen` int(3) DEFAULT 0,
  `waktu_baca_menit` int(5) DEFAULT 0,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `materi_id` (`materi_id`),
  CONSTRAINT `rm_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `rm_materi` FOREIGN KEY (`materi_id`) REFERENCES `materi` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SET FOREIGN_KEY_CHECKS = 1;
