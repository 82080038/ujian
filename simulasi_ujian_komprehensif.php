<?php
require_once __DIR__ . '/config.php';
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) die("DB Error: " . $conn->connect_error);

echo "=" . str_repeat("=", 60) . "\n";
echo "  SIMULASI UJIAN KOMPREHENSIF + PEMBAHASAN SOAL\n";
echo "=" . str_repeat("=", 60) . "\n\n";

// ============================================
// STEP 1: Buat Peserta Simulasi
// ============================================
$nama = "Peserta Simulasi " . date('His');
$email = "simulasi_" . time() . "_" . uniqid() . "@test.com";
$pass = password_hash('password123', PASSWORD_DEFAULT);
$hp = "081234567899";

$stmt = $conn->prepare("INSERT INTO users (nama, email, password, no_hp, role, target_ujian) VALUES (?, ?, ?, ?, 'peserta', 'cpns')");
$stmt->bind_param('ssss', $nama, $email, $pass, $hp);
$stmt->execute();
$user_id = $stmt->insert_id;
echo "[1] Peserta dibuat: $nama (ID=$user_id)\n\n";

// ============================================
// STEP 2: Ambil Paket Ujian
// ============================================
$paket = $conn->query("SELECT id FROM paket_ujian WHERE status='aktif' LIMIT 1")->fetch_assoc();
if (!$paket) {
    echo "[ERROR] Tidak ada paket ujian aktif.\n";
    exit(1);
}
$paket_id = $paket['id'];
echo "[2] Paket ujian ID=$paket_id\n\n";

// ============================================
// STEP 3: Ambil Soal per Jenis (5 soal per jenis)
// ============================================
$jenis_list = ['twk', 'tiu', 'tkp', 'psikologi'];
$soal_per_jenis = 5;
$semua_soal = [];

foreach ($jenis_list as $jenis) {
    $res = $conn->query("SELECT s.id, s.topik, s.jenis_tes, s.pertanyaan, s.pembahasan, s.tips_triks, s.tingkat_kesulitan FROM soal s WHERE s.jenis_tes = '$jenis' ORDER BY RAND() LIMIT $soal_per_jenis");
    while ($row = $res->fetch_assoc()) {
        $semua_soal[] = $row;
    }
}

echo "[3] Soal yang diambil:\n";
$count_per_jenis = [];
foreach ($semua_soal as $s) {
    $j = $s['jenis_tes'];
    if (!isset($count_per_jenis[$j])) $count_per_jenis[$j] = 0;
    $count_per_jenis[$j]++;
}
foreach ($count_per_jenis as $j => $c) {
    echo "    - " . strtoupper($j) . ": $c soal\n";
}
echo "    TOTAL: " . count($semua_soal) . " soal\n\n";

// ============================================
// STEP 4: Simulasi Jawaban + Hitung Skor
// ============================================
echo "[4] SIMULASI JAWABAN:\n";
$skor = ['twk'=>0, 'tiu'=>0, 'tkp'=>0, 'psikologi'=>0];
$jumlah_soal = ['twk'=>0, 'tiu'=>0, 'tkp'=>0, 'psikologi'=>0];
$jumlah_benar = ['twk'=>0, 'tiu'=>0, 'tkp'=>0, 'psikologi'=>0];
$detail = [];

foreach ($semua_soal as $s) {
    $j = $s['jenis_tes'];
    $jumlah_soal[$j]++;
    
    // Ambil opsi
    $opsi = $conn->query("SELECT id, label, teks_jawaban, bobot_nilai, is_kunci FROM opsi_jawaban WHERE soal_id = {$s['id']} ORDER BY label");
    $opsi_list = [];
    while ($o = $opsi->fetch_assoc()) $opsi_list[] = $o;
    
    // Simulasi: 70% benar secara acak
    $acak = rand(1, 100);
    if ($acak <= 70) {
        // Pilih jawaban kunci
        foreach ($opsi_list as $o) {
            if ($o['is_kunci']) {
                $skor[$j] += $o['bobot_nilai'];
                $jumlah_benar[$j]++;
                $detail[] = [
                    'soal_id' => $s['id'],
                    'jenis' => $j,
                    'topik' => $s['topik'],
                    'pertanyaan' => $s['pertanyaan'],
                    'jawaban' => $o['label'] . '. ' . $o['teks_jawaban'],
                    'benar' => true,
                    'pembahasan' => $s['pembahasan'],
                    'tips' => $s['tips_triks'],
                    'kesulitan' => $s['tingkat_kesulitan']
                ];
                break;
            }
        }
    } else {
        // Pilih jawaban salah acak
        $salah = array_filter($opsi_list, fn($o) => !$o['is_kunci']);
        $salah = array_values($salah);
        if (count($salah) > 0) {
            $pilih = $salah[array_rand($salah)];
            $detail[] = [
                'soal_id' => $s['id'],
                'jenis' => $j,
                'topik' => $s['topik'],
                'pertanyaan' => $s['pertanyaan'],
                'jawaban' => $pilih['label'] . '. ' . $pilih['teks_jawaban'],
                'benar' => false,
                'pembahasan' => $s['pembahasan'],
                'tips' => $s['tips_triks'],
                'kesulitan' => $s['tingkat_kesulitan']
            ];
        }
    }
}

