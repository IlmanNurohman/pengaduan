<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "pengaduan";
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$id = $_POST['id'];
$nama = $_POST['nama'];
$email = $_POST['email'];
$jabatan = $_POST['jabatan'];
$alamat = $_POST['alamat'];

// Cek apakah ada file foto diupload
if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
    $target_dir = "../uploads/";
    $file_name = basename($_FILES["foto"]["name"]);
    $target_file = $target_dir . $file_name;

    // Pindahkan file
    if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
        // Update dengan foto baru (tanpa nik)
        $sql = "UPDATE staf_desa SET nama=?, email=?, jabatan=?, alamat=?, foto=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $nama, $email, $jabatan, $alamat, $file_name, $id);
    } else {
        echo "Gagal mengupload foto.";
        exit;
    }
} else {
    // Update tanpa mengubah foto (tanpa nik)
    $sql = "UPDATE staf_desa SET nama=?, email=?, jabatan=?, alamat=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $nama, $email, $jabatan, $alamat, $id);
}

if ($stmt->execute()) {
    echo "Data berhasil diupdate";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>