<?php
// Setup database via PHP CLI
$host = 'localhost';
$user = 'root';
$pass = 'root';
$dbname = 'db_tryout';

// Connect without database
$conn = new mysqli($host, $user, $pass);
if ($conn->connect_error) {
    echo "CONNECT ERROR: " . $conn->connect_error . "\n";
    echo "MySQL root may require a password. Please update config.php DB_PASS.\n";
    exit(1);
}

// Create database
$conn->query("CREATE DATABASE IF NOT EXISTS $dbname CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
echo "Database $dbname created or exists.\n";

// Select database
$conn->select_db($dbname);

// Import schema
$sql = file_get_contents(__DIR__ . '/db_tryout.sql');
if ($conn->multi_query($sql)) {
    do {
        if ($res = $conn->store_result()) $res->free();
    } while ($conn->more_results() && $conn->next_result());
    echo "Schema imported.\n";
} else {
    echo "Schema import error: " . $conn->error . "\n";
}

// Ensure catatan_pengajar table exists
$conn->query("CREATE TABLE IF NOT EXISTS catatan_pengajar (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  catatan TEXT NOT NULL,
  created_by INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY unique_user (user_id)
)");

// Ensure tanya_admin table exists
$conn->query("CREATE TABLE IF NOT EXISTS tanya_admin (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  pertanyaan TEXT NOT NULL,
  jawaban TEXT DEFAULT NULL,
  dijawab_by INT DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  dijawab_at TIMESTAMP NULL DEFAULT NULL
)");

// Import seed data
$seed = file_get_contents(__DIR__ . '/db_seed.sql');
if ($conn->multi_query($seed)) {
    do {
        if ($res = $conn->store_result()) $res->free();
    } while ($conn->more_results() && $conn->next_result());
    echo "Seed data imported.\n";
} else {
    echo "Seed import error: " . $conn->error . "\n";
}

echo "Setup complete.\n";
$conn->close();
