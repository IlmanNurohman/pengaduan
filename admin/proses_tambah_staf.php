<?php
// Koneksi ke database
$host = "localhost";
$user = "root";
$pass = "";
$db   = "pengaduan"; // Ganti dengan nama database kamu

$conn = new mysqli($host, $user, $pass, $db);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data dari form
$nama    = $_POST['nama'];
$email   = $_POST['email'];
$jabatan = $_POST['jabatan'];
$alamat  = $_POST['alamat'];

// Upload foto
$target_dir = "../uploads/"; // Menyesuaikan path karena folder uploads di luar admin
$foto_name = basename($_FILES["foto"]["name"]);
$target_file = $target_dir . time() . "_" . $foto_name;
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Cek apakah file benar-benar gambar
$check = getimagesize($_FILES["foto"]["tmp_name"]);
if($check === false) {
    echo "File bukan gambar.";
    $uploadOk = 0;
}

// Batasi tipe file
$allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
if (!in_array($imageFileType, $allowed_types)) {
    echo "Hanya file JPG, JPEG, PNG & GIF yang diperbolehkan.";
    $uploadOk = 0;
}

// Upload file
if ($uploadOk == 1) {
    if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
        // Simpan ke database
       $stmt = $conn->prepare("INSERT INTO staf_desa (nama, email, jabatan, alamat, foto) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $nama, $email, $jabatan, $alamat, $target_file);


       if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Staf baru berhasil ditambahkan.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan staf: ' . $stmt->error]);
}

        $stmt->close();
    } else {
        echo "Terjadi kesalahan saat mengupload file.";
    }
}

$conn->close();
?>