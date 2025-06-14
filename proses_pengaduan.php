<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php'; // PHPMailer autoload

$host = "localhost";
$user = "root";
$pass = "";
$db   = "pengaduan";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Cek login
if (!isset($_SESSION['user_id'])) {
    die("Akses ditolak. Silakan login terlebih dahulu.");
}

// Ambil data dari session
$user_id = $_SESSION['user_id'];
$nama    = $_SESSION['nama'];
$email   = $_SESSION['email'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $alamat = $_POST['alamat'];
    $pesan  = $_POST['pesan'];
    $tanggal_lapor = $_POST['tanggal'];
    $foto = '';

    // Validasi tanggal
    if (!DateTime::createFromFormat('Y-m-d', $tanggal_lapor)) {
        die("Format tanggal tidak valid.");
    }

    // Proses upload foto jika ada
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    // Validasi ukuran file maksimal 2MB
    if ($_FILES['foto']['size'] > 2 * 1024 * 1024) {
        die("Ukuran file terlalu besar. Maksimal 2MB.");
    }

    $folder = 'uploads/';
    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }

    $foto = basename($_FILES['foto']['name']);
    move_uploaded_file($_FILES['foto']['tmp_name'], $folder . $foto);
}


    // Masukkan ke database
    $stmt = $conn->prepare("INSERT INTO laporan (user_id, nama, email, alamat, pesan, foto, tanggal_lapor) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssss", $user_id, $nama, $email, $alamat, $pesan, $foto, $tanggal_lapor);

    if ($stmt->execute()) {
        // Kirim email ke admin sebagai notifikasi
        

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'webpengaduanmasyarakat@gmail.com'; // Ganti dengan email pengirim
            $mail->Password   = 'wnxu zswz znyq bkjc';    // Gunakan app password
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom('webpengaduanmasyarakat@gmail.com', 'Sistem Pengaduan');
            $mail->addAddress('ilmannurohman1220@gmail.com', 'sekdes'); // Ganti dengan email admin tujuan

            $mail->isHTML(true);
            $mail->Subject = 'Pengaduan Baru Masuk';
            $mail->Body    = "
                <h3>Pengaduan Baru Telah Dikirim</h3>
                <p><strong>Nama:</strong> $nama</p>
                <p><strong>Email:</strong> $email</p>
                <p><strong>Alamat:</strong> $alamat</p>
                <p><strong>Pesan:</strong><br>$pesan</p>
                <p><strong>Tanggal Lapor:</strong> $tanggal_lapor</p>
            ";

            $mail->send();
            header("Location: user.php?pengaduan=berhasil");
exit();

        } catch (Exception $e) {
            echo "Data berhasil disimpan, tetapi email tidak terkirim. Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "Gagal menyimpan data: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>