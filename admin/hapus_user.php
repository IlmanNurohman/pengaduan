<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "pengaduan";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Validasi ID dari parameter URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    // Hapus user berdasarkan ID
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Redirect kembali ke halaman daftar user
        header("Location: admin.php?pesan=sukses_hapus");
        exit;
    } else {
        echo "Gagal menghapus data: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "ID tidak valid.";
}

$conn->close();
?>