<?php
require_once 'koneksi.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    $query = "DELETE FROM supplier WHERE id = $id";
    mysqli_query($conn, $query);
}

header("Location: index.php");
exit();
?>