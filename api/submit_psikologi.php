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

// Simpan hasil psikologi ke tabel hasil_ujian (paket_id NULL atau default 0)
$stmt2 = $conn->prepare("INSERT INTO hasil_ujian (user_id, paket_ujian_id, skor_twk, skor_tiu, skor_tkp, skor_kumulatif, status_lulus) VALUES (?, 0, 0, 0, ?, ?, ?)");
$status = $skor >= PG_PSIKOLOGI ? 'lulus' : 'gugur';
$stmt2->bind_param('iiis', $user_id, $skor, $skor, $status);
$stmt2->execute();
$hasil_id = $stmt2->insert_id;

flash('success', "Hasil Tes Psikologi ($jenis): Skor $skor. Status: " . strtoupper($status));
redirect('peserta/psikologi_hasil.php');
