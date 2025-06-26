<?php
header('Content-Type: application/json');

$host = "127.0.0.1";
$user = "u637089379_lapordesa";
$pass = "Lapordesa123";
$db   = "u637089379_lapordesa";
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Koneksi gagal: ' . $conn->connect_error]);
    exit;
}

$id     = $_POST['id'];
$nama   = $_POST['nama'];
$email  = $_POST['email'];
$jabatan= $_POST['jabatan'];
$alamat = $_POST['alamat'];

// Cek apakah ada file foto baru
if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
    $target_dir = "../uploads/";
    $foto_name = basename($_FILES["foto"]["name"]);
    $target_file = $target_dir . time() . "_" . $foto_name;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

    // Validasi file gambar
    $check = getimagesize($_FILES["foto"]["tmp_name"]);
    if ($check === false) {
        echo json_encode(['status' => 'error', 'message' => 'File bukan gambar.']);
        exit;
    }
    if (!in_array($imageFileType, $allowed_types)) {
        echo json_encode(['status' => 'error', 'message' => 'Hanya JPG, JPEG, PNG & GIF diperbolehkan.']);
        exit;
    }

    // Upload foto baru
    if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
        $sql = "UPDATE staf_desa SET nama=?, email=?, jabatan=?, alamat=?, foto=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $nama, $email, $jabatan, $alamat, $target_file, $id);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal mengupload foto.']);
        exit;
    }
} else {
    // Update tanpa mengubah foto
    $sql = "UPDATE staf_desa SET nama=?, email=?, jabatan=?, alamat=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $nama, $email, $jabatan, $alamat, $id);
}

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Data staf berhasil diupdate.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Gagal update staf: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>