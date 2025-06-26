<?php
$host = "127.0.0.1";
$user = "u637089379_lapordesa";
$pass = "Lapordesa123";
$db   = "u637089379_lapordesa";
$conn = new mysqli($host, $user, $pass, $db);

// Set header JSON
header('Content-Type: application/json');

if ($conn->connect_error) {
    echo json_encode(['error' => 'Koneksi gagal: ' . $conn->connect_error]);
    exit;
}

// Cek jika ID diberikan
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Ambil data user berdasarkan ID
    $query = "SELECT id, nama, email, alamat, username, password, foto, level, reset_token FROM users WHERE id = $id";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $data = $result->fetch_assoc();
        echo json_encode($data);
    } else {
        echo json_encode(['error' => 'Data tidak ditemukan']);
    }
} else {
    echo json_encode(['error' => 'ID tidak diberikan']);
}
?>