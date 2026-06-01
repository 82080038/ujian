-- Soal-soal SBMPTN/UTBK 2026
-- Kategori: SBMPTN/UTBK 2026 (ID: 5)

-- Matematika Dasar (TIU)
INSERT INTO soal (kategori_ujian_id, jenis_tes, topik, pertanyaan, tingkat_kesulitan) VALUES
(5, 'tiu', 'Matematika Dasar', 'Jika f(x) = 2x² - 3x + 1, maka nilai f(2) adalah...', 'sedang'),
(5, 'tiu', 'Matematika Dasar', 'Turunan pertama dari f(x) = x³ + 2x² - 5x + 3 adalah...', 'sedang'),
(5, 'tiu', 'Matematika Dasar', 'Integral dari 3x² + 2x adalah...', 'mudah'),
(5, 'tiu', 'Matematika Dasar', 'Jika log₂(x) = 3, maka nilai x adalah...', 'mudah'),
(5, 'tiu', 'Matematika Dasar', 'Nilai dari 2⁵ × 2³ adalah...', 'mudah'),
(5, 'tiu', 'Matematika Dasar', 'Suku ke-10 dari barisan aritmatika 2, 5, 8, 11, ... adalah...', 'sedang'),
(5, 'tiu', 'Matematika Dasar', 'Jumlah 10 suku pertama dari deret geometri 3, 6, 12, 24, ... adalah...', 'sedang'),
(5, 'tiu', 'Matematika Dasar', 'Himpunan penyelesaian dari |2x - 3| ≤ 5 adalah...', 'sedang'),
(5, 'tiu', 'Matematika Dasar', 'Nilai sin 30° + cos 60° adalah...', 'mudah'),
(5, 'tiu', 'Matematika Dasar', 'Luas lingkaran dengan jari-jari 7 cm adalah...', 'mudah');

-- Opsi jawaban Matematika Dasar
INSERT INTO opsi_jawaban (soal_id, label, teks_jawaban, bobot_nilai, is_kunci) VALUES
-- Soal 1: f(2) = 2(4) - 3(2) + 1 = 8 - 6 + 1 = 3
(220, 'A', '3', 5, 1), (220, 'B', '5', 0, 0), (220, 'C', '7', 0, 0), (220, 'D', '9', 0, 0), (220, 'E', '11', 0, 0),
-- Soal 2: f'(x) = 3x² + 4x - 5
(221, 'A', '3x² + 4x - 5', 5, 1), (221, 'B', '3x² + 4x + 5', 0, 0), (221, 'C', '6x + 4', 0, 0), (221, 'D', 'x³ + 2x² - 5x', 0, 0), (221, 'E', '3x² - 4x - 5', 0, 0),
-- Soal 3: ∫(3x² + 2x)dx = x³ + x² + C
(222, 'A', 'x³ + x² + C', 5, 1), (222, 'B', '3x³ + x² + C', 0, 0), (222, 'C', '6x + 2 + C', 0, 0), (222, 'D', 'x³ + 2x + C', 0, 0), (222, 'E', '3x² + 2x + C', 0, 0),
-- Soal 4: log₂(x) = 3 → x = 2³ = 8
(223, 'A', '6', 0, 0), (223, 'B', '8', 5, 1), (223, 'C', '9', 0, 0), (223, 'D', '12', 0, 0), (223, 'E', '16', 0, 0),
-- Soal 5: 2⁵ × 2³ = 2⁸ = 256
(224, 'A', '64', 0, 0), (224, 'B', '128', 0, 0), (224, 'C', '256', 5, 1), (224, 'D', '512', 0, 0), (224, 'E', '1024', 0, 0),
-- Soal 6: Un = a + (n-1)b = 2 + 9(3) = 29
(225, 'A', '27', 0, 0), (225, 'B', '29', 5, 1), (225, 'C', '31', 0, 0), (225, 'D', '33', 0, 0), (225, 'E', '35', 0, 0),
-- Soal 7: Sn = a(rⁿ - 1)/(r-1) = 3(2¹⁰ - 1)/1 = 3(1024 - 1) = 3069
(226, 'A', '1023', 0, 0), (226, 'B', '2046', 0, 0), (226, 'C', '3069', 5, 1), (226, 'D', '4092', 0, 0), (226, 'E', '5120', 0, 0),
-- Soal 8: -5 ≤ 2x - 3 ≤ 5 → -2 ≤ 2x ≤ 8 → -1 ≤ x ≤ 4
(227, 'A', 'x ≤ -1 atau x ≥ 4', 0, 0), (227, 'B', '-1 ≤ x ≤ 4', 5, 1), (227, 'C', 'x ≤ -4 atau x ≥ 1', 0, 0), (227, 'D', '-4 ≤ x ≤ 1', 0, 0), (227, 'E', 'x ≤ 1 atau x ≥ 4', 0, 0),
-- Soal 9: sin 30° + cos 60° = 0.5 + 0.5 = 1
(228, 'A', '0', 0, 0), (228, 'B', '0.5', 0, 0), (228, 'C', '1', 5, 1), (228, 'D', '1.5', 0, 0), (228, 'E', '2', 0, 0),
-- Soal 10: L = πr² = π(7)² = 49π
(229, 'A', '14π', 0, 0), (229, 'B', '49π', 5, 1), (229, 'C', '98π', 0, 0), (229, 'D', '154π', 0, 0), (229, 'E', '196π', 0, 0);

