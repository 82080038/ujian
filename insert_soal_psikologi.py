#!/usr/bin/env python3
"""Insert soal Psikologi (Wartegg & EPPS) ke database"""
import time, hashlib, mysql.connector

DB = {'host': 'localhost', 'user': 'root', 'password': 'root', 'database': 'db_tryout'}
conn = mysql.connector.connect(**DB)
cur = conn.cursor(dictionary=True)

SOAL = [
# ===== WARTEGG (15 soal) - pola logika/gambar =====
('psikologi','wartegg','Pola berikut: lingkaran, segitiga, lingkaran, segitiga, ... Gambar berikutnya yang tepat adalah?','mudah','Pola berselang: lingkaran - segitiga. Berikutnya = lingkaran.','Cari pola berulang (berselang, bertambah, berputar).',
[('A','Lingkaran',5,1),('B','Segitiga',0,0),('C','Persegi',0,0),('D','Bintang',0,0),('E','Jajaran Genjang',0,0)]),
('psikologi','wartegg','Jika gambar A adalah 1/2 lingkaran, gambar B adalah 1/2 lingkaran yang dilanjutkan menjadi lingkaran penuh, maka 1/2 persegi dilanjutkan menjadi...','sedang','Persegi penuh.','Lanjutkan bentuk dasar menjadi bentuk utuh.',
[('A','Persegi penuh',5,1),('B','1/2 persegi',0,0),('C','Lingkaran',0,0),('D','Segitiga',0,0),('E','Garis lurus',0,0)]),
('psikologi','wartegg','Deret gambar: titik, garis pendek, garis panjang, ... Gambar berikutnya yang logis adalah?','mudah','Pola: panjang bertambah. Berikutnya = lebih panjang/melebar.','Perhatikan transformasi ukuran.',
[('A','Garis lebih panjang atau bidang',5,1),('B','Titik kembali',0,0),('C','Lingkaran',0,0),('D','Segitiga',0,0),('E','Tidak ada lanjutan',0,0)]),
('psikologi','wartegg','Gambar dasar berupa titik di tengah kotak. Kelanjutan yang paling kreatif dan logis adalah?','sedang','Titik diperbesar menjadi lingkaran, atau ditambah titik lain membentuk pola.','Kreativitas + logika: kembangkan elemen dasar.',
[('A','Titik diperbesar menjadi lingkaran atau pola titik',5,1),('B','Biarkan titik saja',0,0),('C','Hapus titik',0,0),('D','Ganti dengan garis melintang',0,0),('E','Warnai kotak tanpa titik',0,0)]),
('psikologi','wartegg','Pola: segitiga kecil, segitiga sedang, segitiga besar, ... Berikutnya?','mudah','Segitiga lebih besar atau bentuk baru.','Pola ukuran bertambah.',
[('A','Segitiga lebih besar / pembentukan objek baru',5,1),('B','Segitiga kecil kembali',0,0),('C','Lingkaran',0,0),('D','Garis',0,0),('E','Kosong',0,0)]),
('psikologi','wartegg','Gambar berisi dua garis paralel. Kelanjutan yang paling tepat adalah...','sedang','Dua garis paralel diperpanjang, diberi penghubung, atau dibentuk jadi jalan/rel.','Kembangkan elemen yang sudah ada secara logis.',
[('A','Garis diperpanjang atau diberi elemen penghubung',5,1),('B','Garis diputus',0,0),('C','Garis dihapus',0,0),('D','Tambah garis acak',0,0),('E','Biarkan kosong',0,0)]),
('psikologi','wartegg','Kotak berisi kurva setengah lingkaran. Kelanjutan paling logis adalah...','mudah','Melengkapi lingkaran penuh.','Lengkapi bentuk dasar.',
[('A','Melengkapi lingkaran penuh',5,1),('B','Ganti jadi segitiga',0,0),('C','Biarkan setengah',0,0),('D','Hapus kurva',0,0),('E','Tambah titik acak',0,0)]),
('psikologi','wartegg','Pola gambar: bentuk geometris berisi 1, 2, 3, 4 titik di dalamnya. Berikutnya berisi...','sedang','5 titik.','Pola numerik dalam gambar.',
[('A','5 titik',5,1),('B','4 titik',0,0),('C','6 titik',0,0),('D','0 titik',0,0),('E','Tidak ada pola',0,0)]),
('psikologi','wartegg','Gambar dasar: zig-zag (sawtooth). Kelanjutan yang paling kreatif namun koheren adalah...','sedang','Lanjutkan zig-zag membentuk gelombang atau pola berulang.','Konsistensi pola.',
[('A','Lanjutkan zig-zag menjadi gelombang / pola berulang',5,1),('B','Ganti jadi garis lurus',0,0),('C','Hapus zig-zag',0,0),('D','Tambah lingkaran',0,0),('E','Warnai saja',0,0)]),
('psikologi','wartegg','Kotak berisi titik di pojok kiri atas. Titik bergerak ke kanan bawah membentuk garis diagonal. Gambar berikutnya seharusnya...','sedang','Lanjutkan diagonal atau bentuk segitiga dari titik-titik.','Pergerakan/rotasi elemen.',
[('A','Lanjutkan diagonal / bentuk segitiga dari titik',5,1),('B','Titik kembali ke kiri atas',0,0),('C','Hapus titik',0,0),('D','Tambah lingkaran besar',0,0),('E','Biarkan kosong',0,0)]),
('psikologi','wartegg','Pola berselang: kotak berisi garis vertikal, kotak berisi garis horizontal, ... Berikutnya?','mudah','Garis vertikal.','Pola berselang.',
[('A','Garis vertikal',5,1),('B','Garis horizontal',0,0),('C','Lingkaran',0,0),('D','Tidak ada pola',0,0),('E','Garis miring',0,0)]),
('psikologi','wartegg','Gambar berisi bentuk seperti huruf L. Kelanjutan paling logis adalah...','mudah','Lengkapi jadi persegi panjang / persegi.','Lengkapi bentuk dasar.',
[('A','Lengkapi jadi persegi / persegi panjang',5,1),('B','Hapus L',0,0),('C','Tambah lingkaran',0,0),('D','Ganti jadi T',0,0),('E','Warnai L saja',0,0)]),
('psikologi','wartegg','Pola: kotak berisi 1 lingkaran, 2 lingkaran, 3 lingkaran, ... Berikutnya berisi?','mudah','4 lingkaran.','Pola bertambah 1.',
[('A','4 lingkaran',5,1),('B','3 lingkaran',0,0),('C','5 lingkaran',0,0),('D','1 lingkaran',0,0),('E','0 lingkaran',0,0)]),
('psikologi','wartegg','Gambar dasar: busur / lengkungan. Kelanjutan paling kreatif adalah...','sedang','Lengkapi jadi lingkaran / bulan / bentuk organik.','Kreativitas + logika.',
[('A','Lengkapi jadi lingkaran / bulan / bentuk organik',5,1),('B','Biarkan busur saja',0,0),('C','Ganti jadi garis',0,0),('D','Tambah persegi',0,0),('E','Hapus busur',0,0)]),
('psikologi','wartegg','Kotak berisi tanda silang (X). Kelanjutan yang paling tepat adalah...','sedang','Tambah elemen di sekitar X membentuk bintang / kupu-kupu / pola simetris.','Simetri dan pengembangan.',
[('A','Kembangkan X menjadi bintang / kupu-kupu / pola simetris',5,1),('B','Hapus X',0,0),('C','Ganti jadi lingkaran',0,0),('D','Biarkan X saja',0,0),('E','Warnai X tanpa tambahan',0,0)]),

# ===== EPPS (20 soal) - pilih pernyataan yang paling menggambarkan diri =====
('psikologi','epps','Pernyataan mana yang PALING menggambarkan diri Anda?\nA. Saya suka menjadi pemimpin dalam kelompok.\nB. Saya lebih nyaman mengikuti daripada memimpin.','mudah','Tidak ada jawaban benar/salah. Pilihan A menunjukkan kebutuhan dominance/achievement. Pilihan B menunjukkan kebutuhan deference/affiliation.','EPPS: pilih yang PALING sesuai dengan diri Anda, bukan yang "seharusnya".',
[('A','Saya suka menjadi pemimpin dalam kelompok',5,1),('B','Saya lebih nyaman mengikuti daripada memimpin',4,0),('C','Keduanya sama',3,0),('D','Tidak yakin',2,0),('E','Tidak ada yang sesuai',1,0)]),
('psikologi','epps','Pernyataan mana yang PALING menggambarkan diri Anda?\nA. Saya merasa puas ketika dapat menyelesaikan tugas yang sulit.\nB. Saya merasa lebih bahagia saat bersama teman-teman dekat.','mudah','A = need achievement. B = need affiliation.','Pilih yang PALING sesuai.',
[('A','Saya merasa puas ketika dapat menyelesaikan tugas yang sulit',5,1),('B','Saya merasa lebih bahagia saat bersama teman-teman dekat',4,0),('C','Keduanya sama',3,0),('D','Tidak yakin',2,0),('E','Tidak ada yang sesuai',1,0)]),
('psikologi','epps','Pernyataan mana yang PALING menggambarkan diri Anda?\nA. Saya suka bekerja secara mandiri tanpa banyak campur tangan orang lain.\nB. Saya suka bekerja dalam tim dengan arahan yang jelas dari atasan.','mudah','A = need autonomy. B = need deference/structure.','Pilih yang PALING sesuai.',
[('A','Saya suka bekerja secara mandiri tanpa banyak campur tangan',5,1),('B','Saya suka bekerja dalam tim dengan arahan yang jelas',4,0),('C','Keduanya sama',3,0),('D','Tidak yakin',2,0),('E','Tidak ada yang sesuai',1,0)]),
('psikologi','epps','Pernyataan mana yang PALING menggambarkan diri Anda?\nA. Saya suka membantu orang lain meskipun tidak diminta.\nB. Saya lebih fokus pada tugas saya sendiri.','mudah','A = need nurturance. B = need achievement/introversion.','Pilih yang PALING sesuai.',
[('A','Saya suka membantu orang lain meskipun tidak diminta',5,1),('B','Saya lebih fokus pada tugas saya sendiri',4,0),('C','Keduanya sama',3,0),('D','Tidak yakin',2,0),('E','Tidak ada yang sesuai',1,0)]),
('psikologi','epps','Pernyataan mana yang PALING menggambarkan diri Anda?\nA. Saya suka mencoba hal-hal baru dan berbeda.\nB. Saya lebih nyaman dengan rutinitas yang sudah familiar.','mudah','A = need change/variety. B = need order/endurance.','Pilih yang PALING sesuai.',
[('A','Saya suka mencoba hal-hal baru dan berbeda',5,1),('B','Saya lebih nyaman dengan rutinitas yang sudah familiar',4,0),('C','Keduanya sama',3,0),('D','Tidak yakin',2,0),('E','Tidak ada yang sesuai',1,0)]),
('psikologi','epps','Pernyataan mana yang PALING menggambarkan diri Anda?\nA. Saya mudah merasa kasihan pada orang yang sedang kesulitan.\nB. Saya cenderung melihat masalah secara objektif tanpa emosi.','mudah','A = need nurturance. B = need aggression/objectivity.','Pilih yang PALING sesuai.',
[('A','Saya mudah merasa kasihan pada orang yang sedang kesulitan',5,1),('B','Saya cenderung melihat masalah secara objektif tanpa emosi',4,0),('C','Keduanya sama',3,0),('D','Tidak yakin',2,0),('E','Tidak ada yang sesuai',1,0)]),
('psikologi','epps','Pernyataan mana yang PALING menggambarkan diri Anda?\nA. Saya suka berdebat untuk membuktikan pendapat saya benar.\nB. Saya suka menghindari konflik dan mencari jalan damai.','mudah','A = need aggression. B = need abasement/harmavoidance.','Pilih yang PALING sesuai.',
[('A','Saya suka berdebat untuk membuktikan pendapat saya benar',5,1),('B','Saya suka menghindari konflik dan mencari jalan damai',4,0),('C','Keduanya sama',3,0),('D','Tidak yakin',2,0),('E','Tidak ada yang sesuai',1,0)]),
('psikologi','epps','Pernyataan mana yang PALING menggambarkan diri Anda?\nA. Saya suka memperoleh pengakuan dan penghargaan atas prestasi saya.\nB. Saya merasa cukup puas tanpa perlu pengakuan orang lain.','mudah','A = need exhibition/recognition. B = need intraception/self-sufficiency.','Pilih yang PALING sesuai.',
[('A','Saya suka memperoleh pengakuan dan penghargaan atas prestasi saya',5,1),('B','Saya merasa cukup puas tanpa perlu pengakuan orang lain',4,0),('C','Keduanya sama',3,0),('D','Tidak yakin',2,0),('E','Tidak ada yang sesuai',1,0)]),
('psikologi','epps','Pernyataan mana yang PALING menggambarkan diri Anda?\nA. Saya suka mengatur dan mengorganisir pekerjaan orang lain.\nB. Saya lebih suka diberi tugas yang jelas daripada menyusun tugas untuk orang lain.','mudah','A = need dominance/organization. B = need deference/clarity.','Pilih yang PALING sesuai.',
[('A','Saya suka mengatur dan mengorganisir pekerjaan orang lain',5,1),('B','Saya lebih suka diberi tugas yang jelas',4,0),('C','Keduanya sama',3,0),('D','Tidak yakin',2,0),('E','Tidak ada yang sesuai',1,0)]),
('psikologi','epps','Pernyataan mana yang PALING menggambarkan diri Anda?\nA. Saya merasa lebih bersemangat saat ada tantangan baru.\nB. Saya merasa lebih tenang saat semuanya terencana dengan baik.','mudah','A = need change/variety. B = need order/security.','Pilih yang PALING sesuai.',
[('A','Saya merasa lebih bersemangat saat ada tantangan baru',5,1),('B','Saya merasa lebih tenang saat semuanya terencana dengan baik',4,0),('C','Keduanya sama',3,0),('D','Tidak yakin',2,0),('E','Tidak ada yang sesuai',1,0)]),
('psikologi','epps','Pernyataan mana yang PALING menggambarkan diri Anda?\nA. Saya sering memikirkan arti hidup dan perasaan orang lain.\nB. Saya lebih fokus pada fakta dan hasil yang konkret.','mudah','A = need intraception. B = need achievement/pragmatism.','Pilih yang PALING sesuai.',
[('A','Saya sering memikirkan arti hidup dan perasaan orang lain',5,1),('B','Saya lebih fokus pada fakta dan hasil yang konkret',4,0),('C','Keduanya sama',3,0),('D','Tidak yakin',2,0),('E','Tidak ada yang sesuai',1,0)]),
('psikologi','epps','Pernyataan mana yang PALING menggambarkan diri Anda?\nA. Saya suka berada di tempat ramai dengan banyak orang.\nB. Saya lebih nyaman di tempat sepi dengan sedikit teman.','mudah','A = need affiliation/sociability. B = need solitude.','Pilih yang PALING sesuai.',
[('A','Saya suka berada di tempat ramai dengan banyak orang',5,1),('B','Saya lebih nyaman di tempat sepi dengan sedikit teman',4,0),('C','Keduanya sama',3,0),('D','Tidak yakin',2,0),('E','Tidak ada yang sesuai',1,0)]),
('psikologi','epps','Pernyataan mana yang PALING menggambarkan diri Anda?\nA. Saya merasa bersalah jika tidak dapat memenuhi harapan orang lain.\nB. Saya tidak terlalu memikirkan apa kata orang lain terhadap saya.','mudah','A = need abasement. B = need autonomy/self-sufficiency.','Pilih yang PALING sesuai.',
[('A','Saya merasa bersalah jika tidak dapat memenuhi harapan orang lain',5,1),('B','Saya tidak terlalu memikirkan apa kata orang lain',4,0),('C','Keduanya sama',3,0),('D','Tidak yakin',2,0),('E','Tidak ada yang sesuai',1,0)]),
('psikologi','epps','Pernyataan mana yang PALING menggambarkan diri Anda?\nA. Saya suka memperbaiki atau membangun sesuatu dengan tangan saya.\nB. Saya lebih suka menggunakan otak daripada tangan.','mudah','A = need construction/sensation. B = need intraception/theoretical.','Pilih yang PALING sesuai.',
[('A','Saya suka memperbaiki atau membangun sesuatu dengan tangan saya',5,1),('B','Saya lebih suka menggunakan otak daripada tangan',4,0),('C','Keduanya sama',3,0),('D','Tidak yakin',2,0),('E','Tidak ada yang sesuai',1,0)]),
('psikologi','epps','Pernyataan mana yang PALING menggambarkan diri Anda?\nA. Saya suka menjadi pusat perhatian dalam pesta atau acara.\nB. Saya lebih suka diam dan mengamati dari pinggir.','mudah','A = need exhibition/sociability. B = need intraception/introversion.','Pilih yang PALING sesuai.',
[('A','Saya suka menjadi pusat perhatian dalam pesta atau acara',5,1),('B','Saya lebih suka diam dan mengamati dari pinggir',4,0),('C','Keduanya sama',3,0),('D','Tidak yakin',2,0),('E','Tidak ada yang sesuai',1,0)]),
('psikologi','epps','Pernyataan mana yang PALING menggambarkan diri Anda?\nA. Saya mudah marah ketika ada yang meremehkan kemampuan saya.\nB. Saya cenderung mengabaikan penilaian negatif orang lain.','mudah','A = need aggression/defensiveness. B = need autonomy/security.','Pilih yang PALING sesuai.',
[('A','Saya mudah marah ketika ada yang meremehkan kemampuan saya',5,1),('B','Saya cenderung mengabaikan penilaian negatif orang lain',4,0),('C','Keduanya sama',3,0),('D','Tidak yakin',2,0),('E','Tidak ada yang sesuai',1,0)]),
('psikologi','epps','Pernyataan mana yang PALING menggambarkan diri Anda?\nA. Saya suka mempelajari hal-hal yang kompleks dan memerlukan analisis mendalam.\nB. Saya suka belajar hal-hal praktis yang langsung bisa diterapkan.','mudah','A = need understanding/complexity. B = need practicality/application.','Pilih yang PALING sesuai.',
[('A','Saya suka mempelajari hal-hal yang kompleks dan memerlukan analisis mendalam',5,1),('B','Saya suka belajar hal-hal praktis yang langsung bisa diterapkan',4,0),('C','Keduanya sama',3,0),('D','Tidak yakin',2,0),('E','Tidak ada yang sesuai',1,0)]),
('psikologi','epps','Pernyataan mana yang PALING menggambarkan diri Anda?\nA. Saya suka menjaga lingkungan tetap rapi dan teratur.\nB. Saya tidak keberatan dengan sedikit kekacauan selalu.','mudah','A = need order. B = need change/variety.','Pilih yang PALING sesuai.',
[('A','Saya suka menjaga lingkungan tetap rapi dan teratur',5,1),('B','Saya tidak keberatan dengan sedikit kekacauan',4,0),('C','Keduanya sama',3,0),('D','Tidak yakin',2,0),('E','Tidak ada yang sesuai',1,0)]),
('psikologi','epps','Pernyataan mana yang PALING menggambarkan diri Anda?\nA. Saya suka mengambil risiko dalam pengambilan keputusan.\nB. Saya suka mempertimbangkan semua kemungkinan sebelum memutuskan.','sedang','A = need change/risk. B = need caution/endurance.','Pilih yang PALING sesuai.',
[('A','Saya suka mengambil risiko dalam pengambilan keputusan',5,1),('B','Saya suka mempertimbangkan semua kemungkinan sebelum memutuskan',4,0),('C','Keduanya sama',3,0),('D','Tidak yakin',2,0),('E','Tidak ada yang sesuai',1,0)]),
('psikologi','epps','Pernyataan mana yang PALING menggambarkan diri Anda?\nA. Saya merasa nyaman menjadi pengikut yang setia dalam tim.\nB. Saya selalu ingin membuktikan bahwa saya lebih baik dari orang lain.','sedang','A = need deference/teamwork. B = need dominance/competition.','Pilih yang PALING sesuai.',
[('A','Saya merasa nyaman menjadi pengikut yang setia dalam tim',5,1),('B','Saya selalu ingin membuktikan bahwa saya lebih baik dari orang lain',4,0),('C','Keduanya sama',3,0),('D','Tidak yakin',2,0),('E','Tidak ada yang sesuai',1,0)]),
]

inserted = skipped = 0
for s in SOAL:
    h = hashlib.md5(s[2].encode()).hexdigest()
    cur.execute("SELECT id FROM soal WHERE MD5(pertanyaan)=%s LIMIT 1", (h,))
    if cur.fetchone():
        skipped += 1
        continue
    cur.execute("""INSERT INTO soal (kategori_ujian_id,jenis_tes,topik,pertanyaan,tingkat_kesulitan,pembahasan,tips_triks,sumber) VALUES (%s,%s,%s,%s,%s,%s,%s,%s)""",
                (1,s[0],s[1],s[2],s[3],s[4],s[5],'AI Knowledge - Psikologi'))
    sid = cur.lastrowid
    for label,teks,bobot,is_kunci in s[6]:
        cur.execute("INSERT INTO opsi_jawaban (soal_id,label,teks_jawaban,bobot_nilai,is_kunci) VALUES (%s,%s,%s,%s,%s)",(sid,label,teks,bobot,is_kunci))
    conn.commit(); inserted += 1; time.sleep(0.15)

print(f"[DONE] Psikologi: {inserted} inserted, {skipped} skipped")
cur.close(); conn.close()
