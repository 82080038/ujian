<?php
require_once __DIR__ . '/config.php';
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) die("DB Error");

echo "=== FIX TOPIK SOAL PSIKOLOGI (lowercase -> Title Case) ===\n";

// Update topik soal psikologi
$updates = [
    "UPDATE soal SET topik = 'Wartegg' WHERE jenis_tes = 'psikologi' AND topik = 'wartegg'",
    "UPDATE soal SET topik = 'EPPS' WHERE jenis_tes = 'psikologi' AND topik = 'epps'",
];

foreach ($updates as $sql) {
    if ($conn->query($sql)) {
        echo "[OK] " . $conn->affected_rows . " rows updated\n";
    } else {
        echo "[FAIL] " . $conn->error . "\n";
    }
}

echo "\n[DONE]\n";
$conn->close();