-- Bahasa Indonesia (TWK)
INSERT INTO soal (kategori_ujian_id, jenis_tes, topik, pertanyaan, tingkat_kesulitan) VALUES
(5, 'twk', 'Bahasa Indonesia', 'Kalimat efektif yang merupakan kalimat majemuk setara adalah...', 'sedang'),
(5, 'twk', 'Bahasa Indonesia', 'Sinonim dari kata "eksploitasi" adalah...', 'mudah'),
(5, 'twk', 'Bahasa Indonesia', 'Antonim dari kata "stabil" adalah...', 'mudah'),
(5, 'twk', 'Bahasa Indonesia', 'Kata yang tidak baku adalah...', 'mudah'),
(5, 'twk', 'Bahasa Indonesia', 'Penggunaan kata depan yang tepat adalah...', 'sedang'),
(5, 'twk', 'Bahasa Indonesia', 'Kalimat yang menggunakan gaya bahasa personifikasi adalah...', 'sedang'),
(5, 'twk', 'Bahasa Indonesia', 'Makna kata "merah" dalam "wajahnya merah padam" adalah...', 'sedang'),
(5, 'twk', 'Bahasa Indonesia', 'Ide pokok paragraf biasanya terdapat pada...', 'mudah'),
(5, 'twk', 'Bahasa Indonesia', 'Kalimat tanya yang tidak memerlukan jawaban adalah...', 'mudah'),
(5, 'twk', 'Bahasa Indonesia', 'Paragraf deduktif adalah paragraf yang...', 'sedang');

