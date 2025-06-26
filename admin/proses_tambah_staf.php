<?php
header('Content-Type: application/json');

// Koneksi ke database
$host = "localhost";
$user = "root";
$pass = "";
$db   = "pengaduan";
$conn = new mysqli($host, $user, $pass, $db);

// Cek koneksi
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Koneksi gagal: ' . $conn->connect_error]);
    exit;
}

// Ambil data dari form
$nama    = $_POST['nama'];
$email   = $_POST['email'];
$jabatan = $_POST['jabatan'];
$alamat  = $_POST['alamat'];

$target_file = "";
if(isset($_FILES["foto"]) && $_FILES["foto"]["error"] == 0){
    $target_dir = "../uploads/";
    $foto_name = basename($_FILES["foto"]["name"]);
    $target_file = $target_dir . time() . "_" . $foto_name;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Validasi gambar
    $check = getimagesize($_FILES["foto"]["tmp_name"]);
    if ($check === false) {
        echo json_encode(['status' => 'error', 'message' => 'File bukan gambar.']);
        exit;
    }

    // Batasi tipe file
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($imageFileType, $allowed_types)) {
        echo json_encode(['status' => 'error', 'message' => 'Hanya JPG, JPEG, PNG & GIF diperbolehkan.']);
        exit;
    }

    // Upload dan simpan ke DB
    if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
        $stmt = $conn->prepare("INSERT INTO staf_desa (nama, email, jabatan, alamat, foto) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $nama, $email, $jabatan, $alamat, $target_file);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Terjadi kesalahan saat mengupload file.']);
        exit;
    }
} else {
    // Tidak ada file foto dikirim
    $stmt = $conn->prepare("INSERT INTO staf_desa (nama, email, jabatan, alamat, foto) VALUES (?, ?, ?, ?, ?)");
    // kosongkan nilai foto
    $emptyFoto = "";
    $stmt->bind_param("sssss", $nama, $email, $jabatan, $alamat, $emptyFoto);
}

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Staf baru berhasil ditambahkan.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan staf: ' . $stmt->error]);
}

$stmt->close();
$conn->close();