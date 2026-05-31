#!/usr/bin/env python3
"""Import soal CPNS dari AI Knowledge ke Database - Batch insert"""
import time, hashlib, mysql.connector

DB_CONFIG = {'host': 'localhost', 'user': 'root', 'password': 'root', 'database': 'db_tryout'}
KATEGORI_ID = 1
DELAY_INSERT = 0.3

# AI-rendered soal CPNS (unique, tidak ada di generate_soal_cpns.php)
SOAL_AI = [
    # TWK - UUD 1945 (advanced)
    ('twk','UUD 1945 - Sistem Pemerintahan','Dalam sistem pemerintahan Indonesia, kekuasaan kehakiman dilakukan oleh...','sedang','Pasal 24 UUD 1945: Kekuasaan kehakiman dilakukan oleh sebuah Mahkamah Agung dan badan peradilan yang berada di bawahnya dalam susunan peradilan umum, peradilan agama, peradilan militer, peradilan tata usaha negara, dan Mahkamah Konstitusi.','Hafalkan: Pasal 24 = Kekuasaan kehakiman. Mahkamah Agung + badan peradilan di bawahnya.',
     [('A','Mahkamah Agung dan badan peradilan di bawahnya',5,1),('B','Presiden dan Wakil Presiden',0,0),('C','DPR dan DPD',0,0),('D','MPR sebagai lembaga tertinggi negara',0,0),('E','Komisi Yudisial semata',0,0)]),

    ('twk','UUD 1945 - HAM','Setiap orang berhak untuk hidup serta berhak mempertahankan hidup dan kehidupannya diatur dalam...','mudah','Pasal 28A UUD 1945: Setiap orang berhak untuk hidup serta berhak mempertahankan hidup dan kehidupannya.','Pasal 28A = hak hidup. Jangan tertukar dengan Pasal 27 (warga negara) atau 28E (kebebasan).',
     [('A','Pasal 28A UUD 1945',5,1),('B','Pasal 27 ayat (2) UUD 1945',0,0),('C','Pasal 28E UUD 1945',0,0),('D','Pasal 28G UUD 1945',0,0),('E','Pasal 29 UUD 1945',0,0)]),

    ('twk','UUD 1945 - Ketahanan Nasional','Upaya pertahanan dan keamanan negara dilaksanakan melalui sistem pertahanan dan keamanan rakyat semesta yang diatur dalam...','sedang','Pasal 30 ayat (1) UUD 1945: Tiap-tiap orang berhak dan wajib ikut serta dalam upaya pembelaan negara. Pasal 30 mengatur pertahanan keamanan negara.','Pasal 30 = pertahanan negara. Semesta = melibatkan seluruh komponen bangsa.',
     [('A','Pasal 30 UUD 1945',5,1),('B','Pasal 27 ayat (3) UUD 1945',0,0),('C','Pasal 31 UUD 1945',0,0),('D','Pasal 33 UUD 1945',0,0),('E','Pasal 34 UUD 1945',0,0)]),

    ('twk','Pancasila - Implementasi','Toleransi antar umat beragama yang diterapkan di Indonesia merupakan implementasi dari sila...','mudah','Sila ke-1 Ketuhanan Yang Maha Esa mengharuskan saling menghormati dan toleransi antar pemeluk agama. Ini adalah implementasi nilai ketuhanan dalam kehidupan bermasyarakat.','Sila 1 = Ketuhanan. Implementasi: toleransi, kebebasan beragama, tidak memaksakan keyakinan.',
     [('A','Ketuhanan Yang Maha Esa',5,1),('B','Kemanusiaan yang Adil dan Beradab',0,0),('C','Persatuan Indonesia',0,0),('D','Kerakyatan yang Dipimpin oleh Hikmat Kebijaksanaan',0,0),('E','Keadilan Sosial bagi Seluruh Rakyat Indonesia',0,0)]),

    # TWK - Sejarah & Pahlawan
    ('twk','Sejarah - Sumpah Pemuda','Sumpah Pemuda tahun 1928 berisi ikrar tentang...','mudah','Sumpah Pemuda 28 Oktober 1928 berisi tiga ikrar: Tanah Air, Bangsa, dan Bahasa Indonesia.','Hafalkan 3 ikrar: Tanah Air, Bangsa, Bahasa.',
     [('A','Tanah Air, Bangsa, dan Bahasa Indonesia',5,1),('B','Merdeka atau Mati',0,0),('C','Persatuan Indonesia dan Islam',0,0),('D','Kemerdekaan Republik Indonesia',0,0),('E','Penghapusan feudalisme',0,0)]),

    ('twk','Sejarah - Proklamasi','Proklamasi Kemerdekaan Indonesia dilaksanakan pada tanggal...','sangat_mudah','Proklamasi Kemerdekaan Indonesia dibacakan oleh Soekarno dan Mohammad Hatta pada tanggal 17 Agustus 1945 di Jalan Pegangsaan Timur No. 56 Jakarta.','Hafal: 17 Agustus 1945. Soekarno & Hatta. Jalan Pegangsaan Timur 56.',
     [('A','17 Agustus 1945',5,1),('B','10 November 1945',0,0),('C','1 Juni 1945',0,0),('D','18 Agustus 1945',0,0),('E','20 Mei 1908',0,0)]),

    # TIU - Matematika (berbeda dari existing)
    ('tiu','Numerik - Persentase','Sebuah barang dijual dengan harga Rp 240.000 setelah mendapat diskon 20%. Berapa harga asli barang tersebut?','sedang','Jika diskon 20%, maka harga jual = 80% dari harga asli. Harga asli = 240.000 / 0.8 = 300.000.','Rumus: Harga Asli = Harga Jual / (100% - Diskon%). 240.000 / 0.8 = 300.000.',
     [('A','Rp 300.000',5,1),('B','Rp 288.000',0,0),('C','Rp 320.000',0,0),('D','Rp 250.000',0,0),('E','Rp 280.000',0,0)]),

    ('tiu','Numerik - Pecahan','Jika 3/4 dari sebuah tangki berisi 180 liter air, berapa liter kapasitas penuh tangki tersebut?','mudah','3/4 = 180 liter. Maka 1/4 = 60 liter. Kapasitas penuh = 4/4 = 4 × 60 = 240 liter.','Rumus: Total = Bagian / Fraksi. 180 / (3/4) = 180 × 4/3 = 240.',
     [('A','240 liter',5,1),('B','200 liter',0,0),('C','270 liter',0,0),('D','220 liter',0,0),('E','210 liter',0,0)]),

    ('tiu','Numerik - Kecepatan','Sebuah mobil menempuh jarak 240 km dalam 4 jam. Berapa kecepatan rata-rata mobil tersebut?','sangat_mudah','Kecepatan = Jarak / Waktu = 240 km / 4 jam = 60 km/jam.','Rumus dasar: v = s/t.',
     [('A','60 km/jam',5,1),('B','48 km/jam',0,0),('C','72 km/jam',0,0),('D','56 km/jam',0,0),('E','64 km/jam',0,0)]),

    ('tiu','Logika - Pola Angka','Pola: 1, 1, 2, 3, 5, 8, 13, ... Berikutnya adalah?','sedang','Pola Fibonacci: setiap angka adalah jumlah dua angka sebelumnya. 5+8=13, maka 8+13=21.','Fibonacci: 1, 1, 2, 3, 5, 8, 13, 21, 34...',
     [('A','21',5,1),('B','20',0,0),('C','22',0,0),('D','18',0,0),('E','19',0,0)]),

    ('tiu','Verbal - Penjelasan','Paragraf berikut membahas tentang...\n\n"Perkembangan teknologi informasi dan komunikasi telah membawa perubahan besar dalam berbagai aspek kehidupan manusia, termasuk dalam bidang pendidikan."','mudah','Paragraf tersebut menjelaskan pengaruh perkembangan teknologi informasi dan komunikasi terhadap berbagai aspek kehidupan, khususnya pendidikan.','Cari kalimat utama yang menyatakan topik utama paragraf.',
     [('A','Pengaruh perkembangan teknologi terhadap kehidupan manusia',5,1),('B','Sejarah teknologi informasi',0,0),('C','Masalah dalam bidang pendidikan',0,0),('D','Perkembangan teknologi di masa depan',0,0),('E','Manfaat pendidikan bagi manusia',0,0)]),

    # TKP (berbeda dari existing)
    ('tkp','Integritas - Konflik Kepentingan','Anda mengetahui bahwa atasan Anda menggunakan kendaraan dinas untuk keperluan pribadi secara rutin. Tindakan Anda adalah...','sedang','Integritas mengharuskan kita melaporkan pelanggaran aturan, meskipun dilakukan oleh atasan. Melaporkan ke penyelenggara negara/inspektorat adalah langkah tepat.','Integritas: laporkan pelanggaran, jangan tutupi meski dilakukan atasan. Pilih yang tegas dan prosedural.',
     [('A','Melaporkan ke inspektorat atau penyelenggara negara',5,1),('B','Menganggap itu urusan pribadi atasan',1,0),('C','Menyebarkan ke rekan kerja agar semua tahu',2,0),('D','Meminta bagian dari fasilitas tersebut',3,0),('E','Mengabaikan karena takut kehilangan pekerjaan',4,0)]),

    ('tkp','Pelayanan Publik - Prioritas','Di kantor Anda sedang ramai dengan antrean panjang. Seorang lansia terlihat kelelahan berdiri. Tindakan terbaik Anda adalah...','mudah','Pelayanan publik harus mengutamakan kelompok rentan. Bantu lansia dengan menyediakan tempat duduk atau prioritas pelayanan sesuai ketentuan.','Pelayanan: utamakan kelompok rentan (lansia, ibu hamil, difabel).',
     [('A','Menyediakan kursi dan memberikan prioritas pelayanan sesuai ketentuan',5,1),('B','Mengabaikan karena semua orang sama di muka hukum',1,0),('C','Menyuruh lansia datang lagi besok saat tidak ramai',2,0),('D','Menyarankan lansia meminta bantuan keluarga',3,0),('E','Melanjutkan pelayanan antrean tanpa memperhatikan lansia',4,0)]),

    ('tkp','Komitmen - Deadline','Anda diberi tugas dengan deadline ketat, namun data yang dibutuhkan belum lengkap. Yang Anda lakukan adalah...','sedang','Komitmen organisasi: komunikasikan kendala ke atasan, usahakan selesaikan dengan data tersedia, atau minta perpanjangan dengan alasan yang jelas.','Komitmen: komunikasi + usaha maksimal. Jangan menyerah tanpa mencoba.',
     [('A','Melaporkan kendala ke atasan dan berusaha menyelesaikan dengan data tersedia',5,1),('B','Menunggu data lengkap tanpa melapor ke atasan',1,0),('C','Mengklaim tugas selesai meski data belum lengkap',2,0),('D','Menolak tugas karena deadline tidak realistis',3,0),('E','Mengalihkan tanggung jawab ke rekan kerja',4,0)]),

    ('tkp','Profesionalisme - Kritik','Atasan Anda memberikan kritik keras atas hasil kerja Anda dalam rapat umum. Reaksi terbaik Anda adalah...','sedang','Profesionalisme: dengarkan kritik, evaluasi diri, minta saran perbaikan. Jangan defensif atau menyalahkan orang lain di depan umum.','Profesional: terima kritik dengan lapang dada, jadikan pembelajaran.',
     [('A','Mendengarkan kritik, mencatat, dan meminta saran perbaikan setelah rapat',5,1),('B','Membela diri dan menjelaskan alasannya di depan umum',1,0),('C','Menolak kritik karena merasa sudah bekerja keras',2,0),('D','Menyalahkan rekan kerja yang tidak mendukung',3,0),('E','Meninggalkan rapat karena merasa malu',4,0)]),

    ('tkp','Sosial Budaya - Adaptasi','Anda ditugaskan di daerah dengan budaya yang sangat berbeda dari daerah asal Anda. Sikap Anda adalah...','mudah','Sosial Budaya: menghargai perbedaan, beradaptasi dengan norma setempat, tetap profesional. Ini menunjukkan fleksibilitas dan penghargaan terhadap keberagaman.','Adaptasi: hormati norma lokal, pelajari budaya baru, tetap profesional.',
     [('A','Mempelajari dan menghormati budaya setempat sambil tetap profesional',5,1),('B','Menolak tugas karena tidak nyaman dengan budaya tersebut',1,0),('C','Memaksakan budaya asal Anda ke lingkungan baru',2,0),('D','Mengisolasi diri dan tidak berinteraksi dengan masyarakat',3,0),('E','Menganggap budaya daerah tersebut inferior',4,0)]),
]

