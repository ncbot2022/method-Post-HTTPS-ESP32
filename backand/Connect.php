<?php
// Koneksi ke database
$host = "localhost";
$user = "root";
$pass = "";
$db   = "powermeter";

$conn = new mysqli($host, $user, $pass, $db);

// Cek koneksi
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Koneksi gagal: " . $conn->connect_error]));
}
