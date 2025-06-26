<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "pengaduan";
$conn = new mysqli($host, $user, $pass, $db);

// Pastikan metode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $nama = $_POST['nama_kegiatan'] ?? '';
    $tanggal = $_POST['tanggal_kegiatan'] ?? '';

    if (!$id) {
        echo "ID tidak ditemukan";
        exit;
    }

    // Jika foto ada
    if (!empty($_FILES['foto']['name'])) {
        $foto = 'uploads/' . basename($_FILES['foto']['name']);
        move_uploaded_file($_FILES['foto']['tmp_name'], '../' . $foto);

        $query = "UPDATE kegiatan SET nama_kegiatan=?, tanggal_kegiatan=?, foto=? WHERE id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssi", $nama, $tanggal, $foto, $id);
    } else {
        // Jika foto tidak dikirim
        $query = "UPDATE kegiatan SET nama_kegiatan=?, tanggal_kegiatan=? WHERE id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssi", $nama, $tanggal, $id);
    }

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Metode tidak diizinkan";
}
?>