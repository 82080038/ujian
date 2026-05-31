#!/usr/bin/env python3
"""
Scraper Soal CPNS + Auto Materi Ajar
- Scraping dari multiple sources
- Insert ke MySQL dengan rate limit
- Hindari duplikat
- Generate materi ajar otomatis
"""
import requests
import time
import hashlib
import re
from bs4 import BeautifulSoup
import mysql.connector
from mysql.connector import Error

# DB Config
DB_CONFIG = {
    'host': 'localhost',
    'user': 'root',
    'password': 'root',
    'database': 'db_tryout'
}

# Kategori ID default (CPNS)
KATEGORI_ID = 1

# Rate limit: delay antar request (detik)
DELAY_REQUEST = 2
DELAY_INSERT = 0.5

# Sumber soal CPNS (URL yang bisa di-scrape)
SOURCES = [
    {
        'url': 'https://www.kitalulus.com/blog/info-cpns/contoh-soal-skd-cpns-tiu-twk-tkp/',
        'jenis': 'tiu',
        'topik_default': 'Numerik - Aritmatika'
    },
    {
        'url': 'https://blog.skillacademy.com/soal-latihan-cpns-dan-pembahasan',
        'jenis': 'twk',
        'topik_default': 'Pancasila - Sila'
    },
    {
        'url': 'https://mamikos.com/info/latihan-soal-cpns-dan-jawabannya-kry/',
        'jenis': 'tiu',
        'topik_default': 'Verbal - Analogi'
    },
    {
        'url': 'https://tryout.id/berita/latihan-soal-cpns-terbaru-2025-twk-tiu-dan-tkp-lengkap-dengan-pembahasan-bddg768j91bh',
        'jenis': 'tkp',
        'topik_default': 'Profesionalisme'
    }
]

HEADERS = {
    'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'
}

