<?php
// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "pengaduan");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Cek apakah data dikirim via JSON
$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

if ($contentType === "application/json") {
    // Terima data dari fetch offline
    $content = trim(file_get_contents("php://input"));
    $data = json_decode($content, true);

    $tahun = $data['tahun'];
    $jumlah_total = $data['jumlah_total'];
    $rincian = $data['rincian'];
} else {
    // Data dari form biasa
    $tahun = $_POST['tahun'];
    $jumlah_total = $_POST['jumlah_total'];
    $rincian = [];
    if(isset($_POST['kategori']) && isset($_POST['jumlah'])){
        $kategori = $_POST['kategori'];
        $jumlah = $_POST['jumlah'];

        for ($i = 0; $i < count($kategori); $i++) {
            $rincian[] = [
                'kategori' => $kategori[$i],
                'jumlah' => $jumlah[$i]
            ];
        }
    }
}

// Simpan ke apbd_desa
$stmt = $conn->prepare("INSERT INTO apbd_desa (tahun_anggaran, jumlah_total) VALUES (?, ?)");
$stmt->bind_param("ii", $tahun, $jumlah_total);
$stmt->execute();
$apbd_id = $conn->insert_id;

// Simpan rincian
foreach ($rincian as $r) {
    $k = $r['kategori'];
    $j = $r['jumlah'];
    $stmt_rincian = $conn->prepare("INSERT INTO apbd_rincian (apbd_id, kategori, jumlah) VALUES (?, ?, ?)");
    $stmt_rincian->bind_param("isi", $apbd_id, $k, $j);
    $stmt_rincian->execute();
}

echo "Data APBD berhasil disimpan.";
exit;
?>