-- Opsi jawaban Bahasa Indonesia
INSERT INTO opsi_jawaban (soal_id, label, teks_jawaban, bobot_nilai, is_kunci) VALUES
-- Soal 11: Kalimat majemuk setara menggunakan konjungsi "dan, atau, tetapi"
(230, 'A', 'Ibu memasak nasi dan ayam goreng', 5, 1), (230, 'B', 'Ibu memasak nasi lalu ayam goreng', 0, 0), (230, 'C', 'Ibu memasak nasi kemudian ayam goreng', 0, 0), (230, 'D', 'Ibu memasak nasi setelah ayam goreng', 0, 0), (230, 'E', 'Ibu memasak nasi sehingga ayam goreng', 0, 0),
-- Soal 12: Eksploitasi = penyelewengan/pemanfaatan
(231, 'A', 'Pelestarian', 0, 0), (231, 'B', 'Penyelewengan', 5, 1), (231, 'C', 'Pengembangan', 0, 0), (231, 'D', 'Penegakan', 0, 0), (231, 'E', 'Pemeliharaan', 0, 0),
-- Soal 13: Stabil (tetap) ↔ Labil (tidak tetap)
(232, 'A', 'Tetap', 0, 0), (232, 'B', 'Kuat', 0, 0), (232, 'C', 'Labil', 5, 1), (232, 'D', 'Kokoh', 0, 0), (232, 'E', 'Solid', 0, 0),
-- Soal 14: Kata baku = apotik, tidak baku = apotek
(233, 'A', 'Apotik', 0, 0), (233, 'B', 'Apotek', 5, 1), (233, 'C', 'Analisis', 0, 0), (233, 'D', 'Kalkulator', 0, 0), (233, 'E', 'Praktis', 0, 0),
-- Soal 15: "di" untuk tempat, "ke" untuk arah
(234, 'A', 'Saya pergi ke pasar', 5, 1), (234, 'B', 'Saya pergi di pasar', 0, 0), (234, 'C', 'Saya tinggal di rumah', 0, 0), (234, 'D', 'Saya berangkat ke sekolah', 0, 0), (234, 'E', 'Saya duduk di kelas', 0, 0),
-- Soal 16: Personifikasi = memberi sifat manusia pada benda
(235, 'A', 'Matahari bersinar terik', 0, 0), (235, 'B', 'Angin berhembus kencang', 0, 0), (235, 'C', 'Ombak menari-nari di pantai', 5, 1), (235, 'D', 'Hujan turun deras', 0, 0), (235, 'E', 'Guntur menggelegar', 0, 0),
-- Soal 17: Merah padam = sedih/murung
(236, 'A', 'Bersemangat', 0, 0), (236, 'B', 'Marah', 0, 0), (236, 'C', 'Sedih', 5, 1), (236, 'D', 'Bangga', 0, 0), (236, 'E', 'Takut', 0, 0),
-- Soal 18: Ide pokok biasanya di kalimat utama (awal paragraf)
(237, 'A', 'Akhir paragraf', 0, 0), (237, 'B', 'Tengah paragraf', 0, 0), (237, 'C', 'Awal paragraf', 5, 1), (237, 'D', 'Semua kalimat', 0, 0), (237, 'E', 'Kalimat terakhir', 0, 0),
-- Soal 19: Kalimat retoris = tanya tanpa jawaban
(238, 'A', 'Siapa namamu?', 0, 0), (238, 'B', 'Mengapa kamu menangis?', 0, 0), (238, 'C', 'Bukankah Indonesia merdeka?', 5, 1), (238, 'D', 'Kapan kamu pulang?', 0, 0), (238, 'E', 'Di mana kamu tinggal?', 0, 0),
-- Soal 20: Deduktif = umum ke khusus
(239, 'A', 'Dimulai dari khusus ke umum', 0, 0), (239, 'B', 'Dimulai dari umum ke khusus', 5, 1), (239, 'C', 'Hanya berisi fakta', 0, 0), (239, 'D', 'Hanya berisi opini', 0, 0), (239, 'E', 'Tidak memiliki ide pokok', 0, 0);

-- Bahasa Inggris (TIU)
INSERT INTO soal (kategori_ujian_id, jenis_tes, topik, pertanyaan, tingkat_kesulitan) VALUES
(5, 'tiu', 'Bahasa Inggris', 'The opposite of "happy" is...', 'mudah'),
(5, 'tiu', 'Bahasa Inggris', 'Choose the correct sentence: "She ___ to school every day."', 'mudah'),
(5, 'tiu', 'Bahasa Inggris', 'The synonym of "beautiful" is...', 'mudah'),
(5, 'tiu', 'Bahasa Inggris', 'Which word is a noun?', 'mudah'),
(5, 'tiu', 'Bahasa Inggris', 'Complete: "If I ___ rich, I would buy a car."', 'sedang'),
(5, 'tiu', 'Bahasa Inggris', 'The passive form of "They built this house" is...', 'sedang'),
(5, 'tiu', 'Bahasa Inggris', 'Choose the correct preposition: "She is good ___ mathematics."', 'mudah'),
(5, 'tiu', 'Bahasa Inggris', 'The past tense of "go" is...', 'mudah'),
(5, 'tiu', 'Bahasa Inggris', 'Which is a conditional sentence?', 'sedang'),
(5, 'tiu', 'Bahasa Inggris', 'The meaning of "ubiquitous" is...', 'sedang');

