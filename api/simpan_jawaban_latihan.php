<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';
header('Content-Type: application/json');

if (!isPeserta() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error']);
    exit;
}

$soal_id = intval($_POST['soal_id'] ?? 0);
$opsi_id = intval($_POST['opsi_id'] ?? 0);
$reveal = intval($_POST['reveal'] ?? 0);
$csrf = $_POST['csrf_token'] ?? '';

if (!verifyCSRF($csrf)) {
    echo json_encode(['status' => 'error']);
    exit;
}

if ($opsi_id > 0) {
    $_SESSION['latihan_jawaban'][$soal_id] = $opsi_id;
}
if ($reveal) {
    $_SESSION['latihan_reveal'][$soal_id] = true;
}

echo json_encode(['status' => 'ok']);
