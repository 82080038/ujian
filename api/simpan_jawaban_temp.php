<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';

header('Content-Type: application/json');

if (!isPeserta()) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit;
}

$soal_id = intval($_POST['soal_id'] ?? 0);
$opsi_id = intval($_POST['opsi_id'] ?? 0);
$paket_id = intval($_POST['paket_id'] ?? 0);
$is_ragu = intval($_POST['is_ragu'] ?? 0);
$csrf = $_POST['csrf_token'] ?? '';

if (!verifyCSRF($csrf)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid CSRF token']);
    exit;
}

if (!$soal_id || !$paket_id) {
    echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap']);
    exit;
}

// Ambil bobot nilai opsi
$stmt = $conn->prepare('SELECT bobot_nilai FROM opsi_jawaban WHERE id = ? AND soal_id = ?');
$stmt->bind_param('ii', $opsi_id, $soal_id);
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();
$bobot = $res ? $res['bobot_nilai'] : 0;

// Ambil hasil_ujian_id yang sedang proses
$stmt2 = $conn->prepare('SELECT h.id FROM hasil_ujian h WHERE h.paket_ujian_id = ? AND h.user_id = ? AND h.status_lulus = "proses" ORDER BY h.id DESC LIMIT 1');
$stmt2->bind_param('ii', $paket_id, $_SESSION['user_id'] ?? 0);
$stmt2->execute();
$hasil = $stmt2->get_result()->fetch_assoc();

if (!$hasil) {
    echo json_encode(['status' => 'error', 'message' => 'Sesi ujian tidak ditemukan']);
    exit;
}

$hasil_id = $hasil['id'];

// Update detail_jawaban
$stmt3 = $conn->prepare('UPDATE detail_jawaban SET opsi_dipilih_id = ?, nilai_diperoleh = ?, is_ragu = ? WHERE hasil_ujian_id = ? AND soal_id = ?');
$stmt3->bind_param('iiiii', $opsi_id, $bobot, $is_ragu, $hasil_id, $soal_id);
$stmt3->execute();

echo json_encode(['status' => 'ok', 'bobot' => $bobot]);
