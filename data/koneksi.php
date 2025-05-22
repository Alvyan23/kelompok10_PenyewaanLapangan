<?php
$host = "localhost";
$user = "root";  // ganti jika berbeda
$pass = "";      // ganti jika ada password
$db   = "persewaan_lapangan";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($host, $user, $pass, $db);
    $conn->set_charset("utf8mb4");  // Set encoding agar aman

    // Jika berhasil koneksi, bisa lanjutkan...
} catch (mysqli_sql_exception $e) {
    die("Koneksi gagal: " . $e->getMessage());
}
?>

