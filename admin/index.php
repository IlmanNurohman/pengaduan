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
$foto = $data['foto'] ? $data['foto'] : 'default.png'; // fallback jika foto kosong

$user_id = $_SESSION['user_id']; // Ambil user_id dari session

// Total semua laporan
$totalQuery = $conn->query("SELECT COUNT(*) as total FROM laporan");
$total = $totalQuery->fetch_assoc()['total'];

// Laporan Menunggu
$menungguQuery = $conn->query("SELECT COUNT(*) as total FROM laporan WHERE status = 'Menunggu'");
$menunggu = $menungguQuery->fetch_assoc()['total'];

// Laporan Diterima
$diterimaQuery = $conn->query("SELECT COUNT(*) as total FROM laporan WHERE status = 'Diterima'");
$diterima = $diterimaQuery->fetch_assoc()['total'];

// Laporan Ditolak
$ditolakQuery = $conn->query("SELECT COUNT(*) as total FROM laporan WHERE status = 'Ditolak'");
$ditolak = $ditolakQuery->fetch_assoc()['total'];

// Ambil data total per kategori untuk Pie Chart
$query = "SELECT kategori, SUM(jumlah) as total FROM apbd_rincian GROUP BY kategori";
$result = $conn->query($query);

$labels = [];
$data = [];

while ($row = $result->fetch_assoc()) {
    $labels[] = $row['kategori'];
    $data[] = $row['total'];
}
$tahunQuery = "SELECT DISTINCT tahun_anggaran FROM apbd_desa ORDER BY tahun_anggaran DESC";
$tahunResult = $conn->query($tahunQuery);

$tahunOptions = "";
$currentYear = null;

