<?php
require_once __DIR__ . '/../includes/functions.php';
requirePeserta();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    flash('error', 'Akses tidak valid.');
    redirect('peserta/psikologi.php');
}

$jenis = $_POST['jenis'] ?? '';
$jawaban = $_POST['jawaban'] ?? [];
$user_id = $_SESSION['user_id'];
$csrf = $_POST['csrf_token'] ?? '';

if (!verifyCSRF($csrf)) {
    flash('error', 'Sesi tidak valid. Silakan ulangi tes.');
    redirect('peserta/psikologi.php');
}

$total = count($jawaban);
$benar = 0;
$skor = 0;

foreach ($jawaban as $soal_id => $opsi_id) {
    $opsi_id = intval($opsi_id);
    $soal_id = intval($soal_id);

    $stmt = $conn->prepare("SELECT bobot_nilai, is_kunci FROM opsi_jawaban WHERE id = ? AND soal_id = ?");
    $stmt->bind_param('ii', $opsi_id, $soal_id);
    $stmt->execute();
    $opsi = $stmt->get_result()->fetch_assoc();

    $nilai = $opsi ? intval($opsi['bobot_nilai']) : 0;
    if ($nilai > 0) {
        $benar++;
    }
    $skor += $nilai;
}

$stmt2 = $conn->prepare("INSERT INTO hasil_ujian (user_id, paket_ujian_id, skor_twk, skor_tiu, skor_tkp, skor_kumulatif, status_lulus, tanggal_mulai, tanggal_selesai) VALUES (?, NULL, 0, 0, ?, ?, ?, NOW(), NOW())");
$status = $skor >= PG_PSIKOLOGI ? 'lulus' : 'gugur';
$stmt2->bind_param('iiis', $user_id, $skor, $skor, $status);
$stmt2->execute();
$hasil_id = $stmt2->insert_id;

flash('success', "Hasil Tes Psikologi ($jenis): Skor $skor. Status: " . strtoupper($status));
redirect('peserta/psikologi_hasil.php');
