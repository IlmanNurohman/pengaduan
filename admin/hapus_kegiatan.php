<?php
$host = "127.0.0.1";
$user = "u637089379_lapordesa";
$pass = "Lapordesa123";
$db   = "u637089379_lapordesa";
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    // Hapus data berdasarkan id
    $query = "DELETE FROM kegiatan WHERE id = $id";
    if ($conn->query($query)) {
        header("Location: tambah_kegiatan.php?status=tambah_sukses");
    } else {
        echo "<script>alert('Gagal menghapus data'); window.history.back();</script>";
    }
} else {
    echo "ID tidak ditemukan.";
}