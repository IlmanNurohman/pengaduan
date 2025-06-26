<?php
session_start();
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
$query = mysqli_query($conn, "SELECT * FROM laporan WHERE id = $id");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "Data tidak ditemukan!";
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Detail Laporan</title>

    <style>
    body {
        padding: 20px;
    }

    .kop-surat {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
        text-align: center;
        margin-bottom: 20px;
    }

    .kop-surat img {
        width: 90px;
        height: 90px;
        margin-right: 20px;
    }

    .kop-text {
        flex: 1;
        min-width: 250px;
    }

    .kop-text h1,
    .kop-text h2,
    .kop-text h3,
    .kop-text p {
        margin: 0;
        line-height: 1.2;
    }

    .line {
        border-top: 3px double black;
        margin: 20px 0;
        clear: both;
    }

    .section-title {
        text-align: center;
        font-weight: bold;
        margin-top: 20px;
        font-size: 18px;
    }

    table.detail td {
        padding: 8px;
        vertical-align: top;
        word-wrap: break-word;
        white-space: normal;
    }

    @media (max-width: 600px) {
        table.detail {
            font-size: 14px;
        }

        table.detail td {
            display: table-cell !important;
            width: auto !important;
        }
    }


    .foto img {
        max-width: 100%;
        height: auto;
    }

    .form-tanggapan {
        margin-top: 30px;
    }

    .form-tanggapan textarea {
        width: 100%;
        height: 120px;
        font-family: inherit;
        font-size: 16px;
        padding: 8px;
        box-sizing: border-box;
    }

    .form-tanggapan .buttons {
        text-align: center;
        margin-top: 15px;
        flex-wrap: wrap;
    }

    .btn_1 {
        padding: 10px 20px;
        font-size: 16px;
        margin: 5px;
        cursor: pointer;
        border: none;
        border-radius: 5px;
    }

    .btn-success_1 {
        background-color: #4CAF50;
        color: white;
    }

    .btn-danger_1 {
        background-color: #f44336;
        color: white;
    }

    .no-print {
        display: block;
    }

    #qr-section {
        margin-top: 30px;
        text-align: center;
    }

    #qrcode {
        margin: 10px auto;
        display: inline-block;
    }

    .hide {
        display: none !important;
    }

    /* RESPONSIVE */
    @media (max-width: 600px) {
        .kop-surat {
            flex-direction: column;
            align-items: center;
        }

        .kop-surat img {
            margin-right: 0;
            margin-bottom: 10px;
        }

        .kop-text {
            text-align: center;
        }

        table.detail td {
            display: block;
            width: 100%;
        }

        .form-tanggapan .buttons {
            flex-direction: column;
        }

        .btn_1 {
            width: 100%;
            max-width: 300px;
        }
    }
    </style>
</head>

<body>

    <div class="kop-surat">
        <img src="assets/img/garut.png" alt="Logo">
        <div class="kop-text">
            <h2>PEMERINTAH KABUPATEN GARUT</h2>
            <h3>KECAMATAN PEUNDEUY</h3>
            <h1>DESA PURWAJAYA</h1>
            <p>Jalan Desa Purwajaya No. 01 Peundeuy-Garut Post 44178</p>
        </div>
    </div>

    <div class="line"></div>

    <div class="section-title">LAPORAN PENGADUAN MASYARAKAT</div>

    <table class="detail">
        <tr>
            <td width="150px">Nama</td>
            <td>:</td>
            <td><?= htmlspecialchars($data['nama']) ?></td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>:</td>
            <td><?= htmlspecialchars($data['alamat']) ?></td>
        </tr>
        <tr>
            <td>Pesan</td>
            <td>:</td>
            <td><?= nl2br(htmlspecialchars($data['pesan'])) ?></td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>:</td>
            <td><?= htmlspecialchars($data['tanggal_lapor']) ?></td>
        </tr>
        <tr>
            <td>Status</td>
            <td>:</td>
            <td><?= htmlspecialchars($data['status']) ?></td>
        </tr>
        <tr>
            <td>Foto</td>
            <td>:</td>
            <td class="foto">
                <?php if (!empty($data['foto'])): ?>
                <img src="../uploads/<?= htmlspecialchars($data['foto']) ?>" alt="Foto">
                <?php else: ?>
                <em>Foto tidak tersedia</em>
                <?php endif; ?>
            </td>
        </tr>
    </table>

    <div class="form-tanggapan">
        <?php if (strtolower($data['status']) !== 'diterima'): ?>
        <?php if ($_SESSION['level'] == 'kades'): ?>
        <form id="form-tanggapan" method="post" action="proses_laporan.php">
            <input type="hidden" name="id" value="<?= $data['id'] ?>">
            <label for="tanggapan"><strong>Tanggapan</strong></label><br>
            <textarea name="tanggapan" id="tanggapan" required placeholder="Tulis tanggapan di sini..."></textarea>
            <div class="buttons">
                <button type="submit" name="terima" class="btn btn-success_1">Terima</button>
                <button type="submit" name="tolak" class="btn btn-danger_1">Tolak</button>
            </div>
        </form>
        <?php endif; ?>

        <?php endif; ?>
    </div>



    <?php if (strtolower($data['status']) === 'diterima'): ?>
    <div id="qr-section">
        <h4>Tanda Tangan Digital:</h4>
        <div id="qrcode"></div>
        <div class="hide">
            <strong>Ilman Nurohman<br>Kepala Desa Purwajaya</strong><br>
            <img src="assets/img/ttd kades.png" alt="Tanda Tangan" width="150">
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
    new QRCode(document.getElementById("qrcode"), {
        text: "http://localhost/sekdes/admin/verifikasi.php?id=<?= $data['id'] ?>",
        width: 128,
        height: 128
    });
    </script>
    <?php endif; ?>

</body>

</html>