<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "pengaduan";
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$id = $_GET['id'] ?? null;

if ($id) {
    $delete = $conn->prepare("DELETE FROM staf_desa WHERE id = ?");
    $delete->bind_param("i", $id);
    $delete->execute();
}

header("Location: tambah_staf.php?hapus=berhasil");
exit;

?>