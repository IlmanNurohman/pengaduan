<?php
header('Content-Type: application/json');

$conn = new mysqli("127.0.0.1", "u637089379_lapordesa", "u637089379_lapordesa", "Lapordesa123");
if ($conn->connect_error) {
    die(json_encode(['error' => 'Koneksi gagal']));
}

$tahun = isset($_GET['tahun']) ? (int)$_GET['tahun'] : date("Y");

$query = "
    SELECT r.kategori, SUM(r.jumlah) AS total
    FROM apbd_rincian r
    JOIN apbd_desa d ON r.apbd_id = d.id
    WHERE d.tahun_anggaran = $tahun
    GROUP BY r.kategori
";

$result = $conn->query($query);

$labels = [];
$data = [];

while ($row = $result->fetch_assoc()) {
    $labels[] = $row['kategori'];
    $data[] = (int)$row['total'];
}

echo json_encode([
    'labels' => $labels,
    'data' => $data,
    'debug' => [
        'tahun' => $tahun,
        'jumlah_data' => count($data)
    ]
]);
?>