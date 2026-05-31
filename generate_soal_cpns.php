<?php
// CLI: Generate CPNS questions from internet knowledge (AI-rendered)
require_once __DIR__ . '/config.php';
if (php_sapi_name() !== 'cli') die('Run from CLI only');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die("DB ERROR: " . $conn->connect_error . "\n");
}

$soal_data = [
    // TWK
    ['twk','Pancasila - Sila ke-1','Ketuhanan Yang Maha Esa sebagai sila pertama diwujudkan melalui...','mudah','Sila 1: pengamalan keyakinan dalam kehidupan bermasyarakat, berbangsa, bernegara. Contoh: menghormati kebebasan beragama.','Fokus pada implementasi nilai ketuhanan dalam kehidupan sosial dan politik.',
     [['A','Menghormati kebebasan beragama dan kepercayaan',5,1],['B','Menjadikan agama sebagai sumber hukum negara',0,0],['C','Mewajibkan semua warga mengikuti agama mayoritas',0,0],['D','Menghapuskan perbedaan keyakinan dalam masyarakat',0,0],['E','Memisahkan agama dari kehidupan bernegara',0,0]]],
    ['twk','Pancasila - Sila ke-2','Berikut yang merupakan perwujudan sila kedua Pancasila adalah...','mudah','Sila ke-2: Kemanusiaan yang Adil dan Beradab. Contoh: menghargai HAM, tidak diskriminasi.','Ingat: Sila 2 = Kemanusiaan. Jika soal tentang HAM → jawaban Sila 2.',
     [['A','Menghargai hak asasi manusia setiap individu',5,1],['B','Mengutamakan kepentingan bangsa di atas individu',0,0],['C','Membatasi kebebasan untuk menjaga ketertiban',0,0],['D','Mewajibkan pelayanan sosial oleh negara',0,0],['E','Memperlakukan semua orang sama tanpa memandang hukum',0,0]]],
    ['twk','Pancasila - Sila ke-3','Persatuan Indonesia menuntut setiap warga negara untuk...','mudah','Sila ke-3: persatuan dan kesatuan bangsa. Warga harus rela berkorban untuk kepentingan bangsa.','Kata kunci: persatuan, kesatuan, NKRI, korban untuk bangsa.',
     [['A','Rela berkorban untuk kepentingan bangsa dan negara',5,1],['B','Membela kepentingan daerah masing-masing',0,0],['C','Mengutamakan hak individual',0,0],['D','Membentuk organisasi berdasarkan suku',0,0],['E','Menolak kerja sama dengan daerah lain',0,0]]],
    ['twk','Pancasila - Sila ke-4','Kerakyatan yang dipimpin oleh hikmat kebijaksanaan dalam permusyawaratan/perwakilan berarti...','sedang','Sila ke-4: kekuasaan di tangan rakyat, dijalankan melalui permusyawaratan. Dasar demokrasi Pancasila.','Demokrasi Pancasila ≠ liberal. Ciri: musyawarah, kekeluargaan, kepentingan bersama.',
     [['A','Kekuasaan berada di tangan rakyat melalui permusyawaratan',5,1],['B','Presiden memegang kekuasaan absolut atas rakyat',0,0],['C','Rakyat hanya berhak memilih tanpa berpendapat',0,0],['D','Permusyawaratan hanya dilakukan oleh elit politik',0,0],['E','Kekuasaan tertinggi ada pada partai politik',0,0]]],
    ['twk','Pancasila - Sila ke-5','Keadilan sosial bagi seluruh rakyat Indonesia diwujudkan dengan cara...','mudah','Sila ke-5: distribusi keadilan merata di bidang sosial, ekonomi, hukum.','Kata kunci: keadilan, merata, seluruh rakyat, tidak memihak, pembangunan merata.',
     [['A','Membangun dan mendistribusikan keadilan secara merata',5,1],['B','Memberikan keistimewaan pada kelompok tertentu',0,0],['C','Memusatkan pembangunan di wilayah strategis saja',0,0],['D','Mengutamakan pemodal besar dalam ekonomi',0,0],['E','Membebaskan pasar tanpa intervensi negara',0,0]]],
    ['twk','UUD 1945 - Pasal 1-5','Menurut UUD 1945 Pasal 1 ayat (1), Negara Indonesia adalah...','mudah','Pasal 1 ayat (1): Negara Indonesia ialah Negara Kesatuan, yang berbentuk Republik.','Hafalkan: Pasal 1 = Bentuk & Kedaulatan. (1) Negara Kesatuan Republik. (2) Kedaulatan rakyat. (3) Negara hukum.',
     [['A','Negara Kesatuan yang berbentuk Republik',5,1],['B','Negara Federasi yang berbentuk Republik',0,0],['C','Negara Kesatuan yang berbentuk Kerajaan',0,0],['D','Negara Serikat yang berbentuk Republik',0,0],['E','Negara Uni yang berbentuk Republik',0,0]]],
    ['twk','UUD 1945 - Pasal 1-5','Kedaulatan berada di tangan rakyat dan dilaksanakan menurut UUD merupakan bunyi dari...','sangat_mudah','Pasal 1 ayat (2): Kedaulatan berada di tangan rakyat dan dilaksanakan menurut Undang-Undang Dasar.','Pasal 1 ayat (2) = Kedaulatan rakyat. Jangan tertukar dengan ayat (3) tentang negara hukum.',
     [['A','Pasal 1 ayat (2) UUD 1945',5,1],['B','Pasal 1 ayat (3) UUD 1945',0,0],['C','Pasal 2 ayat (1) UUD 1945',0,0],['D','Pasal 3 UUD 1945',0,0],['E','Pasal 4 UUD 1945',0,0]]],
    ['twk','UUD 1945 - Pasal 1-5','Presiden memegang kekuasaan pemerintahan menurut UUD diatur dalam...','mudah','Pasal 4 ayat (1): Presiden Republik Indonesia memegang kekuasaan pemerintahan menurut UUD.','Pasal 4 = Presiden. (1) Pemerintahan. (2) Kepala negara. Lengkapi dengan Pasal 5.',
     [['A','Pasal 4 ayat (1) UUD 1945',5,1],['B','Pasal 3 UUD 1945',0,0],['C','Pasal 5 ayat (1) UUD 1945',0,0],['D','Pasal 6 UUD 1945',0,0],['E','Pasal 7 UUD 1945',0,0]]],
    ['twk','UUD 1945 - Pasal 27-34','Setiap warga negara berhak atas pekerjaan dan penghidupan yang layak untuk kemanusiaan diatur dalam...','sedang','Pasal 27 ayat (2): Tiap-tiap warga negara berhak atas pekerjaan dan penghidupan yang layak untuk kemanusiaan.','Pasal 27 = Warga negara & penduduk. ayat (1) sama di muka hukum. ayat (2) pekerjaan layak. ayat (3) ikut pembelaan negara.',
     [['A','Pasal 27 ayat (2) UUD 1945',5,1],['B','Pasal 28 ayat (1) UUD 1945',0,0],['C','Pasal 28A UUD 1945',0,0],['D','Pasal 28E ayat (1) UUD 1945',0,0],['E','Pasal 30 UUD 1945',0,0]]],
    ['twk','NKRI - Bhinneka Tunggal Ika','Semboyan Bhinneka Tunggal Ika berasal dari kitab...','mudah','Bhinneka Tunggal Ika berasal dari kitab Sutasoma karangan Mpu Tantular (abad XIV). Artinya: Berbeda-beda tetapi tetap satu jua.','Hafal: Mpu Tantular, kitab Sutasoma, abad XIV. Jangan tertukar dengan Negarakertagama (Mpu Prapanca).',
     [['A','Sutasoma karya Mpu Tantular',5,1],['B','Negarakertagama karya Mpu Prapanca',0,0],['C','Arjunawiwaha karya Mpu Kanwa',0,0],['D','Bharatayuddha karya Mpu Sedah',0,0],['E','Kakawin Ramayana',0,0]]],
    ['twk','NKRI - Bela Negara','Pembelaan negara dilakukan dengan cara...','mudah','Pasal 27 ayat (3): Pembelaan negara adalah hak dan kewajiban setiap warga negara. Cara: jabatan pemerintahan, tentara, atau swakelola.','Pasal 27 ayat (3) = hak dan kewajiban. Cara: jabatan pemerintahan, tentara, atau swakelola.',
     [['A','Mengabdi dalam jabatan pemerintahan, TNI, atau swakelola',5,1],['B','Membayar pajak lebih besar dari ketentuan',0,0],['C','Melakukan demonstrasi menentang kebijakan pemerintah',0,0],['D','Menjadi anggota ormas tertentu',0,0],['E','Memilih pemimpin daerah setiap periode',0,0]]],
    // TIU
    ['tiu','Verbal - Sinonim','Sinonim dari kata KONSISTEN adalah...','mudah','Konsisten = tetap, tidak berubah-ubah, teguh, konsekuen.','Gunakan konteks kalimat. Konsisten sering dipasangkan dengan sikap, perilaku, atau komitmen.',
     [['A','Tetap',5,1],['B','Berubah-ubah',0,0],['C','Ragu-ragu',0,0],['D','Bimbang',0,0],['E','Fluktuatif',0,0]]],
    ['tiu','Verbal - Antonim','Antonim dari kata EKSTENSIF adalah...','sedang','Ekstensif = luas, merata, menyeluruh. Antonimnya intensif = mendalam, terpusat.','Ekstensif vs Intensif sering muncul di soal CPNS. Ekstensif = luas. Intensif = mendalam.',
     [['A','Intensif',5,1],['B','Ekspansif',0,0],['C','Luas',0,0],['D','Merata',0,0],['E','Menyeluruh',0,0]]],
    ['tiu','Verbal - Analogi','GURU : SEKOLAH = DOKTER : ...','mudah','Analogi hubungan tempat bekerja. Guru bekerja di sekolah. Dokter bekerja di rumah sakit.','Cari pola hubungan: tempat bekerja, alat kerja, fungsi, atau lawan.',
     [['A','Rumah Sakit',5,1],['B','Pasien',0,0],['C','Obat',0,0],['D','Periksa',0,0],['E','Stetoskop',0,0]]],
    ['tiu','Numerik - Deret','Deret: 2, 5, 10, 17, 26, ... Berikutnya adalah?','sedang','Pola: n² + 1. 1²+1=2, 2²+1=5, 3²+1=10, 4²+1=17, 5²+1=26, maka 6²+1=37.','Rumus cepat: perhatikan selisih antar angka. Selisih: 3,5,7,9 → selanjutnya 11 → 26+11=37.',
     [['A','37',5,1],['B','35',0,0],['C','39',0,0],['D','41',0,0],['E','43',0,0]]],
    ['tiu','Numerik - Aritmatika','12 pekerja dapat menyelesaikan proyek dalam 20 hari. Jika ditambah 8 pekerja, berapa hari selesai?','sedang','Orang × Hari = konstan. 12×20=240. Pekerja baru=20. Hari=240/20=12.','Rumus cepat: Orang1 × Hari1 = Orang2 × Hari2. 12×20 = 20×x → x=12.',
     [['A','12 hari',5,1],['B','14 hari',0,0],['C','15 hari',0,0],['D','16 hari',0,0],['E','18 hari',0,0]]],
    ['tiu','Numerik - Perbandingan','6 orang menyelesaikan pekerjaan dalam 10 hari, maka 15 orang menyelesaikan dalam...','mudah','6×10=60 orang-hari. 15 orang butuh: 60/15=4 hari.','Perbandingan berbalik: Orang bertambah, hari berkurang. 6×10 = 15×x → x=4.',
     [['A','4 hari',5,1],['B','5 hari',0,0],['C','6 hari',0,0],['D','7 hari',0,0],['E','8 hari',0,0]]],
    ['tiu','Logika - Silogisme','Semua PNS wajib pelatihan. Budi adalah PNS. Kesimpulan: ...','mudah','Silogisme: Semua A adalah B. C adalah A. Maka C adalah B. Budi wajib pelatihan.','Silogisme sederhana: perhatikan subjek dan predikat. Jika premis valid, kesimpulan mengikuti pola logis.',
     [['A','Budi wajib mengikuti pelatihan',5,1],['B','Budi tidak wajib mengikuti pelatihan',0,0],['C','Semua yang mengikuti pelatihan adalah PNS',0,0],['D','Budi bukan PNS',0,0],['E','Tidak dapat disimpulkan',0,0]]],
    ['tiu','Logika - Analitis','Andi > Budi > Cici. Dedi < Cici. Kesimpulan: ...','sedang','Urutan: Andi > Budi > Cici > Dedi. Andi lebih tinggi dari Dedi.','Buat diagram urutan. Panah ke bawah = lebih pendek. Susun dari yang tertinggi.',
     [['A','Andi lebih tinggi dari Dedi',5,1],['B','Dedi lebih tinggi dari Budi',0,0],['C','Cici lebih tinggi dari Andi',0,0],['D','Budi lebih pendek dari Dedi',0,0],['E','Tidak ada kesimpulan yang pasti',0,0]]],
    // TKP
    ['tkp','Pelayanan Publik','Warga datang marah karena permohonan tertolak. Sikap Anda...','sedang','Pelayanan publik: tetap tenang, dengarkan keluhan, jelaskan sabar, berikan solusi alternatif.','TKP: Pilih jawaban yang menunjukkan sikap profesional, empati, dan solusi. Hindari pasif, defensif, atau arogan.',
     [['A','Mendengarkan keluhan dengan tenang dan menjelaskan alasan sambil menawarkan solusi alternatif',5,1],['B','Menjadi marah karena warga tidak menghormati petugas',1,0],['C','Mengabaikan warga dan melayani warga lain',2,0],['D','Menyuruh warga untuk datang lain hari',3,0],['E','Menegaskan bahwa keputusan sudah final',4,0]]],
    ['tkp','Integritas','Ditawari hadiah besar oleh vendor proyek. Sikap Anda...','sedang','Integritas: menolak gratifikasi dalam bentuk apapun. Melaporkan ke atasan atau penyelenggara negara.','Integritas = kejujuran, menolak suap/gratifikasi, melaporkan pelanggaran. Pilih yang paling tegas menolak dan melaporkan.',
     [['A','Menolak hadiah dan melaporkan ke atasan/penyelenggara negara',5,1],['B','Menerima hadiah karena sudah menjadi tradisi',1,0],['C','Menerima tapi tidak memberikan keuntungan khusus',2,0],['D','Menolak secara halus tanpa melaporkan',4,0],['E','Meminta hadiah yang lebih kecil agar tidak mencurigakan',3,0]]],
    ['tkp','Profesionalisme','Tugas mendadak Jumat sore harus lembur, padahal ada janji keluarga. Anda...','mudah','Profesionalisme: memprioritaskan tugas kedinasan sambil menghargai komitmen pribadi.','Profesionalisme ≠ mengorbankan keluarga terus-menerus. Cari win-win: komunikasi dengan atasan + komitmen menyelesaikan.',
     [['A','Menghubungi atasan untuk diskusi penyelesaian tugas dan konfirmasi ke keluarga',5,1],['B','Menolak tugas karena sudah ada janji keluarga',1,0],['C','Meninggalkan tugas tanpa izin untuk hadir acara keluarga',2,0],['D','Menyelesaikan tugas tanpa memberi tahu keluarga',3,0],['E','Meminta rekan menyelesaikan tugas tanpa sepengetahuan atasan',4,0]]],
    ['tkp','Komitmen','Rekan kerja sering datang terlambat. Sebagai kolega, Anda...','sedang','Komitmen: menjaga produktivitas tim. Tegur secara pribadi dan profesional, berikan solusi.','Komitmen: pilih pendekatan persuasif dan profesional sebelum melapor. Tegur pribadi → solusi → lapor jika tidak berubah.',
     [['A','Mengingatkan secara pribadi dan profesional serta memberikan solusi',5,1],['B','Melaporkan langsung ke atasan tanpa peringatan',3,0],['C','Mengikuti perilakunya karena tidak ada yang peduli',1,0],['D','Menegurnya di depan umum agar malu',2,0],['E','Mengabaikan karena bukan tanggung jawab Anda',4,0]]],
    ['tkp','Sosial Budaya','Ada rekan dari suku berbeda dengan kebiasaan berbeda. Sikap Anda...','mudah','Sosial Budaya: menghargai keberagaman, tidak memaksakan kebiasaan sendiri, memperlakukan setiap orang setara.','Keberagaman: pilih jawaban yang menunjukkan penghargaan, adaptasi, dan tidak diskriminatif.',
     [['A','Menghargai perbedaan dan beradaptasi selama tidak melanggar aturan',5,1],['B','Memintanya menyesuaikan dengan kebanyakan rekan',2,0],['C','Mengabaikan perbedaan dan fokus pada pekerjaan',3,0],['D','Melaporkan ke HRD karena mengganggu dinamika tim',1,0],['E','Menyarankan dipindahkan ke divisi lain',4,0]]],
];

$kategori = $conn->query("SELECT id FROM kategori_ujian WHERE nama LIKE '%CPNS%' LIMIT 1")->fetch_assoc();
$kat_id = $kategori['id'] ?? 1;
$count = 0;

foreach ($soal_data as $s) {
    $stmt = $conn->prepare('INSERT INTO soal (kategori_ujian_id, jenis_tes, topik, pertanyaan, tingkat_kesulitan, pembahasan, tips_triks) VALUES (?,?,?,?,?,?,?)');
    $stmt->bind_param('issssss', $kat_id, $s[0], $s[1], $s[2], $s[3], $s[4], $s[5]);
    $stmt->execute();
    $soal_id = $stmt->insert_id;

    foreach ($s[6] as $o) {
        $stmt2 = $conn->prepare('INSERT INTO opsi_jawaban (soal_id, label, teks_jawaban, bobot_nilai, is_kunci) VALUES (?,?,?,?,?)');
        $stmt2->bind_param('issii', $soal_id, $o[0], $o[1], $o[2], $o[3]);
        $stmt2->execute();
    }
    $count++;
}

echo "Generated $count soal CPNS (TWK, TIU, TKP) dari pengetahuan internet.\n";
echo "Setup complete.\n";
