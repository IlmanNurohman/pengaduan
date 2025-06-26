<?php
$host = 'localhost';
$user = 'u637089379_lapordesa';
$pass = '';
$db   = 'pengaduan';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die('Koneksi gagal: ' . $conn->connect_error);
}

// Cek apakah request berupa JSON (offline sync)
$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
if ($contentType === "application/json") {
    // Ambil data JSON dari body
    $data = json_decode(file_get_contents("php://input"), true);

    $apbd_id      = $data['id_apbd'];
    $tahun        = $data['tahun'];
    $jumlah_total = $data['jumlah_total'];
    $kategori     = array_column($data['rincian'], 'kategori');
    $jumlah       = array_column($data['rincian'], 'jumlah');
} else {
    // Ambil data dari form POST biasa
    $apbd_id      = $_POST['apbd_id'];
    $tahun        = $_POST['tahun'];
    $jumlah_total = $_POST['jumlah_total'];
    $kategori     = $_POST['kategori'];
    $jumlah       = $_POST['jumlah'];
}

// ✅ Update data utama di tabel apbd_desa
$stmt = $conn->prepare("UPDATE apbd_desa SET tahun_anggaran = ?, jumlah_total = ? WHERE id = ?");
$stmt->bind_param("iii", $tahun, $jumlah_total, $apbd_id);
$stmt->execute();
$stmt->close();

// ✅ Hapus rincian lama
$conn->query("DELETE FROM apbd_rincian WHERE apbd_id = $apbd_id");

// ✅ Simpan rincian baru
for ($i = 0; $i < count($kategori); $i++) {
    $kat = $conn->real_escape_string($kategori[$i]);
    $jum = (int) $jumlah[$i];

    $stmt_rincian = $conn->prepare("INSERT INTO apbd_rincian (apbd_id, kategori, jumlah) VALUES (?, ?, ?)");
    $stmt_rincian->bind_param("isi", $apbd_id, $kat, $jum);
    $stmt_rincian->execute();
    $stmt_rincian->close();
}

// ✅ Kirim response jika JSON
if ($contentType === "application/json") {
    echo json_encode(["status" => "success", "message" => "APBD berhasil diperbarui"]);
    exit;
}

// ✅ Redirect jika dari form biasa
header("Location: apbd.php?edit=1");
exit;
?>