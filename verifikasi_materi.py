#!/usr/bin/env python3
"""Verifikasi materi ajar terhadap soal CPNS"""
import mysql.connector

DB = {'host': 'localhost', 'user': 'root', 'password': 'root', 'database': 'db_tryout'}
conn = mysql.connector.connect(**DB)
cursor = conn.cursor(dictionary=True)

print("=" * 60)
print("VERIFIKASI MATERI AJAR vs SOAL CPNS")
print("=" * 60)

# 1. Statistik soal per jenis
print("\n[1] STATISTIK SOAL PER JENIS:")
cursor.execute("SELECT jenis_tes, COUNT(*) as total FROM soal GROUP BY jenis_tes")
for row in cursor.fetchall():
    print(f"  {row['jenis_tes'].upper()}: {row['total']} soal")

# 2. Statistik soal per topik
print("\n[2] TOPIK SOAL (TOP 10):")
cursor.execute("SELECT topik, COUNT(*) as total FROM soal GROUP BY topik ORDER BY total DESC LIMIT 10")
for row in cursor.fetchall():
    print(f"  {row['topik']}: {row['total']} soal")

# 3. Materi ajar yang ada
print("\n[3] MATERI AJAR YANG TERSEDIA:")
cursor.execute("SELECT judul, topik, jenis_tes, tipe FROM materi ORDER BY jenis_tes, topik")
materi_list = cursor.fetchall()
for row in materi_list:
    print(f"  [{row['jenis_tes'].upper()}] {row['judul']} ({row['tipe']})")

# 4. Coverage: Apakah setiap topik soal punya materi?
print("\n[4] COVERAGE MATERI vs TOPIK SOAL:")
cursor.execute("SELECT DISTINCT topik, jenis_tes FROM soal")
topik_soal = cursor.fetchall()
cursor.execute("SELECT DISTINCT topik, jenis_tes FROM materi")
topik_materi = {(r['topik'], r['jenis_tes']) for r in cursor.fetchall()}

covered = 0
uncovered = []
for ts in topik_soal:
    if (ts['topik'], ts['jenis_tes']) in topik_materi:
        covered += 1
    else:
        uncovered.append(f"  [{ts['jenis_tes'].upper()}] {ts['topik']}")

print(f"  Topik soal dengan materi: {covered}/{len(topik_soal)}")
if uncovered:
    print(f"  Topik soal TANPA materi ({len(uncovered)}):")
    for u in uncovered:
        print(u)
else:
    print("  Semua topik soal sudah memiliki materi ajar!")

# 5. Rekomendasi materi yang perlu dibuat
print("\n[5] REKOMENDASI MATERI YANG PERLU DIBUAT:")
if uncovered:
    for u in uncovered[:5]:
        print(f"  -> Buat materi untuk: {u}")
else:
    print("  Tidak ada rekomendasi. Semua topik sudah tertutup materi.")

# 6. Verifikasi soal memiliki opsi lengkap
print("\n[6] VERIFIKASI KELEGENKAPAN OPSI SOAL:")
cursor.execute("""
    SELECT s.id, s.jenis_tes, s.topik, COUNT(o.id) as jumlah_opsi
    FROM soal s LEFT JOIN opsi_jawaban o ON s.id = o.soal_id
    GROUP BY s.id HAVING jumlah_opsi < 5
""")
incomplete = cursor.fetchall()
if incomplete:
    print(f"  [WARNING] {len(incomplete)} soal memiliki opsi < 5!")
    for row in incomplete:
        print(f"    Soal #{row['id']} [{row['jenis_tes'].upper()}] {row['topik']} - opsi: {row['jumlah_opsi']}")
else:
    print("  Semua soal memiliki 5 opsi jawaban (lengkap).")

print("\n" + "=" * 60)
print("VERIFIKASI SELESAI")
print("=" * 60)

cursor.close()
conn.close()