-- Opsi jawaban Bahasa Inggris
INSERT INTO opsi_jawaban (soal_id, label, teks_jawaban, bobot_nilai, is_kunci) VALUES
-- Soal 21: Opposite of happy = sad
(240, 'A', 'Joyful', 0, 0), (240, 'B', 'Sad', 5, 1), (240, 'C', 'Excited', 0, 0), (240, 'D', 'Cheerful', 0, 0), (240, 'E', 'Glad', 0, 0),
-- Soal 22: goes (present simple)
(241, 'A', 'go', 0, 0), (241, 'B', 'goes', 5, 1), (241, 'C', 'going', 0, 0), (241, 'D', 'went', 0, 0), (241, 'E', 'gone', 0, 0),
-- Soal 23: Beautiful = pretty/attractive
(242, 'A', 'Ugly', 0, 0), (242, 'B', 'Pretty', 5, 1), (242, 'C', 'Difficult', 0, 0), (242, 'D', 'Simple', 0, 0), (242, 'E', 'Complex', 0, 0),
-- Soal 24: Happiness = noun
(243, 'A', 'Run', 0, 0), (243, 'B', 'Quickly', 0, 0), (243, 'C', 'Happiness', 5, 1), (243, 'D', 'Beautiful', 0, 0), (243, 'E', 'Sing', 0, 0),
-- Soal 25: Conditional type 2 = were
(244, 'A', 'am', 0, 0), (244, 'B', 'is', 0, 0), (244, 'C', 'are', 0, 0), (244, 'D', 'were', 5, 1), (244, 'E', 'was', 0, 0),
-- Soal 26: Passive = This house was built by them
(245, 'A', 'This house is built by them', 0, 0), (245, 'B', 'This house was built by them', 5, 1), (245, 'C', 'This house has been built by them', 0, 0), (245, 'D', 'This house will be built by them', 0, 0), (245, 'E', 'This house had been built by them', 0, 0),
-- Soal 27: good at
(246, 'A', 'in', 0, 0), (246, 'B', 'at', 5, 1), (246, 'C', 'on', 0, 0), (246, 'D', 'with', 0, 0), (246, 'E', 'for', 0, 0),
-- Soal 28: Past tense of go = went
(247, 'A', 'goed', 0, 0), (247, 'B', 'gone', 0, 0), (247, 'C', 'went', 5, 1), (247, 'D', 'going', 0, 0), (247, 'E', 'goes', 0, 0),
-- Soal 29: If I study, I will pass (type 1)
(248, 'A', 'I study, I pass', 0, 0), (248, 'B', 'If I study, I will pass', 5, 1), (248, 'C', 'If I studied, I would pass', 0, 0), (248, 'D', 'If I had studied, I would have passed', 0, 0), (248, 'E', 'I will study if I pass', 0, 0),
-- Soal 30: Ubiquitous = everywhere/present everywhere
(249, 'A', 'Rare', 0, 0), (249, 'B', 'Present everywhere', 5, 1), (249, 'C', 'Hidden', 0, 0), (249, 'D', 'Ancient', 0, 0), (249, 'E', 'Future', 0, 0);

-- IPA - Fisika (TIU)
INSERT INTO soal (kategori_ujian_id, jenis_tes, topik, pertanyaan, tingkat_kesulitan) VALUES
(5, 'tiu', 'Fisika', 'Satuan SI untuk gaya adalah...', 'mudah'),
(5, 'tiu', 'Fisika', 'Energi kinetik suatu benda bergerak dengan massa 2 kg dan kecepatan 10 m/s adalah...', 'sedang'),
(5, 'tiu', 'Fisika', 'Hukum Newton ke-2 menyatakan bahwa...', 'sedang'),
(5, 'tiu', 'Fisika', 'Daya adalah besaran turunan dari...', 'mudah'),
(5, 'tiu', 'Fisika', 'Kecepatan cahaya dalam vakum adalah...', 'mudah'),
(5, 'tiu', 'Fisika', 'Energi potensial benda dengan massa 5 kg pada ketinggian 10 m (g=10 m/s²) adalah...', 'sedang'),
(5, 'tiu', 'Fisika', 'Frekuensi getaran dengan periode 0.5 detik adalah...', 'mudah'),
(5, 'tiu', 'Fisika', 'Hukum Ohm menyatakan hubungan antara...', 'sedang'),
(5, 'tiu', 'Fisika', 'Muatan listrik diukur dengan satuan...', 'mudah'),
(5, 'tiu', 'Fisika', 'Energi yang dihasilkan oleh resistor 10 ohm dengan arus 2 A selama 10 detik adalah...', 'sedang');

