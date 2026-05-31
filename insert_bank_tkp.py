#!/usr/bin/env python3
"""Insert 35 soal TKP unik"""
import time, hashlib, mysql.connector
DB = {'host':'localhost','user':'root','password':'root','database':'db_tryout'}
conn = mysql.connector.connect(**DB); cur = conn.cursor(dictionary=True)

SOAL = [
('tkp','Profesionalisme','Petugas pelayanan menghadapi warga marah karena permohonan tertolak. Sikap terbaik...','sedang','Dengarkan, tenang, jelaskan dengan sabar, beri solusi alternatif.','TKP: Pilih profesional, empati, solusi.',
[('A','Dengarkan keluhan tenang & jelaskan alasan + solusi alternatif',5,1),('B','Marah balik karena warga tidak hormat',1,0),('C','Abaikan warga, layani yang lain',2,0),('D','Suruh datang lain hari',3,0),('E','Tegaskan keputusan final tidak bisa diganggu gugat',4,0)]),
('tkp','Integritas','Ditawari hadiah besar oleh vendor proyek. Sikap Anda...','sedang','Tolak gratifikasi dan laporkan ke atasan/penyelenggara negara.','Integritas = tolak + laporkan.',
[('A','Menolak hadiah dan melaporkan ke atasan/penyelenggara negara',5,1),('B','Menerima karena sudah tradisi',1,0),('C','Menerima tapi tidak beri keuntungan khusus',2,0),('D','Menolak tanpa melaporkan',4,0),('E','Minta hadiah lebih kecil',3,0)]),
('tkp','Komitmen','Tugas mendadak Jumat sore harus lembur, tapi sudah janji keluarga. Yang dilakukan...','mudah','Komunikasi dengan atasan + konfirmasi ke keluarga.','Profesionalisme ≠ mengorbankan keluarga. Cari win-win.',
[('A','Hubungi atasan diskusi tugas + konfirmasi keluarga',5,1),('B','Tolak tugas karena janji keluarga',1,0),('C','Tinggalkan tugas tanpa izin',2,0),('D','Selesaikan tugas tanpa kabar keluarga',3,0),('E','Minta rekan kerjakan tanpa izin atasan',4,0)]),
('tkp','Profesionalisme','Rekan sering datang terlambat dan pulang cepat. Sebagai kolega...','sedang','Bicara pribadi, beri saran perbaikan, jika perlu lapor atasan.','Komitmen organisasi: jaga produktivitas tim.',
[('A','Bicara pribadi & beri saran, eskalasi jika tidak berubah',5,1),('B','Biarkan karena urusan pribadi',1,0),('C','Lapor langsung ke atasan tanpa bicara dulu',2,0),('D','Ikut-ikutan pulang cepat',3,0),('E','Sebarkan ke rekan lain',4,0)]),
('tkp','Sosial Budaya','Ditugaskan di daerah budaya sangat berbeda. Sikap Anda...','mudah','Belajar dan hormati budaya setempat, tetap profesional.','Adaptasi: hormati norma lokal.',
[('A','Belajar & hormati budaya setempat, tetap profesional',5,1),('B','Tolak tugas karena tidak nyaman',1,0),('C','Paksa budaya asal ke lingkungan baru',2,0),('D','Isolasi diri, tidak berinteraksi',3,0),('E','Anggap budaya daerah inferior',4,0)]),
('tkp','Pelayanan Publik','Antrean panjang, lansia terlihat kelelahan. Tindakan terbaik...','mudah','Sediakan kursi dan prioritas pelayanan sesuai ketentuan.','Pelayanan: utamakan kelompok rentan.',
[('A','Sediakan kursi & prioritas pelayanan sesuai ketentuan',5,1),('B','Abaikan karena semua sama di muka hukum',1,0),('C','Suruh lansia datang besok',2,0),('D','Sarankan minta bantuan keluarga',3,0),('E','Lanjutkan antrean tanpa perhatikan lansia',4,0)]),
('tkp','Integritas','Atasan gunakan kendaraan dinas untuk pribadi. Tindakan Anda...','sedang','Laporkan ke inspektorat/penyelenggara negara.','Integritas: laporkan pelanggaran, jangan tutupi.',
[('A','Laporkan ke inspektorat/penyelenggara negara',5,1),('B','Anggap urusan pribadi atasan',1,0),('C','Sebarkan ke rekan agar semua tahu',2,0),('D','Minta bagian fasilitas tersebut',3,0),('E','Abaikan karena takut kehilangan pekerjaan',4,0)]),
('tkp','Komitmen','Tugas deadline ketat tapi data belum lengkap. Yang dilakukan...','sedang','Lapor kendala ke atasan & berusaha dengan data tersedia.','Komitmen: komunikasi + usaha maksimal.',
[('A','Lapor kendala ke atasan & usaha dengan data tersedia',5,1),('B','Tunggu data lengkap tanpa lapor',1,0),('C','Klaim selesai meski data belum lengkap',2,0),('D','Tolak tugas karena deadline tidak realistis',3,0),('E','Alihkan tanggung jawab ke rekan',4,0)]),
('tkp','Profesionalisme','Atasan kritik keras hasil kerja Anda di rapat umum. Reaksi terbaik...','sedang','Dengarkan, catat, minta saran perbaikan setelah rapat.','Profesional: terima kritik dengan lapang dada.',
[('A','Dengarkan, catat, minta saran perbaikan setelah rapat',5,1),('B','Beladiri & jelaskan alasannya di depan umum',1,0),('C','Tolak kritik karena sudah kerja keras',2,0),('D','Salahkan rekan yang tidak mendukung',3,0),('E','Tinggalkan rapat karena malu',4,0)]),
('tkp','Pelayanan Publik','Warga mengeluh proses berkas terlalu lama. Tindakan Anda...','sedang','Mint maaf, percepat proses sesuai SOP, beri penjelasan.','Pelayanan: solusi-oriented, jangan alasan.',
[('A','Mint maaf, percepat sesuai SOP, beri penjelasan',5,1),('B','Bilang itu prosedur standar, sabar',1,0),('C','Suruh warga protes ke atasan',2,0),('D','Abaikan karena banyak antrean lain',3,0),('E','Tolak berkas karena warga tidak sabar',4,0)]),

('tkp','Profesionalisme','Anda diminta mengerjakan tugas di luar tupoksi. Sikap terbaik...','sedang','Terima dan kerjakan sebaik mungkin, komunikasikan jika overload.','Profesionalisme: fleksibel tapi komunikatif.',
[('A','Terima dan kerjakan sebaik mungkin, komunikasikan jika overload',5,1),('B','Tolak karena bukan tugas pokok',1,0),('C','Kerjakan asal selesai tanpa maksimal',2,0),('D','Minta ganti tugas dengan yang lebih mudah',3,0),('E','Biarkan menumpuk',4,0)]),
('tkp','Integritas','Menemukan uang di dalam dokumen warga yang dikembalikan. Tindakan...','sedang','Segera hubungi warga untuk mengembalikan uang tersebut.','Integritas = kejujuran dalam hal kecil sekalipun.',
[('A','Segera hubungi warga untuk mengembalikan uang',5,1),('B','Simpan uang untuk keperluan kantor',1,0),('C','Bagikan dengan rekan kerja',2,0),('D','Masukkan ke kotak amal',3,0),('E','Tunggu warga menghubungi sendiri',4,0)]),
('tkp','Komitmen','Proyek tim terganggu karena 1 rekan sakit. Yang Anda lakukan...','sedang','Bagi tugas rekan yang sakit ke anggota lain & tetap komunikasi.','Komitmen tim: gotong royong, jangan biarkan proyek gagal.',
[('A','Bagi tugas ke anggota lain & tetap komunikasi',5,1),('B','Tunda proyek sampai rekan sembuh',1,0),('C','Kerjakan sendiri tanpa bantuan',2,0),('D','Laporkan atasan tanpa solusi',3,0),('E','Biarkan proyek terbengkalai',4,0)]),
('tkp','Pelayanan Publik','Warga datang tanpa dokumen lengkap tapi urgensitas tinggi. Sikap...','sedang','Bantu dengan data tersedia, arahkan dokumen kurang, prioritaskan.','Pelayanan: bantu sebisa mungkin tanpa melanggar aturan.',
[('A','Bantu dengan data tersedia & arahkan dokumen kurang',5,1),('B','Tolak karena dokumen tidak lengkap',1,0),('C','Suruh pulang & datang lagi lengkap',2,0),('D','Proses asal-asalan agar cepat',3,0),('E','Marahi warga karena tidak persiapan',4,0)]),
('tkp','Sosial Budaya','Menerima keluhan warga dengan bahasa daerah yang tidak Anda kuasai. Tindakan...','mudah','Mint tolong penerjemah/anggota yang menguasai bahasa daerah tersebut.','Sosial budaya: hormati bahasa daerah, cari solusi komunikasi.',
[('A','Mint tolong penerjemah/anggota yang menguasai bahasa tersebut',5,1),('B','Paksa warga menggunakan bahasa Indonesia',1,0),('C','Abaikan keluhan karena tidak mengerti',2,0),('D','Tebak-tebak arti keluhan warga',3,0),('E','Suruh warga datang dengan penerjemah sendiri',4,0)]),

('tkp','Profesionalisme','Anda salah input data di sistem. Yang dilakukan...','mudah','Segera lapor dan perbaiki kesalahan, jangan tutupi.','Integritas: akui kesalahan, perbaiki.',
[('A','Segera lapor & perbaiki kesalahan',5,1),('B','Biarkan karena mungkin tidak ada yang sadar',1,0),('C','Salahkan sistem yang error',2,0),('D','Minta rekan memperbaiki tanpa sepengetahuan atasan',3,0),('E','Hapus jejak kesalahan',4,0)]),
('tkp','Integritas','Diminta mempercepat proses oleh kenalan dengan imbalan. Tindakan...','sedang','Tolak dan jelaskan prosedur harus diikuti semua orang.','Integritas: prosedur sama untuk semua, tanpa pengecualian.',
[('A','Tolak & jelaskan prosedur sama untuk semua',5,1),('B','Terima karena kenalan dekat',1,0),('C','Terima tapi tidak janji pasti',2,0),('D','Arahkan ke atasan untuk keputusan',4,0),('E','Bantu dengan syarat imbalan lain',3,0)]),
('tkp','Komitmen','Tugas proyek memerlukan kerja lembur berkelanjutan. Sikap...','sedang','Komunikasikan ke atasan untuk efisiensi atau penambahan SDM.','Komitmen ≠ self-exploitation. Cari solusi berkelanjutan.',
[('A','Komunikasikan ke atasan untuk efisiensi atau tambah SDM',5,1),('B','Kerjakan lembur terus tanpa protes',1,0),('C','Meninggalkan tugas karena kelelahan',2,0),('D','Minta ganti rugi lembur lebih dulu',3,0),('E','Biarkan proyek terlambat',4,0)]),
('tkp','Pelayanan Publik','Warga tidak puas dengan keputusan yang sudah final. Tindakan...','sedang','Jelaskan dengan sabar, arahkan ke prosedur banding jika ada.','Pelayanan: tetap profesional meski keputusan tidak populer.',
[('A','Jelaskan dengan sabar & arahkan ke prosedur banding',5,1),('B','Abaikan karena keputusan sudah final',1,0),('C','Marahi warga karena tidak menghargai keputusan',2,0),('D','Ubah keputusan demi kepuasan warga',3,0),('E','Suruh warga protes ke instansi lain',4,0)]),
('tkp','Sosial Budaya','Anda diminta mengikuti ritual adat yang bertentangan dengan keyakinan Anda. Sikap...','sedang','Hormati ritual adat dengan tetap menjaga keyakinan pribadi.','Sila 1: toleransi. Hormati adat tanpa meninggalkan keyakinan.',
[('A','Hormati ritual adat tanpa meninggalkan keyakinan pribadi',5,1),('B','Tolak dengan keras karena bertentangan keyakinan',1,0),('C','Ikuti ritual meski bertentangan keyakinan',2,0),('D','Abaikan undangan ritual',3,0),('E','Kritik ritual di depan umum',4,0)]),

('tkp','Profesionalisme','Anda menemukan data pribadi warga bocor di media sosial. Tindakan...','sedang','Lapor ke atasan & tim keamanan informasi, bantu identifikasi sumber kebocoran.','Profesionalisme: tanggap keamanan data.',
[('A','Lapor atasan & tim keamanan, bantu identifikasi sumber',5,1),('B','Abaikan karena bukan tanggung jawab Anda',1,0),('C','Sebarkan ke publik agar semua waspada',2,0),('D','Hapus jejak tanpa melapor',3,0),('E','Salahkan sistem IT',4,0)]),
('tkp','Integritas','Rekan kerja Anda curang dalam laporan keuangan. Tindakan...','sedang','Kumpulkan bukti, laporkan ke atasan/auditor internal.','Integritas: melaporkan kecurangan wajib dilakukan.',
[('A','Kumpulkan bukti & lapor ke atasan/auditor internal',5,1),('B','Biarkan karena bukan urusan Anda',1,0),('C','Konfrontasi rekan di depan umum',2,0),('D','Ikut-ikutan curang agar tidak dianggap iri',3,0),('E','Ancam rekan agar berhenti curang',4,0)]),
('tkp','Komitmen','Proyek penting deadline mepet, tim rewel. Yang Anda lakukan...','sedang','Motivasi tim, koordinasi tugas, dan tetap fokus tujuan.','Komitmen: leadership di saat sulit.',
[('A','Motivasi tim, koordinasi tugas, fokus tujuan',5,1),('B','Tunggu atasan menenangkan tim',1,0),('C','Bekerja sendiri tanpa tim',2,0),('D','Minta perpanjangan deadline',3,0),('E','Biarkan tim berantem sendiri',4,0)]),
('tkp','Pelayanan Publik','Sistem online down saat jam sibuk. Warga marah-marah. Tindakan...','sedang','Mint maaf, arahkan ke jalur offline/manual, laporkan ke IT.','Pelayanan: solusi alternatif saat sistem bermasalah.',
[('A','Mint maaf, arahkan ke jalur offline/manual, lapor IT',5,1),('B','Biarkan warga menunggu sistem hidup',1,0),('C','Marahi warga karena tidak sabar',2,0),('D','Tutup pelayanan sampai sistem normal',3,0),('E','Sebarkan kesalahan sistem ke publik',4,0)]),
('tkp','Sosial Budaya','Acara kantor memerlukan menu non-halal, tapi Anda Muslim. Sikap...','sedang','Hormati acara, pilih menu halal yang tersedia, jadikan ajang silaturahmi.','Sosial budaya: adaptasi tanpa meninggalkan keyakinan.',
[('A','Hormati acara, pilih menu halal, jadikan ajang silaturahmi',5,1),('B','Tolak ikut acara karena ada menu non-halal',1,0),('C','Ikut acara tapi komplain keras',2,0),('D','Diam saja & tidak makan apapun',3,0),('E','Tuntut panitia ganti menu semua halal',4,0)]),

('tkp','Profesionalisme','Anda salah kirim email internal ke pihak eksternal. Tindakan...','sedang','Segera minta maaf, tarik email, dan lapor ke atasan.','Profesionalisme: akui & perbaiki kesalahan komunikasi.',
[('A','Segera minta maaf, tarik email, lapor atasan',5,1),('B','Abaikan karena mungkin tidak dibaca',1,0),('C','Kirim email baru membantah isi sebelumnya',2,0),('D','Salahkan IT email error',3,0),('E','Tutupi dengan email palsu',4,0)]),
('tkp','Integritas','Atasan minta Anda memalsukan tanda tangan untuk percepatan proses. Tindakan...','sedang','Tolak tegas dan jelaskan risiko hukum pemalsuan dokumen.','Integritas: pemalsuan dokumen = pidana.',
[('A','Tolak tegas & jelaskan risiko hukum pemalsuan',5,1),('B','Lakukan karena perintah atasan',1,0),('C','Lakukan tapi minta jaminan aman',2,0),('D','Alihkan ke rekan lain',3,0),('E','Buat tanda tangan palsu dengan berbeda',4,0)]),
('tkp','Komitmen','Program baru gagal total meski Anda sudah kerja keras. Sikap...','sedang','Evaluasi bersama tim, identifikasi penyebab, susun rencana perbaikan.','Komitmen: kegagalan adalah pembelajaran, bukan akhir.',
[('A','Evaluasi bersama tim & susun rencana perbaikan',5,1),('B','Menyerah dan biarkan program ditutup',1,0),('C','Salahkan rekan yang tidak kompak',2,0),('D','Tutupi kegagalan di laporan',3,0),('E','Tuntut reward meski gagal',4,0)]),
('tkp','Pelayanan Publik','Warga tidak bisa membaca dan menulis, butuh bantuan formulir. Tindakan...','mudah','Bantu isi formulir dengan sabar sambil jelaskan isinya.','Pelayanan: inklusi bagi yang buta huruf.',
[('A','Bantu isi formulir dengan sabar & jelaskan isinya',5,1),('B','Tolak karena warga harus isi sendiri',1,0),('C','Suruh warga minta bantuan orang lain',2,0),('D','Isi asal-asalan agar cepat',3,0),('E','Marahi warga karena tidak bisa baca tulis',4,0)]),
('tkp','Sosial Budaya','Anda diundang makan di rumah warga dengan tradisi makan bersama pakai tangan. Anda biasa pakai sendok. Sikap...','sedang','Ikuti tradisi makan bersama dengan tangan, jadikan pengalaman budaya.','Sosial budaya: hormati tradisi lokal tanpa menghina.',
[('A','Ikuti tradisi makan dengan tangan, jadikan pengalaman budaya',5,1),('B','Tolak makan karena tidak nyaman pakai tangan',1,0),('C','Minta sendok tanpa menjelaskan',2,0),('D','Kritik tradisi tidak higienis',3,0),('E','Makan sendiri di sudut dengan sendok',4,0)]),

('tkp','Profesionalisme','Anda diminta oleh 2 atasan dengan prioritas berbeda pada waktu yang sama. Tindakan...','sedang','Komunikasikan ke kedua atasan, negosiasikan prioritas & deadline.','Profesionalisme: manajemen konflik antar atasan.',
[('A','Komunikasikan ke kedua atasan & negosiasikan prioritas',5,1),('B','Pilih atasan yang lebih senior',1,0),('C','Kerjakan keduanya setengah-setengah',2,0),('D','Tolak salah satu tanpa alasan',3,0),('E','Abaikan kedua tugas',4,0)]),
('tkp','Integritas','Anda menemukan bahwa data statistik kantor dibuat-buat untuk laporan. Tindakan...','sedang','Kumpulkan bukti, laporkan ke auditor internal/inspektorat.','Integritas: data palsu = kecurangan serius.',
[('A','Kumpulkan bukti & lapor ke auditor internal/inspektorat',5,1),('B','Abaikan karena semua kantor juga begitu',1,0),('C','Perbaiki data sendiri tanpa melapor',2,0),('D','Sebarkan ke media sosial',3,0),('E','Gunakan data palsu demi kelancaran laporan',4,0)]),
('tkp','Komitmen','Tim Anda kalah kompetisi antar unit kerja. Sikap...','sedang','Ucapkan selamat ke pemenang, evaluasi diri, dan motivasi tim untuk kompetisi berikutnya.','Komitmen: sportivitas dan continuous improvement.',
[('A','Ucapkan selamat, evaluasi diri, motivasi tim',5,1),('B','Salahkan juri tidak adil',1,0),('C','Putus asa dan tidak ikut kompetisi lagi',2,0),('D','Tuduh pemenang curang',3,0),('E','Biarkan tim kecewa tanpa tindakan lanjut',4,0)]),
('tkp','Pelayanan Publik','Warga datang 5 menit sebelum jam tutup dengan urusan panjang. Tindakan...','sedang','Terima dengan ramah, selesaikan urusan, atau jadwalkan lanjutan besok.','Pelayanan: jangan tolak hanya karena waktu.',
[('A','Terima dengan ramah & selesaikan urusan',5,1),('B','Tolak karena sudah mau tutup',1,0),('C','Suruh datang pagi-pagi besok',2,0),('D','Kerjakan asal-asalan agar cepat selesai',3,0),('E','Marahi warga karena datang telat',4,0)]),
('tkp','Sosial Budaya','Anda melihat rekan mengejek dialek daerah warga. Tindakan...','sedang','Ingatkan rekan bahwa setiap dialek adalah kekayaan budaya dan harus dihormati.','Sosial budaya: penghargaan terhadap keberagaman bahasa.',
[('A','Ingatkan rekan bahwa dialek adalah kekayaan budaya',5,1),('B','Ikut mengejek karena memang lucu',1,0),('C','Diam saja karena bukan urusan Anda',2,0),('D','Lapor atasan tanpa bicara ke rekan dulu',3,0),('E','Ejek balik dialek rekan',4,0)]),
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

print(f"[DONE] TKP: {inserted} inserted, {skipped} skipped")
cur.close(); conn.close()
