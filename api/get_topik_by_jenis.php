<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();
header('Content-Type: application/json');

$jenis = $_GET['jenis'] ?? '';
if (!in_array($jenis, ['twk','tiu','tkp'])) {
    echo json_encode([]);
    exit;
}

$res = $conn->query("SELECT DISTINCT topik FROM soal WHERE jenis_tes = '$jenis' ORDER BY topik");
$topik = [];
while ($r = $res->fetch_assoc()) $topik[] = $r['topik'];
echo json_encode($topik);
