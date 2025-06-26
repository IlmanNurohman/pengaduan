<?php
// Jika belum ada, jalankan di root project:
// composer require phpmailer/phpmailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php';  // sesuaikan path ke autoload.php

// 1. Koneksi langsung
$host = "127.0.0.1";
$user = "u637089379_lapordesa";
$pass = "Lapordesa123";
$db   = "u637089379_lapordesa";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// 2. Proses form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    if (! $email) {
        exit("Email tidak valid.");
    }

    // 3. Cek user
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 0) {
        exit("Email tidak ditemukan.");
    }

    // 4. Generate & simpan token
    $token = bin2hex(random_bytes(32));
    $upd = $conn->prepare("UPDATE users SET reset_token = ? WHERE email = ?");
    $upd->bind_param("ss", $token, $email);
    $upd->execute();

    // 5. Siapkan link reset (sesuaikan jika di dalam folder /sekdes/)
    $resetLink = "https://lapordesa.site/sekdes/reset-password.php?token=" . urlencode($token);

    // 6. Kirim email via SMTP Gmail
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = '2106083@itg.ac.id';            // ganti
        $mail->Password   = 'ykvp gzvh xqxf gyea';        // App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('2106083@itg.ac.id', 'Web Pengaduan');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Permintaan Reset Password';
        $mail->Body    = "
            <p>Halo,</p>
            <p>Kami menerima permintaan untuk mereset password akun <b>{$email}</b>.</p>
            <p>Silakan klik link berikut untuk mengatur ulang password Anda:</p>
            <p><a href='{$resetLink}'>Reset Password</a></p>
            <p>Jika Anda tidak meminta ini, abaikan saja email ini.</p>
            <hr>
            <p>Web Pengaduan Masyarakat</p>
        ";

        $mail->send();
        echo "Link reset password telah dikirim ke email Anda.";
    } catch (Exception $e) {
        echo "Gagal mengirim email: {$mail->ErrorInfo}";
    }

    $stmt->close();
    $upd->close();
}

$conn->close();
?>