class CPNSScraper:
    def __init__(self):
        self.conn = None
        self.cursor = None
        self.soal_inserted = 0
        self.soal_skipped = 0
        self.materi_inserted = 0
        self.connect_db()

    def connect_db(self):
        try:
            self.conn = mysql.connector.connect(**DB_CONFIG)
            self.cursor = self.conn.cursor(dictionary=True)
            print("[DB] Connected to db_tryout")
        except Error as e:
            print(f"[DB ERROR] {e}")
            exit(1)

    def is_duplicate(self, pertanyaan_hash):
        """Cek apakah soal sudah ada berdasarkan hash pertanyaan."""
        query = "SELECT id FROM soal WHERE MD5(pertanyaan) = %s LIMIT 1"
        self.cursor.execute(query, (pertanyaan_hash,))
        return self.cursor.fetchone() is not None

    def insert_soal(self, jenis, topik, pertanyaan, pembahasan, tips, opsi_list, kesulitan='sedang'):
        """Insert soal + opsi ke DB."""
        # Hash pertanyaan untuk cek duplikat
        pertanyaan_hash = hashlib.md5(pertanyaan.encode()).hexdigest()
        if self.is_duplicate(pertanyaan_hash):
            self.soal_skipped += 1
            return None

        # Insert soal
        soal_sql = """
            INSERT INTO soal (kategori_ujian_id, jenis_tes, topik, pertanyaan, 
            tingkat_kesulitan, pembahasan, tips_triks, sumber)
            VALUES (%s, %s, %s, %s, %s, %s, %s, %s)
        """
        sumber = 'Internet - Auto Scraped'
        self.cursor.execute(soal_sql, (
            KATEGORI_ID, jenis, topik, pertanyaan,
            kesulitan, pembahasan, tips, sumber
        ))
        soal_id = self.cursor.lastrowid

        # Insert opsi
        for label, teks, bobot, is_kunci in opsi_list:
            opsi_sql = """
                INSERT INTO opsi_jawaban (soal_id, label, teks_jawaban, bobot_nilai, is_kunci)
                VALUES (%s, %s, %s, %s, %s)
            """
            self.cursor.execute(opsi_sql, (soal_id, label, teks, bobot, is_kunci))

        self.conn.commit()
        self.soal_inserted += 1
        time.sleep(DELAY_INSERT)
        return soal_id

    def insert_materi(self, judul, topik, jenis, konten, level='dasar'):
        """Insert materi ajar jika belum ada."""
        # Cek duplikat judul
        check_sql = "SELECT id FROM materi WHERE judul = %s LIMIT 1"
        self.cursor.execute(check_sql, (judul,))
        if self.cursor.fetchone():
            return None

        materi_sql = """
            INSERT INTO materi (kategori_ujian_id, judul, topik, jenis_tes, 
            konten_html, tipe, level)
            VALUES (%s, %s, %s, %s, %s, %s, %s)
        """
        self.cursor.execute(materi_sql, (
            KATEGORI_ID, judul, topik, jenis, konten, 'artikel', level
        ))
        self.conn.commit()
        self.materi_inserted += 1
        return self.cursor.lastrowid

    def generate_materi_from_soal(self, topik, jenis, pembahasan_list):
        """Generate materi ajar otomatis dari kumpulan pembahasan soal."""
        if not pembahasan_list:
            return

        judul = f"Materi {topik} - {jenis.upper()}"
        konten = f"<h2>Pengantar {topik}</h2>\n"
        konten += f"<p>Materi ini membahas topik <strong>{topik}</strong> dalam ujian CPNS.</p>\n"
        konten += "<h3>Poin-poin Penting:</h3>\n<ul>\n"

        # Ekstrak tips unik dari pembahasan
        tips_set = set()
        for p in pembahasan_list[:5]:  # ambil 5 pembahasan
            sentences = re.split(r'[.!?]', p)
            for s in sentences:
                s = s.strip()
                if len(s) > 20 and len(s) < 200:
                    tips_set.add(s)

        for tip in list(tips_set)[:8]:
            konten += f"<li>{tip}</li>\n"

        konten += "</ul>\n"
        konten += f"<h3>Ringkasan {topik}</h3>\n"
        konten += f"<p>Pelajari dengan teliti materi ini agar dapat menjawab soal-soal {jenis.upper()} dengan baik.</p>\n"

        self.insert_materi(judul, topik, jenis, konten)

    def parse_tiu_soal(self, html, topik_default):
        """Parse soal TIU dari HTML."""
        soup = BeautifulSoup(html, 'lxml')
        soal_list = []

        # Cari pola SOAL X
        text = soup.get_text(separator='\n')
        soal_blocks = re.split(r'(?:SOAL\s*\d+|Contoh\s+Soal\s*\d+)', text)

        for block in soal_blocks[1:]:  # skip header
            lines = [l.strip() for l in block.split('\n') if l.strip()]
            if len(lines) < 6:
                continue

            # Extract pertanyaan (biasanya baris pertama setelah SOAL X)
            pertanyaan = lines[0]
            if len(pertanyaan) < 10:
                continue

            # Cari opsi A-E
            opsi_pattern = re.findall(r'([A-E])[.\s]+(.+)', block)
            if len(opsi_pattern) < 2:
                continue

            # Cari jawaban
            jawaban_match = re.search(r'Jawaban:\s*([A-E])', block)
            jawaban = jawaban_match.group(1) if jawaban_match else 'A'

            # Cari pembahasan
            pembahasan_match = re.search(r'Pembahasan[\s:]*(.*?)(?=SOAL|Contoh|$)', block, re.DOTALL)
            pembahasan = pembahasan_match.group(1).strip() if pembahasan_match else ''

            opsi_list = []
            for label, teks in opsi_pattern[:5]:
                is_kunci = 1 if label == jawaban else 0
                bobot = 5 if is_kunci else 0
                opsi_list.append((label, teks.strip(), bobot, is_kunci))

            if len(opsi_list) == 5:
                soal_list.append({
                    'jenis': 'tiu',
                    'topik': topik_default,
                    'pertanyaan': pertanyaan[:500],
                    'pembahasan': pembahasan[:1000],
                    'tips': f'Perhatikan pola logika pada soal {topik_default}',
                    'opsi': opsi_list,
                    'kesulitan': 'sedang'
                })

        return soal_list

    def parse_twk_soal(self, html, topik_default):
        """Parse soal TWK dari HTML."""
        soup = BeautifulSoup(html, 'lxml')
        soal_list = []
        text = soup.get_text(separator='\n')

        # Cari pola soal TWK
        soal_blocks = re.split(r'(?:SOAL\s*\d+|Soal\s*\d+)', text)

        for block in soal_blocks[1:]:
            lines = [l.strip() for l in block.split('\n') if l.strip()]
            if len(lines) < 6:
                continue

            pertanyaan = lines[0]
            if len(pertanyaan) < 10 or 'TWK' not in topik_default and len(pertanyaan) < 20:
                # TWK biasanya tentang Pancasila/UUD
                pass

            opsi_pattern = re.findall(r'([A-E])[.\s]+(.+)', block)
            if len(opsi_pattern) < 2:
                continue

            jawaban_match = re.search(r'Jawaban:\s*([A-E])', block)
            jawaban = jawaban_match.group(1) if jawaban_match else 'A'

            pembahasan_match = re.search(r'Pembahasan[\s:]*(.*?)(?=SOAL|Soal|$)', block, re.DOTALL)
            pembahasan = pembahasan_match.group(1).strip() if pembahasan_match else ''

            # Tentukan topik dari konten
            topik = topik_default
            if 'Pancasila' in pertanyaan or 'sila' in pertanyaan.lower():
                topik = 'Pancasila - Umum'
            elif 'UUD' in pertanyaan or 'Pasal' in pertanyaan:
                topik = 'UUD 1945 - Pasal'
            elif 'Bhinneka' in pertanyaan:
                topik = 'NKRI - Bhinneka Tunggal Ika'

            opsi_list = []
            for label, teks in opsi_pattern[:5]:
                is_kunci = 1 if label == jawaban else 0
                bobot = 5 if is_kunci else 0
                opsi_list.append((label, teks.strip(), bobot, is_kunci))

            if len(opsi_list) == 5:
                soal_list.append({
                    'jenis': 'twk',
                    'topik': topik,
                    'pertanyaan': pertanyaan[:500],
                    'pembahasan': pembahasan[:1000],
                    'tips': f'Hafalkan konsep {topik} dengan baik',
                    'opsi': opsi_list,
                    'kesulitan': 'mudah'
                })

        return soal_list

    def parse_tkp_soal(self, html):
        """Parse soal TKP dari HTML."""
        soup = BeautifulSoup(html, 'lxml')
        soal_list = []
        text = soup.get_text(separator='\n')

        # TKP punya format: jawaban ranking BACDE
        soal_blocks = re.split(r'(?:SOAL\s*\d+|Studi\s+Kasus)', text)

        for block in soal_blocks[1:]:
            lines = [l.strip() for l in block.split('\n') if l.strip()]
            if len(lines) < 6:
                continue

            pertanyaan_lines = []
            opsi_lines = []
            found_opsi = False

            for line in lines:
                if re.match(r'^[A-E][.\s]', line):
                    found_opsi = True
                    opsi_lines.append(line)
                elif not found_opsi:
                    pertanyaan_lines.append(line)

            if len(pertanyaan_lines) < 1 or len(opsi_lines) < 2:
                continue

            pertanyaan = ' '.join(pertanyaan_lines)[:500]

            # Cari jawaban ranking (contoh: BACDE)
            ranking_match = re.search(r'Jawaban:\s*([A-E]{5})', block)
            ranking = ranking_match.group(1) if ranking_match else 'ABCDE'

            # Cari pembahasan
            pembahasan_match = re.search(r'Pembahasan[\s:]*(.*?)(?=SOAL|Studi|$)', block, re.DOTALL)
            pembahasan = pembahasan_match.group(1).strip() if pembahasan_match else ''

            opsi_list = []
            for idx, line in enumerate(opsi_lines[:5]):
                match = re.match(r'^([A-E])[.\s]+(.+)', line)
                if match:
                    label, teks = match.groups()
                    # Bobot TKP: 5 untuk rank 1, 4 untuk rank 2, dst
                    rank_pos = ranking.find(label)
                    if rank_pos == -1:
                        rank_pos = idx
                    bobot = 5 - rank_pos if rank_pos < 5 else 1
                    is_kunci = 1 if rank_pos == 0 else 0
                    opsi_list.append((label, teks.strip(), max(1, bobot), is_kunci))

            if len(opsi_list) == 5:
                soal_list.append({
                    'jenis': 'tkp',
                    'topik': 'Pelayanan Publik',
                    'pertanyaan': pertanyaan,
                    'pembahasan': pembahasan[:1000],
                    'tips': 'Pilih jawaban yang paling profesional, solutif, dan berintegritas',
                    'opsi': opsi_list,
                    'kesulitan': 'sedang'
                })

        return soal_list

    def scrape_url(self, url, jenis, topik_default):
        """Scrape soal dari URL."""
        print(f"\n[SCRAPE] {url}")
        print(f"         Jenis: {jenis}, Topik: {topik_default}")

        try:
            resp = requests.get(url, headers=HEADERS, timeout=15)
            resp.raise_for_status()
            time.sleep(DELAY_REQUEST)
        except Exception as e:
            print(f"         ERROR fetch: {e}")
            return []

        html = resp.text

        if jenis == 'tiu':
            return self.parse_tiu_soal(html, topik_default)
        elif jenis == 'twk':
            return self.parse_twk_soal(html, topik_default)
        elif jenis == 'tkp':
            return self.parse_tkp_soal(html)
        else:
            return self.parse_tiu_soal(html, topik_default)

    def run(self):
        """Jalankan scraping dan insert."""
        print("=" * 60)
        print("CPNS SOAL SCRAPER + MATERI GENERATOR")
        print("=" * 60)

        all_soal = []
        materi_data = {}  # topik -> list pembahasan

        # Batch 1: Scraping dari internet
        print("\n[BATCH 1] Scraping soal dari internet...")
        for src in SOURCES:
            soal_list = self.scrape_url(src['url'], src['jenis'], src['topik_default'])
            print(f"         Ditemukan {len(soal_list)} soal")
            all_soal.extend(soal_list)

            # Kumpulkan pembahasan untuk materi
            for s in soal_list:
                key = (s['topik'], s['jenis'])
                if key not in materi_data:
                    materi_data[key] = []
                materi_data[key].append(s['pembahasan'])

        # Batch 2: Insert soal satu per satu
        print(f"\n[BATCH 2] Insert {len(all_soal)} soal ke database...")
        for idx, soal in enumerate(all_soal, 1):
            soal_id = self.insert_soal(
                soal['jenis'],
                soal['topik'],
                soal['pertanyaan'],
                soal['pembahasan'],
                soal['tips'],
                soal['opsi'],
                soal['kesulitan']
            )
            if soal_id:
                print(f"  [{idx}/{len(all_soal)}] INSERTED soal #{soal_id} [{soal['jenis'].upper()}] {soal['topik']}")
            else:
                print(f"  [{idx}/{len(all_soal)}] SKIPPED (duplikat)")

        # Batch 3: Generate materi ajar
        print(f"\n[BATCH 3] Generate materi ajar dari {len(materi_data)} topik...")
        for (topik, jenis), pembahasan_list in materi_data.items():
            self.generate_materi_from_soal(topik, jenis, pembahasan_list)
            print(f"  Materi: {topik} ({jenis.upper()}) - dari {len(pembahasan_list)} pembahasan")

        # Summary
        print("\n" + "=" * 60)
        print("SUMMARY")
        print("=" * 60)
        print(f"Soal inserted : {self.soal_inserted}")
        print(f"Soal skipped  : {self.soal_skipped} (duplikat)")
        print(f"Materi created: {self.materi_inserted}")
        print("=" * 60)

        self.cursor.close()
        self.conn.close()
        print("[DONE]")


if __name__ == '__main__':
    scraper = CPNSScraper()
    scraper.run()
