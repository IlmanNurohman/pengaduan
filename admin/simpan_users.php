<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'pengaduan';

// Koneksi ke database
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die('Koneksi gagal: ' . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama     = $_POST['nama'];
    $username = $_POST['username'];

    // Hash password dengan SHA-256
    $password_plain = $_POST['password'];
    $password = hash('sha256', $password_plain);

    $email    = $_POST['email'];
    $level    = $_POST['level'];
    $alamat   = $_POST['alamat'];

    // Proses upload foto
    $foto_name = '';
    if (!empty($_FILES['foto']['name'])) {
        $foto_tmp  = $_FILES['foto']['tmp_name'];
        $foto_name = uniqid() . '-' . basename($_FILES['foto']['name']);

        $upload_dir = __DIR__ . '/../uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        if (!move_uploaded_file($foto_tmp, $upload_dir . $foto_name)) {
            die('Gagal mengunggah foto.');
        }
    }

    // Simpan ke database
    $stmt = $conn->prepare("INSERT INTO users (nama, username, password, email, level, alamat, foto) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $nama, $username, $password, $email, $level, $alamat, $foto_name);

    if ($stmt->execute()) {
        header("Location: admin.php?success=1");
        exit;
    } else {
        echo "Gagal menyimpan data: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Akses tidak diizinkan.";
}
?>