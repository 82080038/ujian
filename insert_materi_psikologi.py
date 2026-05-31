#!/usr/bin/env python3
"""Insert materi ajar Psikologi"""
import mysql.connector
DB = {'host':'localhost','user':'root','password':'root','database':'db_tryout'}
conn = mysql.connector.connect(**DB); cur = conn.cursor(dictionary=True)

MATERI = [
('psikologi','Kraepelin','Panduan Tes Kraepelin (Pauli)',
"""<h2>1. Pengertian Tes Kraepelin</h2><p>Tes Kraepelin atau Pauli adalah tes psikologi yang mengukur <strong>ketahanan kerja</strong>, <strong>ketelitian</strong>, dan <strong>konsentrasi</strong>. Peserta diminta menjumlahkan dua angka berdampingan secara terus-menerus dalam waktu tertentu.</p>
<h2>2. Teknik Mengerjakan</h2><ul><li>Jumlahkan angka ke-1 dan ke-2, tulis hasilnya di bawah</li><li>Lanjutkan angka ke-2 dan ke-3, dan seterusnya</li><li>Jika hasil > 9, tulis <strong>angka satuan saja</strong> (contoh: 12 = 2)</li></ul>
<h2>3. Tips Lulus Kraepelin</h2><ul><li>Konsentrasi pada baris yang sedang dikerjakan</li><li>Jangan melihat ke bawah terlalu jauh</li><li>Latihan rutin untuk meningkatkan kecepatan</li><li>Tenangkan pikiran sebelum tes</li></ul>"""),

('psikologi','Wartegg','Panduan Tes Wartegg',
"""<h2>1. Pengertian Tes Wartegg</h2><p>Tes Wartegg adalah tes proyeksi dan logika visual. Peserta melanjutkan gambar dasar (garis, titik, kurva) menjadi gambar utuh yang bermakna.</p>
<h2>2. Aspek yang Diukur</h2><ul><li>Daya imaginasi dan kreativitas</li><li>Logika spasial</li><li>Kemampuan problem solving</li><li>Kepribadian (melalui interpretasi gambar)</li></ul>
<h2>3. Tips Menjawab</h2><ul><li>Lanjutkan gambar secara logis dan kreatif</li><li>Jangan terlalu sederhana (hanya garis) atau terlalu rumit</li><li>Pastikan gambar Anda memiliki makna/ bentuk yang jelas</li></ul>"""),

('psikologi','EPPS','Panduan Tes EPPS',
"""<h2>1. Pengertian EPPS</h2><p>Edwards Personal Preference Schedule (EPPS) mengukur <strong>kebutuhan psikologis</strong>: achievement, affiliation, autonomy, dominance, nurturance, order, dan lainnya.</p>
<h2>2. Cara Mengerjakan</h2><p>Setiap soal berisi dua pernyataan. Pilih yang <strong>PALING</strong> menggambarkan diri Anda. Tidak ada jawaban benar atau salah.</p>
<h2>3. Tips</h2><ul><li>Jawab sesuai diri Anda yang sebenarnya, bukan yang "seharusnya"</li><li>Jangan terlalu lama memikirkan satu soal</li><li>Konsistensi jawaban menunjukkan kepribadian yang stabil</li></ul>"""),
]

inserted = 0
for jenis,topik,judul,konten in MATERI:
    cur.execute("SELECT id FROM materi WHERE judul=%s LIMIT 1", (judul,))
    if cur.fetchone(): continue
    cur.execute("INSERT INTO materi (kategori_ujian_id,judul,topik,jenis_tes,konten_html,tipe,level) VALUES (%s,%s,%s,%s,%s,%s,%s)",(1,judul,topik,jenis,konten,'artikel','dasar'))
    conn.commit(); inserted += 1

print(f"[DONE] {inserted} materi psikologi inserted")
cur.close(); conn.close()
