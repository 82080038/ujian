#!/usr/bin/env python3
"""Fix materi psikologi - insert untuk topik lowercase wartegg dan epps"""
import mysql.connector

DB = {'host': 'localhost', 'user': 'root', 'password': 'root', 'database': 'db_tryout'}
conn = mysql.connector.connect(**DB)
cur = conn.cursor(dictionary=True)

# Insert materi untuk topik lowercase jika belum ada
materi_fix = [
    ('psikologi', 'wartegg', 'Materi Wartegg', '<h2>Tes Wartegg</h2><p>Melanjutkan gambar dasar menjadi gambar utuh. Mengukur kreativitas dan logika spasial.</p><ul><li>Lanjutkan garis/titik/kurva secara logis</li><li>Jangan terlalu sederhana atau terlalu rumit</li><li>Pastikan gambar bermakna</li></ul>'),
    ('psikologi', 'epps', 'Materi EPPS', '<h2>Tes EPPS</h2><p>Edwards Personal Preference Schedule mengukur kebutuhan psikologis.</p><ul><li>Pilih yang PALING menggambarkan diri Anda</li><li>Tidak ada jawaban benar/salah</li><li>Jawab sesuai diri Anda yang sebenarnya</li></ul>'),
]

for jenis, topik, judul, konten in materi_fix:
    cur.execute("SELECT id FROM materi WHERE topik = %s AND jenis_tes = %s LIMIT 1", (topik, jenis))
    if not cur.fetchone():
        cur.execute("INSERT INTO materi (kategori_ujian_id, judul, topik, jenis_tes, konten_html, tipe, level) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                    (1, judul, topik, jenis, konten, 'artikel', 'dasar'))
        conn.commit()
        print(f"  [INSERTED] {judul} ({jenis}/{topik})")
    else:
        print(f"  [SKIP] {judul} sudah ada")

# Re-check coverage
cur.execute("SELECT DISTINCT topik, jenis_tes FROM soal")
topik_soal = cur.fetchall()
cur.execute("SELECT DISTINCT topik, jenis_tes FROM materi")
topik_materi = {(r['topik'], r['jenis_tes']) for r in cur.fetchall()}

covered = sum(1 for ts in topik_soal if (ts['topik'], ts['jenis_tes']) in topik_materi)
print(f"\n[COVERAGE] {covered}/{len(topik_soal)} topik dengan materi")

for ts in topik_soal:
    if (ts['topik'], ts['jenis_tes']) not in topik_materi:
        print(f"  [MISSING] [{ts['jenis_tes'].upper()}] {ts['topik']}")

if covered == len(topik_soal):
    print("  Semua topik sudah tertutup materi!")

cur.close(); conn.close()
print("\n[DONE]")