foreach ($jenis_list as $j) {
    $b = $jumlah_benar[$j];
    $t = $jumlah_soal[$j];
    $s = $skor[$j];
    echo "    " . strtoupper($j) . ": {$b}/{$t} benar (Skor: $s)\n";
}
$skor_kumulatif = $skor['twk'] + $skor['tiu'] + $skor['tkp'];
echo "    KUMULATIF TWK+TIU+TKP: $skor_kumulatif\n\n";

// ============================================
// STEP 5: Simpan Hasil ke DB
// ============================================
$stmt = $conn->prepare("INSERT INTO hasil_ujian (user_id, paket_ujian_id, tanggal_mulai, tanggal_selesai, skor_twk, skor_tiu, skor_tkp, skor_kumulatif, status_lulus) VALUES (?, ?, NOW(), NOW(), ?, ?, ?, ?, ?)");
$status = ($skor['twk'] >= PG_TWK && $skor['tiu'] >= PG_TIU && $skor['tkp'] >= PG_TKP && $skor_kumulatif >= PG_KUMULATIF) ? 'lulus' : 'gugur';
$stmt->bind_param('iiiiiis', $user_id, $paket_id, $skor['twk'], $skor['tiu'], $skor['tkp'], $skor_kumulatif, $status);
$stmt->execute();
$hasil_id = $stmt->insert_id;
echo "[5] Hasil ujian disimpan (ID=$hasil_id) - Status: " . strtoupper($status) . "\n\n";

// ============================================
// STEP 6: Pembahasan Soal (yang salah & benar)
// ============================================
echo "=" . str_repeat("=", 60) . "\n";
echo "  PEMBAHASAN SOAL\n";
echo "=" . str_repeat("=", 60) . "\n\n";

// Tampilkan yang salah dulu
$salah_list = array_filter($detail, fn($d) => !$d['benar']);
$benar_list = array_filter($detail, fn($d) => $d['benar']);

if (count($salah_list) > 0) {
    echo "--- SOAL YANG SALAH (" . count($salah_list) . " soal) ---\n\n";
    foreach (array_slice(array_values($salah_list), 0, 3) as $i => $d) {
        echo "Soal #" . ($i+1) . " [" . strtoupper($d['jenis']) . "] {$d['topik']} ({$d['kesulitan']})\n";
        echo "  Pertanyaan: " . substr($d['pertanyaan'], 0, 80) . "...\n";
        echo "  Jawaban Anda: {$d['jawaban']}\n";
        echo "  Pembahasan: {$d['pembahasan']}\n";
        echo "  Tips: {$d['tips']}\n\n";
    }
}

if (count($benar_list) > 0) {
    echo "--- SOAL YANG BENAR (" . count($benar_list) . " soal) ---\n\n";
    foreach (array_slice(array_values($benar_list), 0, 2) as $i => $d) {
        echo "Soal #" . ($i+1) . " [" . strtoupper($d['jenis']) . "] {$d['topik']} ({$d['kesulitan']})\n";
        echo "  Pertanyaan: " . substr($d['pertanyaan'], 0, 80) . "...\n";
        echo "  Jawaban: {$d['jawaban']}\n";
        echo "  Tips: {$d['tips']}\n\n";
    }
}

// ============================================
// STEP 7: Rekomendasi Materi
// ============================================
echo "=" . str_repeat("=", 60) . "\n";
echo "  REKOMENDASI BELAJAR\n";
echo "=" . str_repeat("=", 60) . "\n\n";

foreach ($jenis_list as $j) {
    if ($j === 'psikologi') continue; // psikologi tidak masuk kumulatif
    $persen = $jumlah_soal[$j] > 0 ? ($jumlah_benar[$j] / $jumlah_soal[$j]) * 100 : 0;
    if ($persen < 70) {
        // Cari materi
        $materi = $conn->query("SELECT judul, topik FROM materi WHERE jenis_tes = '$j' ORDER BY id DESC LIMIT 2");
        echo "[" . strtoupper($j) . "] Akurasi " . round($persen) . "% - Perlu belajar:\n";
        while ($m = $materi->fetch_assoc()) {
            echo "  -> {$m['judul']} (topik: {$m['topik']})\n";
        }
        echo "\n";
    } else {
        echo "[" . strtoupper($j) . "] Akurasi " . round($persen) . "% - Sudah baik!\n\n";
    }
}

// ============================================
// STEP 8: Leaderboard Preview
// ============================================
echo "=" . str_repeat("=", 60) . "\n";
echo "  LEADERBOARD (TOP 5)\n";
echo "=" . str_repeat("=", 60) . "\n\n";
$board = $conn->query("SELECT u.nama, h.skor_kumulatif, h.status_lulus FROM hasil_ujian h JOIN users u ON h.user_id = u.id WHERE h.status_lulus != 'proses' ORDER BY h.skor_kumulatif DESC LIMIT 5");
$rank = 1;
while ($b = $board->fetch_assoc()) {
    echo "  #$rank {$b['nama']} - Skor: {$b['skor_kumulatif']} - {$b['status_lulus']}\n";
    $rank++;
}

echo "\n" . str_repeat("=", 62) . "\n";
echo "  SIMULASI SELESAI\n";
echo "  Peserta: $nama\n";
echo "  Hasil Ujian ID: $hasil_id\n";
echo str_repeat("=", 62) . "\n";

$conn->close();
