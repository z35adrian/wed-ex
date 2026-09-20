<?php
// ==========================================
// KONEKSI DATABASE
// ==========================================
$host     = "localhost";
$username = "root";
$password = ""; // Kosongkan jika menggunakan XAMPP default
$dbname   = "digivite";
$link_id  = 1; // Sesuaikan dengan Link ID di halaman edit wedding

$conn = new mysqli($host, $username, $password, $dbname);

// Cek Koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>