-- Opsi jawaban Fisika
INSERT INTO opsi_jawaban (soal_id, label, teks_jawaban, bobot_nilai, is_kunci) VALUES
-- Soal 31: Satuan gaya = Newton (N)
(250, 'A', 'Joule', 0, 0), (250, 'B', 'Watt', 0, 0), (250, 'C', 'Newton', 5, 1), (250, 'D', 'Pascal', 0, 0), (250, 'E', 'Coulomb', 0, 0),
-- Soal 32: Ek = ½mv² = ½(2)(100) = 100 J
(251, 'A', '20 J', 0, 0), (251, 'B', '50 J', 0, 0), (251, 'C', '100 J', 5, 1), (251, 'D', '200 J', 0, 0), (251, 'E', '400 J', 0, 0),
-- Soal 33: F = ma
(252, 'A', 'Gaya sebanding dengan massa', 0, 0), (252, 'B', 'Gaya sebanding dengan percepatan', 0, 0), (252, 'C', 'Gaya = massa × percepatan', 5, 1), (252, 'D', 'Gaya berbanding terbalik dengan massa', 0, 0), (252, 'E', 'Gaya berbanding terbalik dengan percepatan', 0, 0),
-- Soal 34: Daya = Energi/Waktu
(253, 'A', 'Energi', 5, 1), (253, 'B', 'Gaya', 0, 0), (253, 'C', 'Tekanan', 0, 0), (253, 'D', 'Volume', 0, 0), (253, 'E', 'Massa', 0, 0),
-- Soal 35: c = 3 × 10⁸ m/s
(254, 'A', '3 × 10⁶ m/s', 0, 0), (254, 'B', '3 × 10⁷ m/s', 0, 0), (254, 'C', '3 × 10⁸ m/s', 5, 1), (254, 'D', '3 × 10⁹ m/s', 0, 0), (254, 'E', '3 × 10¹⁰ m/s', 0, 0),
-- Soal 36: Ep = mgh = 5(10)(10) = 500 J
(255, 'A', '50 J', 0, 0), (255, 'B', '100 J', 0, 0), (255, 'C', '500 J', 5, 1), (255, 'D', '1000 J', 0, 0), (255, 'E', '5000 J', 0, 0),
-- Soal 37: f = 1/T = 1/0.5 = 2 Hz
(256, 'A', '0.5 Hz', 0, 0), (256, 'B', '1 Hz', 0, 0), (256, 'C', '2 Hz', 5, 1), (256, 'D', '4 Hz', 0, 0), (256, 'E', '5 Hz', 0, 0),
-- Soal 38: V = IR
(257, 'A', 'Tegangan dan arus', 0, 0), (257, 'B', 'Tegangan dan hambatan', 0, 0), (257, 'C', 'Arus dan hambatan', 0, 0), (257, 'D', 'Tegangan, arus, dan hambatan', 5, 1), (257, 'E', 'Daya dan energi', 0, 0),
-- Soal 39: Muatan = Coulomb (C)
(258, 'A', 'Volt', 0, 0), (258, 'B', 'Ampere', 0, 0), (258, 'C', 'Ohm', 0, 0), (258, 'D', 'Coulomb', 5, 1), (258, 'E', 'Watt', 0, 0),
-- Soal 40: E = I²Rt = (2)²(10)(10) = 400 J
(259, 'A', '40 J', 0, 0), (259, 'B', '100 J', 0, 0), (259, 'C', '200 J', 0, 0), (259, 'D', '400 J', 5, 1), (259, 'E', '800 J', 0, 0);

-- IPA - Kimia (TIU)
INSERT INTO soal (kategori_ujian_id, jenis_tes, topik, pertanyaan, tingkat_kesulitan) VALUES
(5, 'tiu', 'Kimia', 'Nomor atom karbon adalah...', 'mudah'),
(5, 'tiu', 'Kimia', 'Jumlah elektron pada atom netral sama dengan...', 'mudah'),
(5, 'tiu', 'Kimia', 'Reaksi pembakaran lengkap hidrokarbon menghasilkan...', 'sedang'),
(5, 'tiu', 'Kimia', 'pH larutan asam kuat 0.01 M adalah...', 'sedang'),
(5, 'tiu', 'Kimia', 'Senyawa ion terdiri dari...', 'mudah'),
(5, 'tiu', 'Kimia', 'Massa molekul relatif (Mr) air (H₂O) adalah...', 'mudah'),
(5, 'tiu', 'Kimia', 'Ikatan kovalen terjadi antara...', 'sedang'),
(5, 'tiu', 'Kimia', 'Hukum kekekalan massa menyatakan...', 'sedang'),
(5, 'tiu', 'Kimia', 'Gas mulia yang paling banyak di atmosfer adalah...', 'mudah'),
(5, 'tiu', 'Kimia', 'Jumlah mol dalam 36 gram air (Mr=18) adalah...', 'sedang');

