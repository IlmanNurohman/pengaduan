<?php
session_start(); // Tambahkan ini untuk mulai session
$servername = "localhost";
$username = "root";
$password = "";
$database = "pengaduan";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    die("Akses ditolak. Silakan login terlebih dahulu.");
}
$user_id = $_SESSION['user_id'];
$query = mysqli_query($conn, "SELECT foto FROM users WHERE id = '$user_id'");
$data = mysqli_fetch_assoc($query);
$foto = $data['foto']; // fallback jika foto kosong
$user_id = $_SESSION['user_id']; // Ambil user_id dari session

if (isset($_POST['simpan'])) {
    $nama     = $_POST['nama'];
    $email    = $_POST['email'];
    $alamat   = $_POST['alamat'];
    $username = $_POST['username'];
    $level    = $_POST['level'];
    $password = !empty($_POST['password']) ? hash('sha256', $_POST['password']) : null;


    $fotoBaru = $foto; // default ke foto lama

    // Proses upload foto jika ada
    if (!empty($_FILES['foto']['name'])) {
        $foto_name = $_FILES['foto']['name'];
        $tmp_name  = $_FILES['foto']['tmp_name'];
        $ext       = pathinfo($foto_name, PATHINFO_EXTENSION);
        $fotoBaru  = uniqid('foto_') . '.' . $ext;
        $uploadDir = 'uploads/' . $fotoBaru;

        // Hapus foto lama jika bukan default
        if ($profil['foto'] && file_exists('uploads/' . $profil['foto']) && $profil['foto'] !== 'default.png') {
            unlink('uploads/' . $profil['foto']);
        }

        move_uploaded_file($tmp_name, $uploadDir);
    }

    // Bangun query update
    $query = "UPDATE users SET 
        nama='$nama',
        email='$email',
        alamat='$alamat',
        username='$username',
        level='$level',
        foto='$fotoBaru'";

    if ($password) {
        $query .= ", password='$password'";
    }

    $query .= " WHERE id='$user_id'";

    $update = mysqli_query($conn, $query);

    if ($update) {
    $_SESSION['update_success'] = true;
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
} else {
    $_SESSION['update_failed'] = true;
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

}



?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="admin/css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link href="css/bootstrap-icons.css" rel="stylesheet" />
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="index.html">
            <img src="images/garut.png" alt="" style="height: 30px; margin-right: 10px;">
            Desa Purwajaya
        </a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i
                class="fas fa-bars"></i></button>
        <!-- Navbar Search-->
        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">

        </form>


        <!-- Navbar-->
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <img src="uploads/<?php echo $foto; ?>" class="rounded-circle" width="35" height="35"
                        alt="Foto Profil">
                </a>

                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="profil.php"><i class="bi bi-person me-1"></i>Profil</a>
                    </li>
                    <li>
                        <hr class="dropdown-divider" />
                    </li>
                    <li><a class="dropdown-item" href="index.php"><i class="bi bi-door-open me-1"></i>Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">

                        <div class="sb-sidenav-menu-heading">Menu</div>

                        <?php if ($_SESSION['level'] == 'masyarakat') : ?>
                        <!-- Menu khusus masyarakat -->
                        <a class="nav-link" href="user.php">
                            <div class="sb-nav-link-icon"><i class="bi-columns-gap"></i></div>
                            Pengaduan
                        </a>

                        <?php elseif ($_SESSION['level'] == 'sekdes' || $_SESSION['level'] == 'kades') : ?>
                        <!-- Menu khusus sekdes dan kades -->
                        <a class="nav-link" href="admin/index.php">
                            <div class="sb-nav-link-icon"><i class="bi-columns-gap"></i></div>
                            Dashboard
                        </a>

                        <div class="sb-sidenav-menu-heading">Pengaduan</div>
                        <a class="nav-link" href="daftar_laporan.php">
                            <div class="sb-nav-link-icon"><i class="bi bi-card-list"></i></div>
                            Daftar Pengaduan
                        </a>

                        <?php if ($_SESSION['level'] == 'sekdes'): ?>
                        <div class="sb-sidenav-menu-heading">Manajeman</div>
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                            data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                            <div class="sb-nav-link-icon"><i class="bi bi-briefcase"></i></div>
                            Kelola Data
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne"
                            data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="tambah_staf.php">Data Staf</a>
                                <a class="nav-link" href="apbd.php">Data APBD</a>
                                <a class="nav-link" href="tambah_kegiatan.php">Data Kegiatan</a>
                            </nav>
                        </div>
                        <?php endif; ?>
                        <?php endif; ?>

                    </div>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h2 class="mt-4">Profil</h2>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active"><i class="bi bi-person"></i>Profil</li>
                    </ol>

                    <?php
    // Ambil data user dari database
    $query_profil = mysqli_query($conn, "SELECT * FROM users WHERE id = '$user_id'");
    $profil = mysqli_fetch_assoc($query_profil);
    ?>

                    <!-- Container tambahan untuk form profil -->
                    <div class="container bg-light p-4 rounded shadow-sm">
                        <form action="" method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <!-- Kolom Kiri: Foto Profil -->
                                <div class="col-md-4 text-center">
                                    <?php if ($profil['foto']) : ?>
                                    <img src="uploads/<?= $profil['foto']; ?>" alt="Foto Profil"
                                        class="img-thumbnail mb-3"
                                        style="width: 200px; height: 250px; object-fit: cover;">

                                    <?php else : ?>
                                    <img src="uploads/default.png" alt="Foto Profil" class="img-thumbnail mb-3"
                                        style="max-width: 100%; height: auto;">
                                    <?php endif; ?>
                                    <div class="mb-3">
                                        <label for="foto" class="form-label">Ganti Foto Profil</label>
                                        <input type="file" class="form-control" id="foto" name="foto">
                                    </div>
                                </div>

                                <!-- Kolom Kanan: Form -->
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label for="nama" class="form-label">Nama Lengkap</label>
                                        <input type="text" class="form-control" id="nama" name="nama"
                                            value="<?= $profil['nama']; ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            value="<?= $profil['email']; ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="alamat" class="form-label">Alamat</label>
                                        <textarea class="form-control" id="alamat" name="alamat"
                                            rows="3"><?= $profil['alamat']; ?></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Username</label>
                                        <input type="text" class="form-control" id="username" name="username"
                                            value="<?= $profil['username']; ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password </label>
                                        <input type="password" class="form-control" id="password" name="password"
                                            placeholder="(Kosongkan jika tidak diubah)">
                                    </div>
                                    <div class="mb-3">
                                        <label for="level" class="form-label">Level</label>
                                        <input type="text" class="form-control" id="level" name="level"
                                            value="<?= $profil['level']; ?>" readonly required>
                                    </div>
                                    <button type="submit" name="simpan" class="btn btn-primary">Simpan
                                        Perubahan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Modal Notifikasi Update Profil -->
                <div class="modal fade" id="notifikasiModal" tabindex="-1" aria-labelledby="notifikasiModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content text-center p-4">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                                </div>
                                <h5 class="modal-title mb-2" id="notifikasiModalLabel">Data Berhasil Diperbarui</h5>
                                <p class="text-muted">Perubahan profil Anda telah disimpan.</p>
                                <button type="button" class="btn btn-success mt-3"
                                    data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>

            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Your Website 2025</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script src="admin/js/scripts.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <?php if (isset($_SESSION['update_success'])): ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var myModal = new bootstrap.Modal(document.getElementById('notifikasiModal'));
        myModal.show();
    });
    </script>
    <?php unset($_SESSION['update_success']); ?>
    <?php endif; ?>

</body>

</html>