while ($row = $tahunResult->fetch_assoc()) {
    if ($currentYear === null) {
        $currentYear = $row['tahun_anggaran']; // ambil tahun pertama
    }

    $selected = ($row['tahun_anggaran'] == $currentYear) ? "selected" : "";
    $tahunOptions .= "<option value='{$row['tahun_anggaran']}' $selected>{$row['tahun_anggaran']}</option>";
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
    <title>Dashboard -Admin</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet" />
    <link rel="stylesheet" href="assets/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="css/dataTables.css">
    <link rel="stylesheet" href="css/dataTables.min.css">
    <link href="../css/bootstrap-icons.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <style>
    .custom-card {
        display: flex;
        background: white;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        height: 100px;
        align-items: center;
    }

    .color-strip {
        width: 8px;
        height: 100%;
    }

    .custom-card-body {
        flex-grow: 1;
        padding: 10px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
    }

    .custom-card-body .title {
        font-size: 13px;
        font-weight: bold;
        text-transform: uppercase;
        margin-bottom: 5px;
        color: #6c757d;
        /* text-secondary */
    }

    .custom-card-body .value {
        font-size: 20px;
        font-weight: bold;
        color: #343a40;
        /* text-dark */
    }

    /* Optional: Color helpers for strip */
    .bg-primary {
        background-color: #007bff;
    }

    .bg-warning {
        background-color: #ffc107;
    }

    .bg-success {
        background-color: #28a745;
    }

    .bg-danger {
        background-color: #dc3545;
    }

    .custom-card-body .icon {
        font-size: 24px;
        margin-bottom: 5px;
    }
    </style>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="index.html">
            <img src="assets/img/garut.png" alt="" style="height: 30px; margin-right: 10px;">
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
                    <img src="../uploads/<?php echo $foto; ?>" class="rounded-circle" width="32" height="32"
                        alt="Foto Profil">
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="../profil.php"><i class="bi bi-person me-1"></i>Profil</a>
                    </li>
                    <li>
                        <hr class="dropdown-divider" />
                    </li>
                    <!-- Tombol Logout -->
                    <li>
                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                            <i class="bi bi-door-open me-1"></i>Logout
                        </a>
                    </li>

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
                        <a class="nav-link" href="index.php">
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
                    </div>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h2 class="mt-4">Dashboard</h2>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active"> <i class="bi-columns-gap me-1"></i>Dashboard</li>
                    </ol>
                    <div class=" row">
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="custom-card border-primary">
                                <div class="color-strip bg-primary"></div>
                                <div class="custom-card-body">
                                    <i class="bi bi-eye icon text-primary"></i>
                                    <div class="title">Semua Laporan</div>
                                    <div class="value"><?= $total ?></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="custom-card border-warning">
                                <div class="color-strip bg-warning"></div>
                                <div class="custom-card-body">
                                    <i class="bi bi-clock icon text-warning"></i>
                                    <div class="title">Menunggu</div>
                                    <div class="value"><?= $menunggu ?></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="custom-card border-success">
                                <div class="color-strip bg-success"></div>
                                <div class="custom-card-body">
                                    <i class="bi bi-bullseye icon text-success"></i>
                                    <div class="title">Diterima</div>
                                    <div class="value"><?= $diterima ?></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="custom-card border-danger">
                                <div class="color-strip bg-danger"></div>
                                <div class="custom-card-body">
                                    <i class="bi bi-x-circle icon text-danger"></i>
                                    <div class="title">Ditolak</div>
                                    <div class="value"><?= $ditolak ?></div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <i class="bi-person-lines-fill me-1"></i>
                                    Jumlah Pengaduan
                                </div>
                                <div class="card-body"><canvas id="myBarChart" width="100%" height="50"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card mb-4">

                                <div class="card-header">
                                    <i class="bi bi-bank me-1"></i>
                                    Data APBD
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="tahunSelect" class="form-label">Pilih Tahun:</label>
                                        <select id="tahunSelect" class="form-select">
                                            <?= $tahunOptions ?>
                                        </select>
                                    </div>
                                    <canvas id="myPieChart" width="100%" height="50"></canvas>
                                </div>




                            </div>
                        </div>
                    </div>
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="bi-people me-1"></i>
                            Daftar Pengadu
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="datatablesSimple" class="table  table-striped text-center">
                                    <thead class="table-dark">
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th class="text-center">Nama</th>
                                            <th class=" text-center">Email</th>
                                            <th class="text-center">Tanggal</th>
                                            <th class="text-center">Status</th>

                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php
        $koneksi = new mysqli("localhost", "u637089379_lapordesa", "u637089379_lapordesa", "Lapordesa123");
        $query = "SELECT * FROM laporan WHERE status = 'Diterima'";

        $result = $koneksi->query($query);
        $no = 1;
        while ($row = $result->fetch_assoc()) {
            $statusClass = match ($row['status']) {
                'Menunggu' => 'bg-warning',
                'Diterima' => 'bg-success',
                'Ditolak' => 'bg-danger',
                default => 'bg-secondary',
            };
        ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= htmlspecialchars($row['nama']) ?></td>
                                            <td><?= htmlspecialchars($row['email']) ?></td>
                                            <td><?= nl2br(htmlspecialchars($row['tanggal_lapor'])) ?></td>
                                            <td><span class=" badge <?= $statusClass ?>">
                                                    <?= htmlspecialchars($row['status']) ?></span>
                                            </td>



                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>


                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal Konfirmasi Logout -->
                <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content text-center p-4">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <i class="bi bi-question-circle-fill text-warning" style="font-size: 4rem;"></i>
                                </div>
                                <h5 class="modal-title mb-2" id="logoutModalLabel">Yakin ingin logout?</h5>
                                <div class="d-flex justify-content-center gap-3 mt-3">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Batal</button>
                                    <a href="../logout.php" class="btn btn-danger">Ya</a>
                                </div>
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

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js">


    </script>
    <!-- jQuery + DataTables -->
    <script src="js/jquery.min.js"></script>
    <script src="js/dataTables.js"></script>
    <script src="js/dataTables.min.js"></script>
    <script src="js/scripts.js"></script>
    <script src="js/Chart.min.js"></script>
    <script src="assets/demo/chart-bar-demo.js"></script>
    <script src="assets/demo/chart-pie-demo.js"></script>


    <script>
    $(document).ready(function() {
        $('#datatablesSimple').DataTable({
            ordering: false // menonaktifkan sorting untuk semua kolom
        });
    });
    </script>



</body>

</html>