-- Opsi jawaban Kimia
INSERT INTO opsi_jawaban (soal_id, label, teks_jawaban, bobot_nilai, is_kunci) VALUES
-- Soal 41: Nomor atom C = 6
(260, 'A', '4', 0, 0), (260, 'B', '6', 5, 1), (260, 'C', '8', 0, 0), (260, 'D', '12', 0, 0), (260, 'E', '14', 0, 0),
-- Soal 42: Elektron = Proton (atom netral)
(261, 'A', 'Jumlah proton', 5, 1), (261, 'B', 'Jumlah neutron', 0, 0), (261, 'C', 'Jumlah proton + neutron', 0, 0), (261, 'D', 'Jumlah proton - neutron', 0, 0), (261, 'E', 'Jumlah neutron - proton', 0, 0),
-- Soal 43: CO₂ + H₂O
(262, 'A', 'CO dan H₂', 0, 0), (262, 'B', 'CO₂ dan H₂O', 5, 1), (262, 'C', 'C dan H₂O', 0, 0), (262, 'D', 'CO dan H₂O', 0, 0), (262, 'E', 'C dan H₂', 0, 0),
-- Soal 44: pH = -log[H+] = -log(0.01) = 2
(263, 'A', '1', 0, 0), (263, 'B', '2', 5, 1), (263, 'C', '3', 0, 0), (263, 'D', '4', 0, 0), (263, 'E', '5', 0, 0),
-- Soal 45: Senyawa ion = kation dan anion
(264, 'A', 'Atom netral', 0, 0), (264, 'B', 'Kation dan anion', 5, 1), (264, 'C', 'Molekul', 0, 0), (264, 'D', 'Isotop', 0, 0), (264, 'E', 'Ion tunggal', 0, 0),
-- Soal 46: Mr H₂O = 2(1) + 16 = 18
(265, 'A', '16', 0, 0), (265, 'B', '18', 5, 1), (265, 'C', '20', 0, 0), (265, 'D', '32', 0, 0), (265, 'E', '36', 0, 0),
-- Soal 47: Kovalen = nonlogam dengan nonlogam
(266, 'A', 'Logam dengan logam', 0, 0), (266, 'B', 'Logam dengan nonlogam', 0, 0), (266, 'C', 'Nonlogam dengan nonlogam', 5, 1), (266, 'D', 'Ion dengan ion', 0, 0), (266, 'E', 'Atom dengan molekul', 0, 0),
-- Soal 48: Massa reaktan = massa produk
(267, 'A', 'Massa reaktan < massa produk', 0, 0), (267, 'B', 'Massa reaktan = massa produk', 5, 1), (267, 'C', 'Massa reaktan > massa produk', 0, 0), (267, 'D', 'Massa reaktan ≠ massa produk', 0, 0), (267, 'E', 'Massa reaktan 2× massa produk', 0, 0),
-- Soal 49: Gas mulia terbanyak = Nitrogen (N₂)
(268, 'A', 'Helium', 0, 0), (268, 'B', 'Neon', 0, 0), (268, 'C', 'Argon', 0, 0), (268, 'D', 'Nitrogen', 5, 1), (268, 'E', 'Oksigen', 0, 0),
-- Soal 50: n = m/Mr = 36/18 = 2 mol
(269, 'A', '1 mol', 0, 0), (269, 'B', '2 mol', 5, 1), (269, 'C', '3 mol', 0, 0), (269, 'D', '4 mol', 0, 0), (269, 'E', '5 mol', 0, 0);

