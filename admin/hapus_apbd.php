<?php
$conn = new mysqli("127.0.0.1", "u637089379_lapordesa", "u637089379_lapordesa", "Lapordesa123");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if (isset($_POST['apbd_id'])) {
    $apbd_id = intval($_POST['apbd_id']);
    $conn->query("DELETE FROM apbd_rincian WHERE apbd_id = $apbd_id");
    $conn->query("DELETE FROM apbd_desa WHERE id = $apbd_id");
    echo "ok"; // kirim respons ke JavaScript
}
?>