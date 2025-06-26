<?php
$host = "127.0.0.1";
$user = "u637089379_lapordesa";
$pass = "Lapordesa123";
$db   = "u637089379_lapordesa";
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data dari form
$id = $_POST['id'];
$nama = $_POST['nama'];
$username = $_POST['username'];
$passwordInput = $_POST['password'];
$email = $_POST['email'];
$level = $_POST['level'];
$alamat = $_POST['alamat'];
$file_name = null; // default jika tidak ada foto baru

// Proses foto jika ada
if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
    $target_dir = "../uploads/";
    $file_name = basename($_FILES["foto"]["name"]);
    $target_file = $target_dir . $file_name;

    if (!move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
        echo "Gagal mengupload foto.";
        exit;
    }
}

// Proses password jika diisi
$password = null;
if (!empty($passwordInput)) {
    $password = hash('sha256', $passwordInput); // Ganti hash ke SHA-256
}

// Bangun query dinamis
if ($password && $file_name) {
    $sql = "UPDATE users SET nama=?, username=?, password=?, email=?, level=?, alamat=?, foto=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssi", $nama, $username, $password, $email, $level, $alamat, $file_name, $id);
} elseif ($password) {
    $sql = "UPDATE users SET nama=?, username=?, password=?, email=?, level=?, alamat=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $nama, $username, $password, $email, $level, $alamat, $id);
} elseif ($file_name) {
    $sql = "UPDATE users SET nama=?, username=?, email=?, level=?, alamat=?, foto=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $nama, $username, $email, $level, $alamat, $file_name, $id);
} else {
    $sql = "UPDATE users SET nama=?, username=?, email=?, level=?, alamat=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $nama, $username, $email, $level, $alamat, $id);
}

// Eksekusi query
if ($stmt->execute()) {
    echo "Data berhasil diupdate";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>