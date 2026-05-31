#!/usr/bin/env python3
"""Insert materi ajar berkualitas (1 materi per topik besar = banyak soal merujuk)"""
import mysql.connector

DB = {'host': 'localhost', 'user': 'root', 'password': 'root', 'database': 'db_tryout'}
conn = mysql.connector.connect(**DB)
cursor = conn.cursor(dictionary=True)

print("="*60)
print("INSERT MATERI AJAR BERKUALITAS")
print("="*60)

# Hapus materi lama yang auto-generated
cursor.execute("DELETE FROM materi WHERE judul LIKE 'Materi %' OR judul LIKE 'Pengantar %' OR judul LIKE 'Tips %' OR judul LIKE 'Rumus %'")
conn.commit()
print(f"  [CLEAN] Materi lama dihapus")

MATERI = [
('twk','Pancasila','Panduan Lengkap Pancasila untuk CPNS',
"""<h2>1. Pengantar Pancasila</h2><p>Pancasila adalah dasar falsafah dan ideologi bangsa Indonesia. Dalam ujian CPNS, soal Pancasila muncul di Tes Wawasan Kebangsaan (TWK).</p>
<h2>2. Sila Pertama: Ketuhanan Yang Maha Esa</h2><ul><li>Menghormati kebebasan beragama</li><li>Toleransi antar umat beragama</li><li>Tidak memaksakan keyakinan</li></ul><p><strong>Tip:</strong> Pilih jawaban yang menunjukkan toleransi.</p>
<h2>3. Sila Kedua: Kemanusiaan yang Adil dan Beradab</h2><ul><li>Penghargaan HAM</li><li>Tidak diskriminasi</li><li>Saling menghormati</li></ul>
<h2>4. Sila Ketiga: Persatuan Indonesia</h2><ul><li>Rela berkorban untuk bangsa</li><li>Menjaga keutuhan NKRI</li><li>Kepentingan bersama > pribadi</li></ul>
<h2>5. Sila Keempat: Kerakyatan yang Dipimpin oleh Hikmat Kebijaksanaan</h2><ul><li>Demokrasi Pancasila = musyawarah mufakat</li><li>Kekeluargaan dalam pengambilan keputusan</li></ul>
<h2>6. Sila Kelima: Keadilan Sosial bagi Seluruh Rakyat Indonesia</h2><ul><li>Pembangunan merata</li><li>Akses pendidikan & kesehatan merata</li><li>Perlindungan kelompok rentan</li></ul>
<h2>7. Tips Menjawab Soal Pancasila</h2><ul><li>Toleransi/beragama &rarr; Sila 1</li><li>HAM/diskriminasi &rarr; Sila 2</li><li>Korban/bangsa/NKRI &rarr; Sila 3</li><li>Musyawarah/demokrasi &rarr; Sila 4</li><li>Merata/keadilan/sosial &rarr; Sila 5</li></ul>"""),

('twk','UUD 1945','Panduan Lengkap UUD 1945 untuk CPNS',
"""<h2>1. Pasal 1: Bentuk & Kedaulatan</h2><ul><li>Ayat (1): Negara Kesatuan Republik</li><li>Ayat (2): Kedaulatan di tangan rakyat</li><li>Ayat (3): Negara berdasar atas hukum</li></ul>
<h2>2. Pasal 2-3: MPR</h2><ul><li>Pasal 2: MPR = DPR + DPD</li><li>Pasal 3: MPR mengubah UUD, melantik Presiden</li></ul>
<h2>3. Pasal 4-7: Presiden</h2><ul><li>Pasal 4: Presiden memegang kekuasaan pemerintahan</li><li>Pasal 5: Presiden membuat UU bersama DPR</li><li>Pasal 6: Syarat jadi Presiden (WNI, 40+ tahun, percaya Tuhan)</li></ul>
<h2>4. Pasal 24: Kekuasaan Kehakiman</h2><ul><li>Mahkamah Agung + badan peradilan</li><li>Peradilan umum, agama, militer, TUN, MK</li></ul>
<h2>5. Pasal 27-34: Hak & Kewajiban</h2><ul><li>Pasal 27: Warga negara (sama di muka hukum, pekerjaan layak, pembelaan negara)</li><li>Pasal 28A: Hak hidup</li><li>Pasal 28E: Kebebasan beragama, berpendapat, berserikat</li><li>Pasal 28G: Perlindungan diri & keluarga</li><li>Pasal 30: Pertahanan negara</li><li>Pasal 31: Pendidikan wajib & gratis</li></ul>
<h2>6. Tips Hafalan</h2><ul><li>Pasal 1 = Bentuk & Kedaulatan</li><li>Pasal 4 = Presiden</li><li>Pasal 5 = Perundang-undangan</li><li>Pasal 24 = Kehakiman</li><li>Pasal 27 = Warga negara</li><li>Pasal 28A = Hidup | 28E = Kebebasan | 28G = Perlindungan</li></ul>"""),

('twk','Sejarah Indonesia','Sejarah Kemerdekaan & Pahlawan untuk CPNS',
"""<h2>1. Era Kolonialisme</h2><p>Indonesia dijajah Belanda ~350 tahun (1602-1942), dijajah Jepang 1942-1945.</p>
<h2>2. Organisasi Pergerakan</h2><ul><li>Budi Utomo (20 Mei 1908) = hari Kebangkitan Nasional</li><li>Indische Partij (1912) - E.F.E. Douwes Dekker</li><li>PNI (1927) - Soekarno</li></ul>
<h2>3. Sumpah Pemuda (28 Oktober 1928)</h2><ul><li>Tiga ikrar: Tanah Air, Bangsa, Bahasa Indonesia</li><li>Di Jakarta (Rapenburg)</li></ul>
<h2>4. Proklamasi 17 Agustus 1945</h2><ul><li>Dibacakan Soekarno & Mohammad Hatta</li><li>Jalan Pegangsaan Timur No. 56, Jakarta</li></ul>
<h2>5. Konferensi & Perjanjian</h2><ul><li>KMB (23 Agustus 1949) - Belanda akui kedaulatan</li><li>Round Table Conference - Den Haag</li></ul>
<h2>6. Tips Soal Sejarah</h2><ul><li>Tanggal: 20 Mei 1908, 28 Oktober 1928, 17 Agustus 1945, 23 Agustus 1949</li><li>Tokoh: Soekarno, Hatta, Yamin, Douwes Dekker</li><li>Organisasi: Budi Utomo, PNI, BPUPKI, PPKI</li></ul>"""),

('twk','NKRI','NKRI, Bela Negara & Bhinneka Tunggal Ika',
"""<h2>1. Bhinneka Tunggal Ika</h2><ul><li>Dari kitab <strong>Sutasoma</strong> karya <strong>Mpu Tantular</strong> (abad XIV)</li><li>Arti: Berbeda-beda tetapi tetap satu jua</li></ul>
<h2>2. Keberagaman Indonesia</h2><ul><li>1.300+ suku bangsa, 700+ bahasa daerah</li><li>6 agama resmi: Islam, Kristen, Katolik, Hindu, Buddha, Konghucu</li></ul>
<h2>3. Bela Negara (Pasal 27 ayat 3)</h2><ul><li>Hak & kewajiban warga negara</li><li>Cara: jabatan pemerintahan, TNI, swakelola</li></ul>
<h2>4. Tips Soal NKRI</h2><ul><li>Mpu Tantular = Sutasoma</li><li>Mpu Prapanca = Negarakertagama</li><li>Bhinneka Tunggal Ika = keberagaman diterima</li><li>Bela negara = hak + kewajiban (Pasal 27 ayat 3)</li></ul>"""),

('tiu','Verbal','Panduan Kemampuan Verbal untuk TIU CPNS',
"""<h2>1. Sinonim</h2><ul><li>Konsisten = tetap, teguh, konsekuen</li><li>Ekstensif = luas, merata</li><li>Autentik = asli, genuine</li></ul>
<h2>2. Antonim</h2><ul><li>Ekstensif &harr; Intensif</li><li>Stagnan &harr; Progresif</li></ul>
<h2>3. Analogi</h2><ul><li>Tempat bekerja: Guru : Sekolah = Dokter : Rumah Sakit</li><li>Fungsi: Mata : Melihat = Telinga : Mendengar</li></ul>
<h2>4. Penjelasan & Ringkasan</h2><ul><li>Cari kalimat utama (awal/akhir paragraf)</li><li>Identifikasi kata kunci</li></ul>
<h2>5. Tips</h2><ul><li>Cocokkan dengan konteks kalimat</li><li>Hindari jawaban terlalu spesifik atau terlalu umum</li></ul>"""),

('tiu','Numerik','Panduan Kemampuan Numerik untuk TIU CPNS',
"""<h2>1. Deret & Pola</h2><ul><li>Aritmatika: beda tetap (2,5,8,11... +3)</li><li>Geometri: rasio tetap (2,4,8,16... x2)</li><li>Kuadrat: n&sup2; &pm; k (2,5,10,17,26... n&sup2;+1)</li><li>Fibonacci: n=(n-1)+(n-2) (1,1,2,3,5,8,13...)</li></ul>
<h2>2. Aritmatika Sosial</h2><ul><li><strong>Orang &times; Hari = konstan</strong></li><li>Contoh: 12 org &times; 20 hr = 240. 20 org butuh 240/20 = 12 hr</li></ul>
<h2>3. Persentase</h2><ul><li>Harga Asli = Harga Jual / (100% - Diskon%)</li><li>Contoh: Rp 240.000 (diskon 20%) &rarr; 240.000 / 0.8 = Rp 300.000</li></ul>
<h2>4. Pecahan</h2><ul><li>Total = Bagian / Fraksi</li><li>Contoh: 3/4 tangki = 180 liter &rarr; 180 / (3/4) = 240 liter</li></ul>
<h2>5. Kecepatan</h2><ul><li>v = s / t</li><li>Contoh: 240 km / 4 jam = 60 km/jam</li></ul>
<h2>6. Tips Cepat</h2><ul><li>Selisih bertambah 2 &rarr; pola kuadrat</li><li>Kalikan silang untuk perbandingan berbalik nilai</li></ul>"""),

('tiu','Logika','Panduan Logika Penalaran untuk TIU CPNS',
"""<h2>1. Silogisme</h2><ul><li>Pola: Semua A adalah B. C adalah A. Maka C adalah B.</li><li>Premis harus benar dan relevan</li></ul>
<h2>2. Logika Analitis</h2><ul><li>Buat diagram/tabel</li><li>Susun dari kriteria paling pasti</li><li>Contoh: Andi &gt; Budi &gt; Cici &gt; Dedi &rarr; Andi &gt; Dedi</li></ul>
<h2>3. Pola Gambar</h2><ul><li>Cari rotasi, pencerminan, translasi</li><li>Perhatikan jumlah elemen bertambah/berkurang</li></ul>
<h2>4. Tips</h2><ul><li>Baca 1x lalu analisis</li><li>Coret opsi yang jelas salah</li></ul>"""),

('tkp','Profesionalisme','Panduan Profesionalisme PNS untuk TKP CPNS',
"""<h2>1. Komitmen Organisasi</h2><ul><li>Prioritaskan kepentingan organisasi</li><li>Tepat waktu, tidak meninggalkan tugas</li></ul>
<h2>2. Integritas</h2><ul><li>Kejujuran dalam bertindak dan berkata</li><li>Menolak gratifikasi/suap</li><li>Melaporkan pelanggaran</li></ul>
<h2>3. Kerja Sama Tim</h2><ul><li>Musyawarah mufakat</li><li>Tidak menyalahkan rekan di depan umum</li></ul>
<h2>4. Pelayanan</h2><ul><li>Ramah, cepat, solusi-oriented</li><li>Prioritaskan kelompok rentan</li></ul>
<h2>5. Tips TKP</h2><ul><li><strong>Terbaik:</strong> solutif, profesional, empati</li><li><strong>Terburuk:</strong> pasif, defensif, menghindar</li></ul>"""),

('tkp','Integritas','Panduan Integritas & Anti Korupsi untuk TKP CPNS',
"""<h2>1. Integritas PNS</h2><ul><li>Kejujuran, keteladanan, tanggung jawab, konsistensi</li></ul>
<h2>2. Gratifikasi</h2><ul><li>Menolak segala bentuk hadiah dari pihak terkait</li><li>Melaporkan ke inspektorat</li></ul>
<h2>3. Konflik Kepentingan</h2><ul><li>Hindari tabrakan kepentingan pribadi & tugas</li><li>Laporkan jika terpaksa</li></ul>
<h2>4. Tips Soal</h2><ul><li>Ditawari suap &rarr; <strong>tolak + laporkan</strong></li><li>Tahu pelanggaran atasan &rarr; <strong>laporkan ke instansi berwenang</strong></li></ul>"""),

('tkp','Pelayanan Publik','Panduan Pelayanan Publik untuk TKP CPNS',
"""<h2>1. Prinsip Pelayanan</h2><ul><li>Kesederhanaan, kejelasan, keamanan, keterbukaan, keadilan, ketepatan waktu</li></ul>
<h2>2. Menghadapi Warga Marah</h2><ul><li>Tenang, sopan, dengarkan</li><li>Minta maaf jika salah</li><li>Berikan solusi alternatif</li></ul>
<h2>3. Prioritas Pelayanan</h2><ul><li>Lansia, ibu hamil, difabel</li><li>Warga dengan urgensitas tinggi</li></ul>
<h2>4. Tips</h2><ul><li>Pilih: <strong>mendengarkan, tenang, solusi</strong></li><li>Hindari: marah balik, mengabaikan, suruh datang lain hari</li></ul>"""),
]

inserted = 0
for jenis, topik, judul, konten in MATERI:
    cursor.execute("SELECT id FROM materi WHERE judul=%s LIMIT 1", (judul,))
    if cursor.fetchone():
        print(f"  [SKIP] {judul}")
        continue
    cursor.execute(
        "INSERT INTO materi (kategori_ujian_id, judul, topik, jenis_tes, konten_html, tipe, level) VALUES (%s,%s,%s,%s,%s,%s,%s)",
        (1, judul, topik, jenis, konten, 'artikel', 'dasar'))
    conn.commit()
    inserted += 1
    print(f"  [INSERTED] {judul}")

print(f"\n[DONE] {inserted} materi berkualitas inserted")
cursor.close()
conn.close()
