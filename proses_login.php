<?php
// Aktifkan session
session_start();

// Koneksi ke database
$host = "localhost";
$user = "u637089379_lapordesa";
$pass = "Lapordesa123";
$db   = "u637089379_lapordesa";

$conn = new mysqli($host, $user, $pass, $db);

// Cek koneksi
if ($conn->connect_error) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Koneksi database gagal.'
    ]);
    exit;
}

// Ambil data dari POST
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// Cek ke database
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

// Jika user ditemukan
if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    // Cek password (disesuaikan jika pakai hash)
   if ($user['password'] === hash('sha256', $password))
 {
        $_SESSION['username'] = $user['username'];
        $_SESSION['level'] = $user['level'];

        echo json_encode([
            'status' => 'success',
            'user' => [
                'username' => $user['username'],
                'level' => $user['level'],
                'password' => $user['password'] // disimpan sementara untuk offline
            ]
        ]);
    } else {
        echo json_encode([
            'status' => 'fail',
            'message' => 'Password salah.'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'fail',
        'message' => 'User tidak ditemukan.'
    ]);
}
?>