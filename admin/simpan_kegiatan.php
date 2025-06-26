<?php
$koneksi = new mysqli("127.0.0.1", "u637089379_lapordesa", "u637089379_lapordesa", "Lapordesa123");

$nama_kegiatan = $_POST['nama_kegiatan'];
$tanggal_kegiatan = $_POST['tanggal_kegiatan']; // ambil input tanggal
$foto = $_FILES['foto']['name'];
$tmp = $_FILES['foto']['tmp_name'];

$folder = "../uploads/"; // menyimpan di folder uploads di root
$path_foto = $folder . basename($foto);

if (move_uploaded_file($tmp, $path_foto)) {
    // Simpan hanya path relatif dari index.php
    $path_in_db = "uploads/" . basename($foto);

    $query = "INSERT INTO kegiatan (nama_kegiatan, tanggal_kegiatan, foto) 
              VALUES ('$nama_kegiatan', '$tanggal_kegiatan', '$path_in_db')";
              
    if ($koneksi->query($query)) {
        header("Location: tambah_kegiatan.php?status=tambah_sukses");
    } else {
        echo "Gagal menyimpan ke database: " . $koneksi->error;
    }
} else {
    echo "Gagal upload gambar.";
}

?>