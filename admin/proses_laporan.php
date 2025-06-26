<?php
$host = "localhost";
$user = "u637089379_lapordesa";
$pass = "Lapordesa123";
$db   = "u637089379_lapordesa";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Koneksi gagal: ' . $conn->connect_error]));
}

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validasi data wajib
    if (!isset($_POST['id'], $_POST['tanggapan'])) {
        echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap.']);
        exit;
    }

    $id = intval($_POST['id']); // pastikan id berupa integer
    $tanggapan = $conn->real_escape_string(trim($_POST['tanggapan']));
    $tanggal_sekarang = date("Y-m-d H:i:s");

    if (isset($_POST['terima'])) {
        $status = 'Diterima';
        $query = "UPDATE laporan SET status='$status', tanggapan='$tanggapan', tanggal_terima='$tanggal_sekarang' WHERE id='$id'";
    } elseif (isset($_POST['tolak'])) {
        $status = 'Ditolak';
        $query = "UPDATE laporan SET status='$status', tanggapan='$tanggapan' WHERE id='$id'";
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Aksi tidak valid.']);
        exit;
    }

    if ($conn->query($query) === TRUE) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan: ' . $conn->error]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Metode tidak diizinkan.']);
}
?>