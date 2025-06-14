<?php
$host = "mysql.railway.internal";
$user = "root";
$pass = "krhPptvTXVDpAZSpWmeEHfwpAISYMxmi";
$db   = "railway";
$port = "3306";

$koneksi = new mysqli($host, $user, $pass, $db, $port);

// Cek koneksi
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}


if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama_kegiatan'];
    $tanggal = $_POST['tanggal_kegiatan'];

    if (!empty($_FILES['foto']['name'])) {
        $foto = 'uploads/' . basename($_FILES['foto']['name']);
        move_uploaded_file($_FILES['foto']['tmp_name'], '../' . $foto);
        $query = "UPDATE kegiatan SET nama_kegiatan='$nama', tanggal_kegiatan='$tanggal', foto='$foto' WHERE id=$id";
    } else {
        $query = "UPDATE kegiatan SET nama_kegiatan='$nama', tanggal_kegiatan='$tanggal' WHERE id=$id";
    }

    if ($conn->query($query)) {
         header("Location: tambah_kegiatan.php?status=tambah_sukses");
    } else {
        echo "<script>alert('Gagal memperbarui data'); window.history.back();</script>";
    }
}