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
$is_ragu = intval($_POST['is_ragu'] ?? -1);

if ($opsi_id > 0) {
    $_SESSION['mini_jawaban'][$soal_id] = $opsi_id;
}
if ($is_ragu >= 0) {
    if ($is_ragu) $_SESSION['mini_ragu'][$soal_id] = true;
    else unset($_SESSION['mini_ragu'][$soal_id]);
}

echo json_encode(['status' => 'ok']);
