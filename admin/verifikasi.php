<?php
$host = "localhost";
$user = "u637089379_lapordesa";
$pass = "Lapordesa123";
$db   = "u637089379_lapordesa";
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if (!isset($_GET['id'])) {
    echo "ID tidak ditemukan!";
    exit;
}

$id = intval($_GET['id']);
$query = $conn->query("SELECT * FROM laporan WHERE id = $id");
$data = $query->fetch_assoc();

if (!$data) {
    echo "Data laporan tidak ditemukan!";
    exit;
}
$tanggal_lapor = !empty($data['tanggal_lapor']) ? date("d-m-Y", strtotime($data['tanggal_lapor'])) : '-';
$tanggal_terima = !empty($data['tanggal_terima']) ? date("d-m-Y", strtotime($data['tanggal_terima'])) : '-';

?>

<!DOCTYPE html>
<html>

<head>
    <title>Verifikasi Laporan</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        padding: 40px;
        background: #f5f5f5;
    }

    .container {
        width: 80%;
        margin: auto;
        font-family: Arial, sans-serif;
    }

    h2 {
        text-align: center;
    }

    .valid {
        color: green;
        font-weight: bold;
        text-align: center;
        margin-bottom: 20px;
    }

    .info table {
        width: auto;
        margin: 0 auto;
        margin-top: 35px;
        border-spacing: 10px 5px;
        /* 10px horizontal, 5px vertical */
    }

    .info td:first-child {
        font-weight: bold;
        white-space: nowrap;
    }

    .info td:nth-child(2) {
        width: 10px;
        /* untuk tanda ":" */
        text-align: right;
    }

    .info td:nth-child(3) {
        white-space: nowrap;
    }

    .ttd {
        text-align: center;
        margin-top: 100px;
    }

    .ttd img {
        width: 150px;
        height: auto;
        display: block;
        margin: 10px auto;
    }
    </style>
</head>

<body>
    <div class="container">
        <h2>Verifikasi Laporan Pengaduan</h2>

        <div class="valid">✅ Laporan Ini Valid dan Terdaftar</div>

        <div class="info">
            <table>
                <tr>
                    <td>Nama Pelapor</td>
                    <td>:</td>
                    <td><?= htmlspecialchars($data['nama']) ?></td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>:</td>
                    <td><?= htmlspecialchars($data['alamat']) ?></td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td>:</td>
                    <td><?= htmlspecialchars($data['status']) ?></td>
                </tr>
                <tr>
                    <td>Tanggal Lapor</td>
                    <td>:</td>
                    <td><?= $tanggal_lapor ?></td>
                </tr>
                <tr>
                    <td>Tanggal Diterima</td>
                    <td>:</td>
                    <td><?= $tanggal_terima ?></td>
                </tr>
            </table>
        </div>

        <div class="ttd">
            <p><strong>Suharidana</strong></p>
            <img src="assets/img/ttd kades.png" alt="Tanda Tangan">
            <p>Kepala Desa Purwajaya</p>
        </div>
    </div>

</body>

</html>