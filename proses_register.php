<?php
// Pastikan ini hanya jalan saat form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Koneksi ke database
   $host = "mysql.railway.internal";
$user = "root";
$pass = "krhPptvTXVDpAZSpWmeEHfwpAISYMxmi";
$db   = "railway";
$port = "3306";

$koneksi = new mysqli($host, $user, $pass, $db, $port);

// Cek koneksi
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

    // Ambil data dari form dengan pengecekan
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $nama = $_POST['nama'] ?? '';
    $email    = $_POST['email'] ?? '';
    $alamat   = $_POST['alamat'] ?? '';
    $foto     = $_FILES['foto']['name'] ?? '';

    // Hash password
    $passwordHash = hash('sha256', $password);


    // Upload foto jika ada
    if ($foto != '') {
        $target = "uploads/" . basename($foto);
        move_uploaded_file($_FILES['foto']['tmp_name'], $target);
    }

    // Query simpan data ke tabel users
   $query = "INSERT INTO users (username, password,  nama, email, alamat, foto, level)
          VALUES ('$username', '$passwordHash', '$nama', '$email', '$alamat', '$foto', 'masyarakat')";


    if ($koneksi->query($query) === TRUE) {
        header("Location: register.html?status=success");
exit;

    } else {
        echo "Error: " . $koneksi->error;
    }

    $koneksi->close();
}
?>