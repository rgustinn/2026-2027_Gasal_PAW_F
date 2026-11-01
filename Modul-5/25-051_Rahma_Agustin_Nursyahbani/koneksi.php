<?php
$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "penjualan";

try {
    $conn = mysqli_connect($servername,$username, $password,$database);
} catch (mysqli_sql_exception $e) {
    die("Koneksi Gagal: " . $e->getMessage());
}
?>