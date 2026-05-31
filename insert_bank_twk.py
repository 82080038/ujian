#!/usr/bin/env python3
"""Insert 50 soal TWK unik"""
import time, hashlib, mysql.connector

DB = {'host': 'localhost', 'user': 'root', 'password': 'root', 'database': 'db_tryout'}
conn = mysql.connector.connect(**DB)
cur = conn.cursor(dictionary=True)

SOAL = [
('twk','Pancasila','Sila pertama menuntut setiap warga untuk...','mudah','Menghormati kebebasan beragama dan toleransi','Sila 1 = toleransi beragama',
[('A','Menghormati kebebasan beragama setiap orang',5,1),('B','Menganut agama mayoritas',0,0),('C','Mewajibkan semua beragama',0,0),('D','Membatasi ibadah minoritas',0,0),('E','Menutup tempat ibadah tak umum',0,0)]),
('twk','Pancasila','Nilai kemanusiaan terwujud ketika seseorang...','mudah','Menghargai HAM tanpa diskriminasi','Sila 2 = HAM, tidak diskriminasi',
[('A','Menghargai HAM tanpa memandang latar belakang',5,1),('B','Bantu hanya kelompok sendiri',0,0),('C','Utamakan keluarga di atas masyarakat',0,0),('D','Batasi hak demi ketertiban',0,0),('E','Abaikan perbedaan status',0,0)]),
('twk','Pancasila','Sikap saling menghormati antar suku adalah implementasi sila...','sangat_mudah','Sila ke-3: Persatuan Indonesia','Sila 3 = persatuan, suku bangsa',
[('A','Persatuan Indonesia',5,1),('B','Ketuhanan',0,0),('C','Kemanusiaan',0,0),('D','Keadilan Sosial',0,0),('E','Kerakyatan',0,0)]),
('twk','Pancasila','Musyawarah untuk mufakat adalah implementasi sila...','sangat_mudah','Sila ke-4: Kerakyatan yang Dipimpin oleh Hikmat Kebijaksanaan','Sila 4 = musyawarah, demokrasi',
[('A','Kerakyatan yang Dipimpin oleh Hikmat Kebijaksanaan',5,1),('B','Persatuan Indonesia',0,0),('C','Keadilan Sosial',0,0),('D','Ketuhanan',0,0),('E','Kemanusiaan',0,0)]),
('twk','Pancasila','Pembangunan infrastruktur di daerah terpencil agar setara kota besar = sila...','mudah','Sila ke-5: Keadilan Sosial bagi Seluruh Rakyat Indonesia','Sila 5 = merata, keadilan sosial',
[('A','Keadilan Sosial',5,1),('B','Persatuan Indonesia',0,0),('C','Kerakyatan',0,0),('D','Ketuhanan',0,0),('E','Kemanusiaan',0,0)]),
('twk','Pancasila','Menghormati ibadah umat lain di lingkungan mayoritas berbeda = sila...','sedang','Sila pertama: Ketuhanan Yang Maha Esa','Sila 1 = toleransi. Hindari memaksakan.',
[('A','Ketuhanan Yang Maha Esa',5,1),('B','Kemanusiaan',0,0),('C','Persatuan Indonesia',0,0),('D','Kerakyatan',0,0),('E','Keadilan Sosial',0,0)]),
('twk','Pancasila','Tidak diskriminasi disabilitas dalam penerimaan kerja = sila...','mudah','Sila kedua: Kemanusiaan yang Adil dan Beradab','Sila 2 = HAM. Diskriminasi = melanggar.',
[('A','Kemanusiaan yang Adil dan Beradab',5,1),('B','Ketuhanan',0,0),('C','Persatuan Indonesia',0,0),('D','Kerakyatan',0,0),('E','Keadilan Sosial',0,0)]),
('twk','Pancasila','Rela berkorban harta bantu korban bencana di daerah lain = sila...','mudah','Sila ketiga: Persatuan Indonesia','Sila 3 = korban untuk bangsa. Bersama > pribadi.',
[('A','Persatuan Indonesia',5,1),('B','Ketuhanan',0,0),('C','Kemanusiaan',0,0),('D','Kerakyatan',0,0),('E','Keadilan Sosial',0,0)]),
('twk','Pancasila','Pemilihan kepala desa dengan musyawarah dan voting = sila...','sangat_mudah','Sila keempat: Kerakyatan yang Dipimpin oleh Hikmat Kebijaksanaan','Sila 4 = demokrasi Pancasila. Musyawahan = ciri khas.',
[('A','Kerakyatan yang Dipimpin oleh Hikmat Kebijaksanaan',5,1),('B','Persatuan Indonesia',0,0),('C','Keadilan Sosial',0,0),('D','Ketuhanan',0,0),('E','Kemanusiaan',0,0)]),
('twk','Pancasila','Pemerintah beri bantuan pangan gratis bagi miskin = sila...','mudah','Sila kelima: Keadilan Sosial bagi Seluruh Rakyat Indonesia','Sila 5 = keadilan sosial. Distribusi merata.',
[('A','Keadilan Sosial',5,1),('B','Persatuan Indonesia',0,0),('C','Kerakyatan',0,0),('D','Ketuhanan',0,0),('E','Kemanusiaan',0,0)]),
('twk','Pancasila','Penghapusan diskriminasi ras di tempat umum merupakan implementasi...','mudah','Sila kedua: Kemanusiaan yang Adil dan Beradab','Sila 2 = tidak diskriminasi.',
[('A','Kemanusiaan yang Adil dan Beradab',5,1),('B','Ketuhanan Yang Maha Esa',0,0),('C','Persatuan Indonesia',0,0),('D','Kerakyatan',0,0),('E','Keadilan Sosial',0,0)]),
('twk','Pancasila','Gotong royong membersihkan lingkungan kampung = implementasi sila...','sangat_mudah','Sila ke-4 Kerakyatan','Gotong royong = kerakyatan/kekeluargaan.',
[('A','Kerakyatan yang Dipimpin oleh Hikmat Kebijaksanaan',5,1),('B','Persatuan Indonesia',0,0),('C','Keadilan Sosial',0,0),('D','Ketuhanan',0,0),('E','Kemanusiaan',0,0)]),
('twk','Pancasila','Pemerintah membangun sekolah di pedalaman agar anak bisa sekolah = sila...','mudah','Sila ke-5: Keadilan Sosial bagi Seluruh Rakyat Indonesia','Sila 5 = pendidikan merata, keadilan sosial.',
[('A','Keadilan Sosial',5,1),('B','Persatuan Indonesia',0,0),('C','Kerakyatan',0,0),('D','Ketuhanan',0,0),('E','Kemanusiaan',0,0)]),
('twk','Pancasila','Setiap warga berhak memilih pemimpin sesuai keyakinan = sila...','mudah','Sila ke-4: Kerakyatan yang Dipimpin oleh Hikmat Kebijaksanaan','Sila 4 = demokrasi, hak memilih.',
[('A','Kerakyatan yang Dipimpin oleh Hikmat Kebijaksanaan',5,1),('B','Ketuhanan Yang Maha Esa',0,0),('C','Persatuan Indonesia',0,0),('D','Kemanusiaan',0,0),('E','Keadilan Sosial',0,0)]),
('twk','Pancasila','Menjaga nama baik bangsa di kancah internasional = sila...','sedang','Sila ke-3: Persatuan Indonesia','Sila 3 = menjaga martabat bangsa.',
[('A','Persatuan Indonesia',5,1),('B','Ketuhanan',0,0),('C','Kemanusiaan',0,0),('D','Kerakyatan',0,0),('E','Keadilan Sosial',0,0)]),

('twk','UUD 1945','Negara Indonesia berbentuk...','sangat_mudah','Pasal 1 ayat (1): Negara Kesatuan yang berbentuk Republik','Hafal: Pasal 1 = Bentuk & Kedaulatan.',
[('A','Negara Kesatuan yang berbentuk Republik',5,1),('B','Negara Federasi',0,0),('C','Negara Kerajaan',0,0),('D','Negara Uni',0,0),('E','Negara Serikat',0,0)]),
('twk','UUD 1945','Kedaulatan berada di tangan rakyat diatur dalam...','sangat_mudah','Pasal 1 ayat (2): Kedaulatan berada di tangan rakyat','Pasal 1 ayat (2) = kedaulatan rakyat.',
[('A','Pasal 1 ayat (2)',5,1),('B','Pasal 1 ayat (3)',0,0),('C','Pasal 2 ayat (1)',0,0),('D','Pasal 3',0,0),('E','Pasal 4',0,0)]),
('twk','UUD 1945','Presiden memegang kekuasaan pemerintahan menurut UUD 1945 diatur dalam...','mudah','Pasal 4 ayat (1): Presiden memegang kekuasaan pemerintahan','Pasal 4 = Presiden. Pasal 5 = UU.',
[('A','Pasal 4 ayat (1)',5,1),('B','Pasal 3',0,0),('C','Pasal 5 ayat (1)',0,0),('D','Pasal 6',0,0),('E','Pasal 7',0,0)]),
('twk','UUD 1945','Setiap warga negara berhak atas pekerjaan dan penghidupan layak diatur dalam...','sedang','Pasal 27 ayat (2): Tiap warga negara berhak atas pekerjaan dan penghidupan layak','Pasal 27 = warga negara. (1) sama di muka hukum. (2) pekerjaan. (3) pembelaan.',
[('A','Pasal 27 ayat (2)',5,1),('B','Pasal 28 ayat (1)',0,0),('C','Pasal 28A',0,0),('D','Pasal 28E ayat (1)',0,0),('E','Pasal 30',0,0)]),
('twk','UUD 1945','Hak memiliki tanah dan benda produktif diatur dalam...','sulit','Pasal 28H ayat (4): Setiap orang berhak memiliki hak milik pribadi','Pasal 28H = kesejahteraan. ayat (4) = hak milik.',
[('A','Pasal 28H ayat (4)',5,1),('B','Pasal 28A ayat (1)',0,0),('C','Pasal 28E ayat (2)',0,0),('D','Pasal 27 ayat (1)',0,0),('E','Pasal 28G ayat (2)',0,0)]),
('twk','UUD 1945','Kekuasaan kehakiman dilakukan oleh...','mudah','Pasal 24: Mahkamah Agung dan badan peradilan di bawahnya','Pasal 24 = kehakiman. Bukan hanya MA, tapi semua badan peradilan.',
[('A','Mahkamah Agung dan badan peradilan di bawahnya',5,1),('B','Presiden dan Wakil Presiden',0,0),('C','DPR dan DPD',0,0),('D','MPR sebagai lembaga tertinggi',0,0),('E','Komisi Yudisial semata',0,0)]),
('twk','UUD 1945','Pendidikan dasar wajib dan biayanya ditanggung pemerintah diatur dalam...','mudah','Pasal 31 ayat (2): Pemerintah wajib membiayai pendidikan dasar','Pasal 31 = pendidikan. Wajib & gratis.',
[('A','Pasal 31 ayat (2)',5,1),('B','Pasal 27 ayat (2)',0,0),('C','Pasal 28A',0,0),('D','Pasal 30',0,0),('E','Pasal 33',0,0)]),
('twk','UUD 1945','Pembelaan negara merupakan hak dan kewajiban setiap warga negara diatur dalam...','mudah','Pasal 27 ayat (3): Pembelaan negara adalah hak dan kewajiban setiap warga negara','Pasal 27 ayat (3) = hak & kewajiban bela negara.',
[('A','Pasal 27 ayat (3)',5,1),('B','Pasal 30 ayat (1)',0,0),('C','Pasal 28E ayat (3)',0,0),('D','Pasal 31',0,0),('E','Pasal 33',0,0)]),
('twk','UUD 1945','Kebebasan berserikat, berkumpul, dan mengeluarkan pendapat diatur dalam...','sedang','Pasal 28E ayat (3): Setiap orang berhak atas kebebasan berserikat, berkumpul, dan mengeluarkan pendapat','Pasal 28E = kebebasan. ayat (3) = berserikat, berkumpul, berpendapat.',
[('A','Pasal 28E ayat (3)',5,1),('B','Pasal 28A',0,0),('C','Pasal 27 ayat (1)',0,0),('D','Pasal 28G ayat (1)',0,0),('E','Pasal 30',0,0)]),
('twk','UUD 1945','Setiap orang berhak untuk hidup dan mempertahankan hidupnya diatur dalam...','mudah','Pasal 28A UUD 1945: Setiap orang berhak untuk hidup','Pasal 28A = hak hidup. Jangan tertukar dengan 28E (kebebasan).',
[('A','Pasal 28A UUD 1945',5,1),('B','Pasal 27 ayat (2)',0,0),('C','Pasal 28E UUD 1945',0,0),('D','Pasal 28G UUD 1945',0,0),('E','Pasal 29 UUD 1945',0,0)]),
('twk','UUD 1945','MPR terdiri dari anggota-anggota DPR dan DPD diatur dalam...','sangat_mudah','Pasal 2 ayat (1): MPR terdiri atas anggota-anggota DPR dan DPD','Pasal 2 = MPR = DPR + DPD.',
[('A','Pasal 2 ayat (1)',5,1),('B','Pasal 1 ayat (2)',0,0),('C','Pasal 3 ayat (1)',0,0),('D','Pasal 4 ayat (1)',0,0),('E','Pasal 5 ayat (1)',0,0)]),
('twk','UUD 1945','MPR berwenang mengubah UUD dan melantik Presiden diatur dalam...','mudah','Pasal 3: MPR berwenang mengubah dan menetapkan UUD, melantik Presiden/Wapres','Pasal 3 = wewenang MPR.',
[('A','Pasal 3',5,1),('B','Pasal 2',0,0),('C','Pasal 4',0,0),('D','Pasal 5',0,0),('E','Pasal 6',0,0)]),
('twk','UUD 1945','Syarat menjadi Presiden adalah WNI, percaya Tuhan, dan...','mudah','Pasal 6 ayat (1): WNI, percaya Tuhan, dan berusia sekurang-kurangnya 40 tahun','Pasal 6 = syarat Presiden. 40 tahun + percaya Tuhan + WNI.',
[('A','Berusia sekurang-kurangnya 40 tahun',5,1),('B','Memiliki gelar akademik',0,0),('C','Pengalaman 10 tahun di pemerintahan',0,0),('D','Keturunan bangsawan',0,0),('E','Berusia sekurang-kurangnya 35 tahun',0,0)]),
('twk','UUD 1945','Presiden dan Wapres dilantik oleh...','sangat_mudah','Pasal 9: Presiden dan Wapres dilantik oleh MPR','Pasal 9 = pelantikan oleh MPR.',
[('A','MPR',5,1),('B','DPR',0,0),('C','MA',0,0),('D','MK',0,0),('E','Rakyat',0,0)]),

('twk','Sejarah Indonesia','Sumpah Pemuda dilaksanakan pada tanggal...','sangat_mudah','28 Oktober 1928','Hafal: 28 Oktober 1928. Tiga ikrar.',
[('A','28 Oktober 1928',5,1),('B','20 Mei 1908',0,0),('C','17 Agustus 1945',0,0),('D','1 Juni 1945',0,0),('E','23 Agustus 1949',0,0)]),
('twk','Sejarah Indonesia','Sumpah Pemuda memuat ikrar tentang...','mudah','Tanah Air, Bangsa, dan Bahasa Indonesia','Tiga ikrar: Tanah Air, Bangsa, Bahasa.',
[('A','Tanah Air, Bangsa, dan Bahasa Indonesia',5,1),('B','Merdeka atau Mati',0,0),('C','Persatuan Indonesia dan Islam',0,0),('D','Kemerdekaan Republik Indonesia',0,0),('E','Penghapusan feudalisme',0,0)]),
('twk','Sejarah Indonesia','Proklamasi Kemerdekaan Indonesia dilaksanakan pada...','sangat_mudah','17 Agustus 1945','Hafal: 17 Agustus 1945.',
[('A','17 Agustus 1945',5,1),('B','10 November 1945',0,0),('C','1 Juni 1945',0,0),('D','18 Agustus 1945',0,0),('E','20 Mei 1908',0,0)]),
('twk','Sejarah Indonesia','Proklamasi dibacakan oleh...','sangat_mudah','Soekarno dan Mohammad Hatta','Soekarno & Hatta. Jalan Pegangsaan Timur 56.',
[('A','Soekarno dan Mohammad Hatta',5,1),('B','Sutan Sjahrir dan Amir Sjarifuddin',0,0),('C','Agus Salim dan Haji Agus',0,0),('D','Ki Hajar Dewantara dan Tjokroaminoto',0,0),('E','Sukemi dan Cipto Mangunkusumo',0,0)]),
('twk','Sejarah Indonesia','Organisasi pertama pergerakan nasional Indonesia adalah...','mudah','Budi Utomo (20 Mei 1908)','Budi Utomo = Kebangkitan Nasional.',
[('A','Budi Utomo',5,1),('B','Indische Partij',0,0),('C','Sarekat Islam',0,0),('D','PNI',0,0),('E','Partai Komunis Indonesia',0,0)]),
('twk','Sejarah Indonesia','Indische Partij didirikan oleh...','sedang','E.F.E. Douwes Dekker','Douwes Dekker = Indische Partij (1912).',
[('A','E.F.E. Douwes Dekker',5,1),('B','Soekarno',0,0),('C','H.O.S. Tjokroaminoto',0,0),('D','Ki Hajar Dewantara',0,0),('E','Ahmad Dahlan',0,0)]),
('twk','Sejarah Indonesia','KMB yang mengakui kedaulatan RI dilaksanakan...','mudah','23 Agustus 1949','KMB = Konferensi Meja Bundar. Den Haag.',
[('A','23 Agustus 1949',5,1),('B','17 Agustus 1945',0,0),('C','27 Desember 1949',0,0),('D','28 Oktober 1928',0,0),('E','10 November 1945',0,0)]),
('twk','Sejarah Indonesia','Panitia Sembilan dipimpin oleh...','sedang','Mohammad Yamin atau Soekarno (ketua rapat)','Panitia Sembilan merumuskan Piagam Jakarta.',
[('A','Mohammad Yamin',5,1),('B','Agus Salim',0,0),('C','Ki Hajar Dewantara',0,0),('D','Hatta',0,0),('E','Sjahrir',0,0)]),
('twk','Sejarah Indonesia','Piagam Jakarta merupakan rancangan...','mudah','Dasar Negara yang kemudian disempurnakan menjadi Pancasila','Piagam Jakarta = rancangan Pancasila (tujuh kata).',
[('A','Dasar Negara yang disempurnakan menjadi Pancasila',5,1),('B','Proklamasi Kemerdekaan',0,0),('C','Konstitusi RIS',0,0),('D','Pembukaan UUD 1945',0,0),('E','Traktat Linggarjati',0,0)]),
('twk','Sejarah Indonesia','BPUPKI dibentuk pada masa pendudukan...','sangat_mudah','Jepang','BPUPKI dibentuk 29 Maret 1945 saat pendudukan Jepang.',
[('A','Jepang',5,1),('B','Belanda',0,0),('C','Inggris',0,0),('D','Amerika Serikat',0,0),('E','Australia',0,0)]),

('twk','NKRI','Semboyan Bhinneka Tunggal Ika berasal dari kitab...','mudah','Sutasoma karya Mpu Tantular','Mpu Tantular = Sutasoma. Abad XIV.',
[('A','Sutasoma karya Mpu Tantular',5,1),('B','Negarakertagama karya Mpu Prapanca',0,0),('C','Arjunawiwaha karya Mpu Kanwa',0,0),('D','Bharatayuddha karya Mpu Sedah',0,0),('E','Kakawin Ramayana',0,0)]),
('twk','NKRI','Bhinneka Tunggal Ika berarti...','sangat_mudah','Berbeda-beda tetapi tetap satu jua','Arti literal Bhinneka Tunggal Ika.',
[('A','Berbeda-beda tetapi tetap satu jua',5,1),('B','Bersatu dalam keberagaman',0,0),('C','Satu nusa satu bangsa',0,0),('D','Persatuan dalam perbedaan',0,0),('E','Satu untuk semua',0,0)]),
('twk','NKRI','Jumlah suku bangsa di Indonesia kurang lebih...','mudah','1.300 suku bangsa','1.300+ suku, 700+ bahasa daerah.',
[('A','1.300 suku bangsa',5,1),('B','500 suku bangsa',0,0),('C','2.000 suku bangsa',0,0),('D','100 suku bangsa',0,0),('E','3.000 suku bangsa',0,0)]),
('twk','NKRI','Agama yang diakui oleh negara di Indonesia berjumlah...','sangat_mudah','6 agama','6: Islam, Kristen, Katolik, Hindu, Buddha, Konghucu.',
[('A','6 agama',5,1),('B','5 agama',0,0),('C','4 agama',0,0),('D','7 agama',0,0),('E','3 agama',0,0)]),
('twk','NKRI','Pembelaan negara merupakan hak dan kewajiban warga negara diatur dalam...','mudah','Pasal 27 ayat (3) UUD 1945','Pasal 27 ayat (3) = hak & kewajiban bela negara.',
[('A','Pasal 27 ayat (3) UUD 1945',5,1),('B','Pasal 30 UUD 1945',0,0),('C','Pasal 28A UUD 1945',0,0),('D','Pasal 31 UUD 1945',0,0),('E','Pasal 33 UUD 1945',0,0)]),
('twk','NKRI','Bela negara dilaksanakan secara...','mudah','Semesta (melibatkan seluruh komponen bangsa)','Semesta = total defence.',
[('A','Semesta melibatkan seluruh komponen bangsa',5,1),('B','Hanya oleh TNI/Polri',0,0),('C','Hanya oleh pemerintah',0,0),('D','Hanya saat perang',0,0),('E','Hanya oleh pemuda',0,0)]),
('twk','NKRI','Upaya pertahanan dan keamanan negara dilaksanakan melalui...','sedang','Sistem pertahanan dan keamanan rakyat semesta','Sishanta = sistem pertahanan semesta.',
[('A','Sistem Pertahanan dan Keamanan Rakyat Semesta',5,1),('B','Sistem Pertahanan Militer Semata',0,0),('C','Sistem Keamanan Internasional',0,0),('D','Sistem Pertahanan Teritorial',0,0),('E','Sistem Keamanan Sipil',0,0)]),
('twk','NKRI','Mpu Prapanca adalah pengarang kitab...','mudah','Negarakertagama','Mpu Prapanca = Negarakertagama. Mpu Tantular = Sutasoma.',
[('A','Negarakertagama',5,1),('B','Sutasoma',0,0),('C','Arjunawiwaha',0,0),('D','Bharatayuddha',0,0),('E','Kakawin Ramayana',0,0)]),
('twk','NKRI','Total Defence meliputi aspek...','sedang','Militer dan non-militer','Total Defence = pertahanan semesta.',
[('A','Militer dan non-militer',5,1),('B','Militer semata',0,0),('C','Non-militer semata',0,0),('D','Hanya pertahanan laut',0,0),('E','Hanya pertahanan udara',0,0)]),
('twk','NKRI','Sistem pemerintahan Indonesia berdasar atas...','sangat_mudah','Hukum (rule of law)','Pasal 1 ayat (3): Negara berdasar atas hukum.',
[('A','Hukum (rule of law)',5,1),('B','Kekuasaan absolut Presiden',0,0),('C','Kedaulatan rakyat tanpa batas',0,0),('D','Agama tertentu',0,0),('E','Partai politik',0,0)]),
]

inserted = skipped = 0
for s in SOAL:
    h = hashlib.md5(s[2].encode()).hexdigest()
    cur.execute("SELECT id FROM soal WHERE MD5(pertanyaan)=%s LIMIT 1", (h,))
    if cur.fetchone():
        skipped += 1; continue
    cur.execute("""INSERT INTO soal (kategori_ujian_id,jenis_tes,topik,pertanyaan,tingkat_kesulitan,pembahasan,tips_triks,sumber) VALUES (%s,%s,%s,%s,%s,%s,%s,%s)""",
                (1,s[0],s[1],s[2],s[3],s[4],s[5],'AI Knowledge'))
    sid = cur.lastrowid
    for label,teks,bobot,is_kunci in s[6]:
        cur.execute("INSERT INTO opsi_jawaban (soal_id,label,teks_jawaban,bobot_nilai,is_kunci) VALUES (%s,%s,%s,%s,%s)",(sid,label,teks,bobot,is_kunci))
    conn.commit(); inserted += 1; time.sleep(0.15)

print(f"[DONE] TWK: {inserted} inserted, {skipped} skipped")
cur.close(); conn.close()