-- IPA - Biologi (TIU)
INSERT INTO soal (kategori_ujian_id, jenis_tes, topik, pertanyaan, tingkat_kesulitan) VALUES
(5, 'tiu', 'Biologi', 'Organel sel yang berfungsi sebagai pusat kendali adalah...', 'mudah'),
(5, 'tiu', 'Biologi', 'Fotosintesis terjadi di...', 'mudah'),
(5, 'tiu', 'Biologi', 'DNA terdiri dari...', 'sedang'),
(5, 'tiu', 'Biologi', 'Enzim yang mencerna protein adalah...', 'mudah'),
(5, 'tiu', 'Biologi', 'Sel hewan memiliki organel yang tidak dimiliki sel tumbuhan, yaitu...', 'sedang'),
(5, 'tiu', 'Biologi', 'Peredaran darah pada manusia disebut...', 'mudah'),
(5, 'tiu', 'Biologi', 'Sistem pencernaan manusia dimulai dari...', 'mudah'),
(5, 'tiu', 'Biologi', 'Oksigen diangkut oleh darah melalui...', 'mudah'),
(5, 'tiu', 'Biologi', 'Mitosis menghasilkan sel...', 'sedang'),
(5, 'tiu', 'Biologi', 'Tumbuhan hijau menghasilkan makanan melalui...', 'mudah');

-- Opsi jawaban Biologi
INSERT INTO opsi_jawaban (soal_id, label, teks_jawaban, bobot_nilai, is_kunci) VALUES
-- Soal 51: Pusat kendali = inti (nucleus)
(270, 'A', 'Mitokondria', 0, 0), (270, 'B', 'Kloroplas', 0, 0), (270, 'C', 'Inti', 5, 1), (270, 'D', 'Ribosom', 0, 0), (270, 'E', 'Retikulum endoplasma', 0, 0),
-- Soal 52: Fotosintesis = kloroplas
(271, 'A', 'Mitokondria', 0, 0), (271, 'B', 'Kloroplas', 5, 1), (271, 'C', 'Ribosom', 0, 0), (271, 'D', 'Inti', 0, 0), (271, 'E', 'Membran sel', 0, 0),
-- Soal 53: DNA = nukleotida (adenin, timin, guanin, sitosin)
(272, 'A', 'Asam amino', 0, 0), (272, 'B', 'Nukleotida', 5, 1), (272, 'C', 'Lipid', 0, 0), (272, 'D', 'Karbohidrat', 0, 0), (272, 'E', 'Protein', 0, 0),
-- Soal 54: Enzim protein = pepsin
(273, 'A', 'Amilase', 0, 0), (273, 'B', 'Lipase', 0, 0), (273, 'C', 'Pepsin', 5, 1), (273, 'D', 'Tripsin', 0, 0), (273, 'E', 'Sakarase', 0, 0),
-- Soal 55: Sel hewan punya sentrosom, sel tumbuhan tidak
(274, 'A', 'Dinding sel', 0, 0), (274, 'B', 'Kloroplas', 0, 0), (274, 'C', 'Vakuola besar', 0, 0), (274, 'D', 'Sentrosom', 5, 1), (274, 'E', 'Membran sel', 0, 0),
-- Soal 56: Peredaran darah = sistem sirkulasi tertutup
(275, 'A', 'Sistem sirkulasi terbuka', 0, 0), (275, 'B', 'Sistem sirkulasi tertutup', 5, 1), (275, 'C', 'Sistem sirkulasi ganda', 0, 0), (275, 'D', 'Sistem sirkulasi tunggal', 0, 0), (275, 'E', 'Sistem sirkulasi campuran', 0, 0),
-- Soal 57: Pencernaan dimulai dari mulut
(276, 'A', 'Kerongkongan', 0, 0), (276, 'B', 'Lambung', 0, 0), (276, 'C', 'Usus', 0, 0), (276, 'D', 'Mulut', 5, 1), (276, 'E', 'Hati', 0, 0),
-- Soal 58: Oksigen diangkut oleh hemoglobin dalam eritrosit
(277, 'A', 'Leukosit', 0, 0), (277, 'B', 'Eritrosit', 5, 1), (277, 'C', 'Trombosit', 0, 0), (277, 'D', 'Plasma', 0, 0), (277, 'E', 'Limfosit', 0, 0),
-- Soal 59: Mitosis = 2 sel anak identik
(278, 'A', '1 sel anak', 0, 0), (278, 'B', '2 sel anak identik', 5, 1), (278, 'C', '4 sel anak identik', 0, 0), (278, 'D', '2 sel anak berbeda', 0, 0), (278, 'E', '4 sel anak berbeda', 0, 0),
-- Soal 60: Fotosintesis
(279, 'A', 'Respirasi', 0, 0), (279, 'B', 'Fotosintesis', 5, 1), (279, 'C', 'Fermentasi', 0, 0), (279, 'D', 'Transpirasi', 0, 0), (279, 'E', 'Osmosis', 0, 0);