class Importer:
    def __init__(self):
        self.conn = mysql.connector.connect(**DB_CONFIG)
        self.cursor = self.conn.cursor(dictionary=True)
        self.inserted = 0
        self.skipped = 0

    def is_duplicate(self, h):
        self.cursor.execute("SELECT id FROM soal WHERE MD5(pertanyaan) = %s LIMIT 1", (h,))
        return self.cursor.fetchone() is not None

    def insert_soal(self, s):
        h = hashlib.md5(s[2].encode()).hexdigest()
        if self.is_duplicate(h):
            self.skipped += 1
            return None
        # Insert soal
        sql = """INSERT INTO soal (kategori_ujian_id, jenis_tes, topik, pertanyaan, tingkat_kesulitan, pembahasan, tips_triks, sumber)
                 VALUES (%s,%s,%s,%s,%s,%s,%s,%s)"""
        self.cursor.execute(sql, (KATEGORI_ID, s[0], s[1], s[2], s[3], s[4], s[5], 'AI Knowledge'))
        sid = self.cursor.lastrowid
        # Insert opsi
        for label, teks, bobot, is_kunci in s[6]:
            self.cursor.execute(
                "INSERT INTO opsi_jawaban (soal_id, label, teks_jawaban, bobot_nilai, is_kunci) VALUES (%s,%s,%s,%s,%s)",
                (sid, label, teks, bobot, is_kunci))
        self.conn.commit()
        self.inserted += 1
        time.sleep(DELAY_INSERT)
        return sid

    def generate_materi(self):
        print("\n[GENERATE] Materi ajar otomatis...")
        materi_list = [
            ('Materi UUD 1945 Sistem Pemerintahan', 'UUD 1945 - Sistem Pemerintahan', 'twk',
             '<h2>Sistem Pemerintahan Indonesia</h2><p>Indonesia menganut sistem pemerintahan presidensial. Kekuasaan negara terbagi: legislatif (DPR/DPD), eksekutif (Presiden), yudikatif (Mahkamah Agung).</p><ul><li>Pasal 1: Bentuk & Kedaulatan</li><li>Pasal 4: Presiden</li><li>Pasal 24: Kekuasaan Kehakiman</li></ul>'),
            ('Materi Pancasila Implementasi', 'Pancasila - Implementasi', 'twk',
             '<h2>Implementasi Pancasila</h2><p>Setiap sila Pancasila memiliki implementasi konkret dalam kehidupan bermasyarakat, berbangsa, dan bernegara.</p><ul><li>Sila 1: Toleransi beragama</li><li>Sila 2: Penghargaan HAM</li><li>Sila 3: Rela berkorban untuk bangsa</li><li>Sila 4: Musyawarah mufakat</li><li>Sila 5: Keadilan sosial</li></ul>'),
            ('Materi Sejarah Kemerdekaan', 'Sejarah - Proklamasi', 'twk',
             '<h2>Sejarah Kemerdekaan Indonesia</h2><p>Indonesia memproklamasikan kemerdekaan pada 17 Agustus 1945. Peristiwa penting: Sumpah Pemuda 1928, BPUPKI/PPKI 1945.</p>'),
            ('Materi Numerik CPNS', 'Numerik - Persentase', 'tiu',
             '<h2>Trik Numerik CPNS</h2><p><strong>Persentase:</strong> Harga Asli = Harga Jual / (1 - Diskon)<br><strong>Pecahan:</strong> Total = Bagian / Fraksi<br><strong>Perbandingan:</strong> Orang1×Hari1 = Orang2×Hari2</p>'),
            ('Materi Logika CPNS', 'Logika - Pola Angka', 'tiu',
             '<h2>Trik Logika CPNS</h2><p><strong>Fibonacci:</strong> n = (n-1) + (n-2)<br><strong>Deret kuadrat:</strong> n² ± k<br><strong>Silogisme:</strong> Semua A=B, C=A → C=B</p>'),
            ('Materi TKP Profesionalisme', 'Profesionalisme', 'tkp',
             '<h2>Profesionalisme PNS</h2><p>Ciri profesionalisme: komitmen, integritas, pelayanan prima, adaptasi, dan kerja sama tim. Selalu pilih jawaban yang solutif dan profesional.</p>'),
        ]
        for judul, topik, jenis, konten in materi_list:
            self.cursor.execute("SELECT id FROM materi WHERE judul=%s LIMIT 1", (judul,))
            if not self.cursor.fetchone():
                self.cursor.execute(
                    "INSERT INTO materi (kategori_ujian_id, judul, topik, jenis_tes, konten_html, tipe, level) VALUES (%s,%s,%s,%s,%s,%s,%s)",
                    (KATEGORI_ID, judul, topik, jenis, konten, 'artikel', 'dasar'))
                self.conn.commit()
                print(f"  [MATERI] {judul}")

    def run(self):
        print("="*60 + "\nIMPORT SOAL CPNS - AI KNOWLEDGE\n" + "="*60)
        print(f"\n[BATCH] Insert {len(SOAL_AI)} soal satu per satu...")
        for i, s in enumerate(SOAL_AI, 1):
            sid = self.insert_soal(s)
            if sid:
                print(f"  [{i}/{len(SOAL_AI)}] INSERTED #{sid} [{s[0].upper()}] {s[1]}")
            else:
                print(f"  [{i}/{len(SOAL_AI)}] SKIPPED (duplikat)")

        self.generate_materi()

        print("\n" + "="*60 + "\nSUMMARY")
        print(f"Soal inserted : {self.inserted}")
        print(f"Soal skipped  : {self.skipped} (duplikat)")
        print("="*60)
        self.cursor.close()
        self.conn.close()
        print("[DONE]")

if __name__ == '__main__':
    Importer().run()
