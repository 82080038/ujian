#!/usr/bin/env python3
"""Insert 35 soal TIU unik"""
import time, hashlib, mysql.connector
DB = {'host':'localhost','user':'root','password':'root','database':'db_tryout'}
conn = mysql.connector.connect(**DB); cur = conn.cursor(dictionary=True)

SOAL = [
('tiu','Verbal','Sinonim KONSISTEN adalah...','mudah','Tetap, teguh, konsekuen','Konsisten = tetap. Antonim: berubah-ubah.',
[('A','Tetap',5,1),('B','Berubah-ubah',0,0),('C','Ragu-ragu',0,0),('D','Bimbang',0,0),('E','Fluktuatif',0,0)]),
('tiu','Verbal','Antonim EKSTENSIF adalah...','sedang','Intensif','Ekstensif = luas. Intensif = mendalam/terpusat.',
[('A','Intensif',5,1),('B','Ekspansif',0,0),('C','Luas',0,0),('D','Merata',0,0),('E','Menyeluruh',0,0)]),
('tiu','Verbal','GURU : SEKOLAH = DOKTER : ...','mudah','Rumah Sakit','Tempat bekerja.',
[('A','Rumah Sakit',5,1),('B','Pasien',0,0),('C','Obat',0,0),('D','Periksa',0,0),('E','Stetoskop',0,0)]),
('tiu','Verbal','MAUT : MATI = LAHIR : ...','sedang','Hidup','Maut = kematian. Lawan: hidup.',
[('A','Hidup',5,1),('B','Bayi',0,0),('C','Tumbuh',0,0),('D','Dunia',0,0),('E','Akhirat',0,0)]),
('tiu','Verbal','Sinonim AUTENTIK adalah...','mudah','Asli, genuine','Autentik = asli.',
[('A','Asli',5,1),('B','Palsu',0,0),('C','Tiruan',0,0),('D','Duplikat',0,0),('E','Rekayasa',0,0)]),
('tiu','Verbal','Antonim STAGNAN adalah...','sedang','Progresif, dinamis','Stagnan = tidak bergerak. Lawan: progresif.',
[('A','Progresif',5,1),('B','Statis',0,0),('C','Mandeg',0,0),('D','Diam',0,0),('E','Mati',0,0)]),
('tiu','Verbal','KAKI : SEPATU = KEPALA : ...','mudah','Topi','Alat pelindung.',
[('A','Topi',5,1),('B','Rambut',0,0),('C','Wajah',0,0),('D','Telinga',0,0),('E','Leher',0,0)]),
('tiu','Verbal','PETANI : CANGKUL = PENJAHIT : ...','sedang','Jarum','Alat kerja.',
[('A','Jarum',5,1),('B','Kain',0,0),('C','Benang',0,0),('D','Mesin',0,0),('E','Gunting',0,0)]),
('tiu','Verbal','Sinonim KONSEKUEN adalah...','mudah','Teguh, konsisten','Konsekuen = tetap pada prinsip.',
[('A','Teguh',5,1),('B','Labil',0,0),('C','Ragu',0,0),('D','Bimbang',0,0),('E','Berubah',0,0)]),
('tiu','Verbal','MATA : MELIHAT = TELINGA : ...','sangat_mudah','Mendengar','Fungsi organ.',
[('A','Mendengar',5,1),('B','Bau',0,0),('C','Merasa',0,0),('D','Mencicipi',0,0),('E','Berbicara',0,0)]),

('tiu','Numerik','Deret: 2, 5, 10, 17, 26, ... Berikutnya?','sedang','37 (n²+1)','Pola: 1²+1=2, 2²+1=5, 3²+1=10, 6²+1=37.',
[('A','37',5,1),('B','35',0,0),('C','39',0,0),('D','41',0,0),('E','43',0,0)]),
('tiu','Numerik','12 pekerja selesai dalam 20 hari. Jika ditambah 8 orang, selesai dalam...','sedang','12 hari. 12×20=240. 20 orang butuh 240/20=12.','Orang×Hari = konstan.',
[('A','12 hari',5,1),('B','14 hari',0,0),('C','15 hari',0,0),('D','16 hari',0,0),('E','18 hari',0,0)]),
('tiu','Numerik','6 orang selesai dalam 10 hari. 15 orang selesai dalam...','mudah','4 hari. 6×10=60. 60/15=4.','Perbandingan berbalik nilai.',
[('A','4 hari',5,1),('B','5 hari',0,0),('C','6 hari',0,0),('D','7 hari',0,0),('E','8 hari',0,0)]),
('tiu','Numerik','Barang dijual Rp 240.000 setelah diskon 20%. Harga asli?','sedang','Rp 300.000. 240.000/0.8=300.000.','Harga Asli = Harga Jual / (1-Diskon%).',
[('A','Rp 300.000',5,1),('B','Rp 288.000',0,0),('C','Rp 320.000',0,0),('D','Rp 250.000',0,0),('E','Rp 280.000',0,0)]),
('tiu','Numerik','3/4 tangki = 180 liter. Kapasitas penuh?','mudah','240 liter. 180/(3/4)=240.','Total = Bagian / Fraksi.',
[('A','240 liter',5,1),('B','200 liter',0,0),('C','270 liter',0,0),('D','220 liter',0,0),('E','210 liter',0,0)]),
('tiu','Numerik','Mobil menempuh 240 km dalam 4 jam. Kecepatan rata-rata?','sangat_mudah','60 km/jam. 240/4=60.','v = s/t.',
[('A','60 km/jam',5,1),('B','48 km/jam',0,0),('C','72 km/jam',0,0),('D','56 km/jam',0,0),('E','64 km/jam',0,0)]),
('tiu','Numerik','Pola: 1, 1, 2, 3, 5, 8, 13, ... Berikutnya?','sedang','21. Fibonacci: 8+13=21.','Fibonacci: n=(n-1)+(n-2).',
[('A','21',5,1),('B','20',0,0),('C','22',0,0),('D','18',0,0),('E','19',0,0)]),
('tiu','Numerik','Pola: 3, 6, 12, 24, 48, ... Berikutnya?','mudah','96. Geometri ×2.','Geometri: rasio tetap.',
[('A','96',5,1),('B','72',0,0),('C','108',0,0),('D','84',0,0),('E','120',0,0)]),
('tiu','Numerik','Pola: 5, 8, 13, 20, 29, ... Berikutnya?','sedang','40. Selisih: +3,+5,+7,+9,+11.','Selisih ganjil bertambah 2.',
[('A','40',5,1),('B','38',0,0),('C','42',0,0),('D','36',0,0),('E','44',0,0)]),
('tiu','Numerik','Sebuah proyek dikerjakan 15 orang selama 8 hari. Jika dikerjakan 24 orang, selesai dalam...','sedang','5 hari. 15×8=120. 120/24=5.','Orang×Hari = konstan.',
[('A','5 hari',5,1),('B','6 hari',0,0),('C','4 hari',0,0),('D','7 hari',0,0),('E','8 hari',0,0)]),

('tiu','Logika','Premis 1: Semua PNS wajib pelatihan. Premis 2: Budi adalah PNS. Kesimpulan...','mudah','Budi wajib pelatihan.','Silogisme: Semua A=B, C=A → C=B.',
[('A','Budi wajib pelatihan',5,1),('B','Budi tidak wajib pelatihan',0,0),('C','Semua pelatihan adalah PNS',0,0),('D','Budi bukan PNS',0,0),('E','Tidak dapat disimpulkan',0,0)]),
('tiu','Logika','Andi>Budi, Budi>Cici, Dedi<Cici. Kesimpulan...','sedang','Andi>Dedi. Urutan: Andi>Budi>Cici>Dedi.','Buat diagram urutan.',
[('A','Andi lebih tinggi dari Dedi',5,1),('B','Dedi lebih tinggi dari Budi',0,0),('C','Cici lebih tinggi dari Andi',0,0),('D','Budi lebih pendek dari Dedi',0,0),('E','Tidak ada kesimpulan pasti',0,0)]),
('tiu','Logika','Semua burung bisa terbang. Ayam adalah burung. Maka...','mudah','Ayam bisa terbang.','Silogisme sederhana.',
[('A','Ayam bisa terbang',5,1),('B','Ayam tidak bisa terbang',0,0),('C','Semua yang terbang adalah burung',0,0),('D','Ayam bukan burung',0,0),('E','Tidak dapat disimpulkan',0,0)]),
('tiu','Logika','Jika hujan, jalan licin. Hari ini jalan licin. Maka...','sedang','Mungkin hujan (tidak pasti).','Affirming the consequent = tidak valid secara logis.',
[('A','Mungkin hujan, tetapi tidak pasti',5,1),('B','Pasti hujan',0,0),('C','Tidak hujan',0,0),('D','Jalan selalu licin',0,0),('E','Tidak ada kesimpulan',0,0)]),
('tiu','Logika','Semua mahasiswa rajin. Santi rajin. Maka...','mudah','Santi mungkin mahasiswa (tidak pasti).','Rajin ≠ pasti mahasiswa. Bisa profesi lain juga rajin.',
[('A','Santi mungkin mahasiswa, tidak pasti',5,1),('B','Santi pasti mahasiswa',0,0),('C','Santi bukan mahasiswa',0,0),('D','Semua yang rajin adalah mahasiswa',0,0),('E','Tidak ada kesimpulan',0,0)]),

('tiu','Verbal','Paragraf: Perkembangan teknologi informasi membawa perubahan besar dalam pendidikan. Paragraf membahas tentang...','mudah','Pengaruh teknologi terhadap pendidikan.','Cari kalimat utama.',
[('A','Pengaruh teknologi terhadap pendidikan',5,1),('B','Sejarah teknologi informasi',0,0),('C','Masalah pendidikan',0,0),('D','Teknologi di masa depan',0,0),('E','Manfaat pendidikan',0,0)]),
('tiu','Verbal','Buku : Perpustakaan = Burung : ...','sedang','Sangkar','Tempat tinggal.',
[('A','Sangkar',5,1),('B','Langit',0,0),('C','Sayap',0,0),('D','Pohon',0,0),('E','Awan',0,0)]),
('tiu','Verbal','Api : Panas = Es : ...','sedang','Dingin','Sifat.',
[('A','Dingin',5,1),('B','Cair',0,0),('C','Padat',0,0),('D','Basah',0,0),('E','Kering',0,0)]),
('tiu','Numerik','Pola: 1, 4, 9, 16, 25, ... Berikutnya?','mudah','36. Kuadrat: 1²,2²,3²,4²,5²,6²=36.','Kuadrat sempurna.',
[('A','36',5,1),('B','30',0,0),('C','40',0,0),('D','32',0,0),('E','42',0,0)]),
('tiu','Numerik','Seorang pedagang membeli 50 kg beras Rp 6.000/kg. Dijual Rp 7.500/kg. Keuntungan...','mudah','Rp 75.000. (7.500-6.000)×50 = 1.500×50 = 75.000.','Untung = (Hj-Hb) × jumlah.',
[('A','Rp 75.000',5,1),('B','Rp 60.000',0,0),('C','Rp 90.000',0,0),('D','Rp 50.000',0,0),('E','Rp 100.000',0,0)]),
('tiu','Numerik','Nilai rata-rata dari 8, 12, 15, 5, 10 adalah...','sangat_mudah','10. (8+12+15+5+10)/5 = 50/5 = 10.','Rata-rata = jumlah data / banyak data.',
[('A','10',5,1),('B','9',0,0),('C','11',0,0),('D','8',0,0),('E','12',0,0)]),
('tiu','Numerik','Jika 2x + 5 = 15, maka x = ...','sangat_mudah','5. 2x = 10 → x = 5.','Persamaan linear sederhana.',
[('A','5',5,1),('B','10',0,0),('C','7,5',0,0),('D','4',0,0),('E','6',0,0)]),
('tiu','Numerik','Pecahan terbesar dari 3/4, 2/3, 5/6, 1/2 adalah...','sedang','5/6. Samakan penyebut: 9/12, 8/12, 10/12, 6/12.','Samakan penyebut lalu bandingkan pembilang.',
[('A','5/6',5,1),('B','3/4',0,0),('C','2/3',0,0),('D','1/2',0,0),('E','Sama besar',0,0)]),
('tiu','Logika','Semua kucing adalah mamalia. Hewan A adalah mamalia. Maka...','sedang','Hewan A mungkin kucing (tidak pasti).','Affirming the consequent.',
[('A','Hewan A mungkin kucing',5,1),('B','Hewan A pasti kucing',0,0),('C','Hewan A bukan kucing',0,0),('D','Semua mamalia adalah kucing',0,0),('E','Tidak ada kesimpulan',0,0)]),
('tiu','Logika','Jika belajar, lulus ujian. Rina tidak lulus ujian. Maka...','sedang','Rina tidak belajar (modus tollens).','Modus tollens valid.',
[('A','Rina tidak belajar',5,1),('B','Rina belajar tapi tidak lulus',0,0),('C','Rina lulus tanpa belajar',0,0),('D','Semua yang belajar pasti lulus',0,0),('E','Tidak ada kesimpulan',0,0)]),
('tiu','Verbal','Sinonim ELASTIS adalah...','mudah','Lentur, fleksibel','Elastis = lentur.',
[('A','Lentur',5,1),('B','Kaku',0,0),('C','Tegar',0,0),('D','Keras',0,0),('E','Padat',0,0)]),
('tiu','Verbal','Antonim KOMPLEKS adalah...','mudah','Sederhana, simple','Kompleks = rumit. Lawan: sederhana.',
[('A','Sederhana',5,1),('B','Rumit',0,0),('C','Sulit',0,0),('D','Banyak',0,0),('E','Detail',0,0)]),
('tiu','Numerik','Pola: 10, 8, 13, 11, 16, 14, ... Berikutnya?','sedang','19. Dua pola berselang: -2, +5. 10→13→16→19 (+3) dan 8→11→14 (+3).','Dua pola berselang.',
[('A','19',5,1),('B','17',0,0),('C','21',0,0),('D','18',0,0),('E','20',0,0)]),
('tiu','Numerik','Sebuah toko memberi diskon 25%. Harga setelah diskon Rp 180.000. Harga asli?','sedang','Rp 240.000. 180.000/0.75=240.000.','Harga Asli = Harga Jual / (1-Diskon%).',
[('A','Rp 240.000',5,1),('B','Rp 225.000',0,0),('C','Rp 200.000',0,0),('D','Rp 250.000',0,0),('E','Rp 210.000',0,0)]),
]

inserted = skipped = 0
for s in SOAL:
    h = hashlib.md5(s[2].encode()).hexdigest()
    cur.execute("SELECT id FROM soal WHERE MD5(pertanyaan)=%s LIMIT 1", (h,))
    if cur.fetchone(): skipped += 1; continue
    cur.execute("""INSERT INTO soal (kategori_ujian_id,jenis_tes,topik,pertanyaan,tingkat_kesulitan,pembahasan,tips_triks,sumber) VALUES (%s,%s,%s,%s,%s,%s,%s,%s)""",
                (1,s[0],s[1],s[2],s[3],s[4],s[5],'AI Knowledge'))
    sid = cur.lastrowid
    for label,teks,bobot,is_kunci in s[6]:
        cur.execute("INSERT INTO opsi_jawaban (soal_id,label,teks_jawaban,bobot_nilai,is_kunci) VALUES (%s,%s,%s,%s,%s)",(sid,label,teks,bobot,is_kunci))
    conn.commit(); inserted += 1; time.sleep(0.15)

print(f"[DONE] TIU: {inserted} inserted, {skipped} skipped")
cur.close(); conn.close()
