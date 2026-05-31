#!/usr/bin/env python3
"""Generate materi ajar otomatis dari pembahasan soal yang ada"""
import mysql.connector

DB = {'host': 'localhost', 'user': 'root', 'password': 'root', 'database': 'db_tryout'}
conn = mysql.connector.connect(**DB)
cursor = conn.cursor(dictionary=True)

print("=" * 60)
print("GENERATE MATERI AJAR OTOMATIS")
print("=" * 60)

# Ambil topik soal yang belum punya materi
cursor.execute("""
    SELECT DISTINCT s.topik, s.jenis_tes, 
           GROUP_CONCAT(DISTINCT s.pembahasan SEPARATOR '|') as pembahasan_list,
           GROUP_CONCAT(DISTINCT s.tips_triks SEPARATOR '|') as tips_list
    FROM soal s
    LEFT JOIN materi m ON s.topik = m.topik AND s.jenis_tes = m.jenis_tes
    WHERE m.id IS NULL
    GROUP BY s.topik, s.jenis_tes
""")

topik_tanpa_materi = cursor.fetchall()
print(f"\nFound {len(topik_tanpa_materi)} topik without materi")

inserted = 0
for row in topik_tanpa_materi:
    topik = row['topik']
    jenis = row['jenis_tes']
    pembahasan = row['pembahasan_list'] or ''
    tips = row['tips_list'] or ''

    # Generate konten materi dari pembahasan
    konten = f"<h2>Materi {topik}</h2>\n"
    konten += f"<p>Materi pembahasan untuk topik <strong>{topik}</strong> dalam ujian CPNS.</p>\n"

    if pembahasan:
        konten += "<h3>Poin-poin Penting dari Pembahasan Soal:</h3>\n<ul>\n"
        # Ambil beberapa kalimat penting dari pembahasan
        points = [p.strip() for p in pembahasan.split('|') if len(p.strip()) > 20]
        for p in points[:5]:
            # Bersihkan HTML tags
            p_clean = p.replace('<', '&lt;').replace('>', '&gt;')
            konten += f"<li>{p_clean[:200]}</li>\n"
        konten += "</ul>\n"

    if tips:
        konten += "<h3>Tips & Trik:</h3>\n<ul>\n"
        tip_points = [t.strip() for t in tips.split('|') if len(t.strip()) > 10]
        for t in tip_points[:3]:
            t_clean = t.replace('<', '&lt;').replace('>', '&gt;')
            konten += f"<li>{t_clean[:200]}</li>\n"
        konten += "</ul>\n"

    konten += f"<p><em>Pelajari materi ini dengan baik untuk mempersiapkan ujian {jenis.upper()}.</em></p>\n"

    judul = f"Materi {topik} - {jenis.upper()}"

    cursor.execute(
        "INSERT INTO materi (kategori_ujian_id, judul, topik, jenis_tes, konten_html, tipe, level) VALUES (%s,%s,%s,%s,%s,%s,%s)",
        (1, judul, topik, jenis, konten, 'artikel', 'dasar')
    )
    conn.commit()
    inserted += 1
    print(f"  [INSERTED] {judul}")

print(f"\nTotal materi generated: {inserted}")

# Re-verify coverage
print("\n[RE-VERIFY] Coverage after generation:")
cursor.execute("SELECT DISTINCT topik, jenis_tes FROM soal")
topik_soal = cursor.fetchall()
cursor.execute("SELECT DISTINCT topik, jenis_tes FROM materi")
topik_materi = {(r['topik'], r['jenis_tes']) for r in cursor.fetchall()}

covered = sum(1 for ts in topik_soal if (ts['topik'], ts['jenis_tes']) in topik_materi)
print(f"  Topik with materi: {covered}/{len(topik_soal)}")

cursor.close()
conn.close()
print("\n[DONE]")
