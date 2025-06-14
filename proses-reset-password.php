<?php
// Koneksi langsung ke database
$host = "localhost";     // Ganti jika berbeda
$user = "root";          // Username MySQL
$pass = "";              // Password MySQL
$db   = "pengaduan"; // Ganti dengan nama database kamu

$conn = new mysqli($host, $user, $pass, $db);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Validasi
    if ($newPassword !== $confirmPassword) {
        echo "Password tidak cocok.";
        exit;
    }

    // Cari pengguna berdasarkan token
    $query = mysqli_query($conn, "SELECT * FROM users WHERE reset_token='$token'");
    if (mysqli_num_rows($query) === 1) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        // Perbarui password dan hapus token
        mysqli_query($conn, "UPDATE users SET password='$hashedPassword', reset_token=NULL WHERE reset_token='$token'");
        echo "Password berhasil diubah. Silakan <a href='login.html'>login</a>.";
    } else {
        echo "Token tidak valid atau sudah digunakan.";
    }
}
?>