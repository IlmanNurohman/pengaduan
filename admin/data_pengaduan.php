<?php
$koneksi = new mysqli("localhost", "root", "", "pengaduan");

$query = "SELECT MONTH(tanggal_lapor) AS bulan, COUNT(*) AS jumlah 
          FROM laporan 
          GROUP BY MONTH(tanggal_lapor)";
$result = $koneksi->query($query);

$labels = [];
$data = [];

$bulan_nama = [
    1 => "Januari", 2 => "Februari", 3 => "Maret", 4 => "April",
    5 => "Mei", 6 => "Juni", 7 => "Juli", 8 => "Agustus",
    9 => "September", 10 => "Oktober", 11 => "November", 12 => "Desember"
];

while ($row = $result->fetch_assoc()) {
    $labels[] = $bulan_nama[(int)$row['bulan']];
    $data[] = (int)$row['jumlah'];
}

echo json_encode([
    "labels" => $labels,
    "data" => $data
]);
?>