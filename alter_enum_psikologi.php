<?php
require_once __DIR__ . '/config.php';
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) die("DB Error: " . $conn->connect_error);

echo "=== ALTER ENUM JENIS_TES - TAMBAH PSIKOLOGI ===\n\n";

$tables = [
    'soal' => "ALTER TABLE soal MODIFY jenis_tes ENUM('twk','tiu','tkp','psikologi') NOT NULL",
    'materi' => "ALTER TABLE materi MODIFY jenis_tes ENUM('twk','tiu','tkp','psikologi') DEFAULT NULL",
    'rekomendasi_belajar' => "ALTER TABLE rekomendasi_belajar MODIFY jenis_tes ENUM('twk','tiu','tkp','psikologi') NOT NULL",
];

foreach ($tables as $table => $sql) {
    if ($conn->query($sql)) {
        echo "[OK] Table `$table` enum diperbarui + 'psikologi'\n";
    } else {
        echo "[FAIL] Table `$table`: " . $conn->error . "\n";
    }
}

// Update config.php passing grade (tambah define via migration marker)
// Note: user perlu edit config.php manual untuk PG_PSIKOLOGI

echo "\n[INFO] Jangan lupa tambahkan di config.php:\n";
echo "define('PG_PSIKOLOGI', 100); // passing grade psikologi\n";
echo "\n[DONE]\n";

$conn->close();
