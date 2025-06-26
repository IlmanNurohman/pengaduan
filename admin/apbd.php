<?php
session_start(); // Tambahkan ini untuk mulai session
$servername = "127.0.0.1";
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
$data = $conn->query("SELECT * FROM apbd_desa ORDER BY tahun_anggaran DESC");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Form Pengaturan</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet" />
    <link rel="stylesheet" href="assets/fontawesome/css/all.min.css">

    <link rel="stylesheet" href="css/dataTables.css">
    <link rel="stylesheet" href="css/dataTables.min.css">
    <link href="../css/bootstrap-icons.css" rel="stylesheet" />

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
                <div class="container-fluid px-4 mt-4">
                    <div class="card-body">
                        <h2 class="mt-4">Manajeman</h2>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">
                                <i class="bi bi-bank me-2"></i>Data APBD
                            </li>
                        </ol>

                        <!-- Button Tambah -->
                        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#apbdModal">+
                            Tambah
                        </button>

                        <!-- Tabel Data -->
                        <!-- Tabel Data -->
                        <div class="table-responsive">
                            <table id="tabelApbd" class="table table-bordered table-striped text-center">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Tahun Anggaran</th>
                                        <th class="text-center">Jumlah Total (Rp)</th>
                                        <th class="text-center">Rincian</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
            $no = 1;
            $data = $conn->query("SELECT * FROM apbd_desa ORDER BY tahun_anggaran DESC");
            while ($row = $data->fetch_assoc()) :
                $apbd_id = $row['id'];
                $rincian_query = $conn->query("SELECT * FROM apbd_rincian WHERE apbd_id = $apbd_id");
                $rincian_data = [];
                while ($r = $rincian_query->fetch_assoc()) {
                    $rincian_data[] = $r;
                }
            ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $row['tahun_anggaran'] ?></td>
                                        <td>Rp. <?= number_format($row['jumlah_total'], 0, ',', '.') ?></td>
                                        <td>
                                            <button class="btn btn-info btn-sm" type="button" data-bs-toggle="modal"
                                                data-bs-target="#modalRincian<?= $apbd_id ?>">Lihat Rincian</button>
                                            <button class="btn btn-warning btn-sm" type="button" data-bs-toggle="modal"
                                                data-bs-target="#modalEdit<?= $apbd_id ?>">Edit</button>
                                            <button class="btn btn-danger btn-sm" type="button"
                                                onclick="confirmHapusApbd(<?= $apbd_id ?>)">Hapus</button>
                                        </td>
                                    </tr>

                                    <!-- Modal Edit -->
                                    <div class="modal fade" id="modalEdit<?= $apbd_id ?>" tabindex="-1"
                                        aria-labelledby="modalEditLabel<?= $apbd_id ?>" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <form action="update_apbd.php" method="POST">
                                                    <input type="hidden" name="apbd_id" value="<?= $apbd_id ?>">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit APBD Tahun
                                                            <?= $row['tahun_anggaran'] ?></h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Tahun Anggaran</label>
                                                            <input type="number" name="tahun" class="form-control"
                                                                value="<?= $row['tahun_anggaran'] ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Jumlah Total APBD</label>
                                                            <input type="number" name="jumlah_total"
                                                                class="form-control" value="<?= $row['jumlah_total'] ?>"
                                                                required>
                                                        </div>
                                                        <hr>
                                                        <h6>Rincian Penggunaan Dana</h6>
                                                        <div id="edit-rincian-container-<?= $apbd_id ?>">
                                                            <?php foreach ($rincian_data as $r) : ?>
                                                            <div class="row mb-2 rincian-group">
                                                                <div class="col-md-6">
                                                                    <input type="text" name="kategori[]"
                                                                        class="form-control"
                                                                        value="<?= htmlspecialchars($r['kategori']) ?>"
                                                                        required>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <input type="number" name="jumlah[]"
                                                                        class="form-control" value="<?= $r['jumlah'] ?>"
                                                                        required>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <button type="button"
                                                                        class="btn btn-danger remove-rincian">Hapus</button>
                                                                </div>
                                                            </div>
                                                            <?php endforeach; ?>
                                                        </div>
                                                        <button type="button" class="btn btn-sm btn-secondary"
                                                            onclick="tambahEditRincian('<?= $apbd_id ?>')">+ Tambah
                                                            Rincian</button>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-success">Simpan
                                                            Perubahan</button>
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Tutup</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal Rincian -->
                                    <div class="modal fade" id="modalRincian<?= $apbd_id ?>" tabindex="-1"
                                        aria-labelledby="modalRincianLabel<?= $apbd_id ?>" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalRincianLabel<?= $apbd_id ?>">
                                                        Rincian APBD Tahun <?= $row['tahun_anggaran'] ?>
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Tutup"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p><strong>Tahun Anggaran:</strong> <?= $row['tahun_anggaran'] ?>
                                                    </p>
                                                    <p><strong>Jumlah Total:</strong> Rp.
                                                        <?= number_format($row['jumlah_total'], 0, ',', '.') ?>
                                                    </p>
                                                    <hr>
                                                    <p><strong>Rincian Penggunaan Dana:</strong></p>
                                                    <ul>
                                                        <?php foreach ($rincian_data as $r) : ?>
                                                        <li><?= htmlspecialchars($r['kategori']) ?>: Rp.
                                                            <?= number_format($r['jumlah'], 0, ',', '.') ?></li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Tutup</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>



                        <!-- Modal Tambah APBD -->
                        <div class="modal fade" id="apbdModal" tabindex="-1" aria-labelledby="apbdModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <form action="simpan_apbd.php" method="POST">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Tambah Data APBD Desa</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Tutup"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="tahun" class="form-label">Tahun Anggaran</label>
                                                <input type="number" name="tahun" class="form-control" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="jumlah_total" class="form-label">Jumlah Total APBD
                                                    Desa</label>
                                                <input type="number" name="jumlah_total" class="form-control" required>
                                            </div>
                                            <hr>
                                            <h6>Rincian Penggunaan Dana</h6>
                                            <div id="rincian-container">
                                                <div class="row mb-2 rincian-group">
                                                    <div class="col-md-6">
                                                        <input type="text" name="kategori[]" class="form-control"
                                                            placeholder="Kategori (mis. Bansos)" required>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <input type="number" name="jumlah[]" class="form-control"
                                                            placeholder="Jumlah (Rp)" required>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <button type="button"
                                                            class="btn btn-danger remove-rincian">Hapus</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-secondary mt-2" id="tambah-rincian">+
                                                Tambah
                                                Rincian</button>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Batal</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Notifikasi -->
                    <div class="modal fade" id="notifModal" tabindex="-1" aria-labelledby="notifModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content text-center p-4">
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <i id="notifIcon" class="bi bi-check-circle-fill text-success"
                                            style="font-size: 3rem;"></i>
                                    </div>
                                    <h5 class="modal-title mb-2" id="notifModalLabel">Berhasil</h5>
                                    <p class="text-muted" id="notifMessage">Data APBD berhasil disimpan!</p>
                                    <button class="btn btn-success mt-3" data-bs-dismiss="modal">OK</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal Konfirmasi Hapus -->
                    <div class="modal fade" id="modalHapusApbd" tabindex="-1" aria-labelledby="modalHapusLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content text-center p-4">
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <i class="bi bi-exclamation-circle-fill text-danger"
                                            style="font-size: 3rem;"></i>
                                    </div>
                                    <h5 class="modal-title mb-2" id="modalHapusLabel">Konfirmasi Hapus</h5>
                                    <p class="text-muted">Yakin ingin menghapus data APBD ini?</p>
                                    <form id="formHapusApbd">
                                        <input type="hidden" name="apbd_id" id="hapusApbdId">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-danger">Hapus</button>
                                    </form>

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
    <script src="../js/index-min.js"></script>
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
    function getQueryParam(param) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(param);
    }

    function showNotifModal(title, message, isSuccess = true, callback = null) {
        document.getElementById('notifModalLabel').textContent = title;
        document.getElementById('notifMessage').textContent = message;

        const icon = document.getElementById('notifIcon');
        icon.className = isSuccess ? 'bi bi-check-circle-fill text-success' : 'bi bi-x-circle-fill text-danger';

        const notifModal = new bootstrap.Modal(document.getElementById('notifModal'));
        notifModal.show();

        const modalElement = document.getElementById('notifModal');

        // 🔁 Pastikan hanya dijalankan sekali tiap modal show
        modalElement.addEventListener('hidden.bs.modal', function() {
            if (callback) callback();
        }, {
            once: true
        });
    }


    document.addEventListener('DOMContentLoaded', () => {
        if (getQueryParam('sukses') === '1') {
            showNotifModal('Berhasil', 'Data APBD berhasil disimpan!', true, () => {
                const url = new URL(window.location);
                url.searchParams.delete('sukses');
                window.history.replaceState({}, document.title, url);
            });
        }

        if (getQueryParam('edit') === '1') {
            showNotifModal('Berhasil', 'Data APBD berhasil diperbarui!', true, () => {
                const url = new URL(window.location);
                url.searchParams.delete('edit');
                window.history.replaceState({}, document.title, url);
            });
        }
    });
    </script>

    <script>
    // DataTables init
    $(document).ready(function() {
        // Inisialisasi DataTable dengan ID yang benar dan hanya sekali
        var table = $('#tabelApbd').DataTable({
            paging: true,
            ordering: false, // Tidak perlu urut otomatis
            info: true,
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ entri",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                paginate: {
                    first: "Awal",
                    last: "Akhir",
                    next: "→",
                    previous: "←"
                },
                zeroRecords: "Tidak ada data yang ditemukan"
            }
        });
    });


    // Tambah rincian input
    document.getElementById('tambah-rincian').addEventListener('click', function() {
        const container = document.getElementById('rincian-container');
        const div = document.createElement('div');
        div.classList.add('row', 'mb-2', 'rincian-group');
        div.innerHTML = `
      <div class="col-md-6">
        <input type="text" name="kategori[]" class="form-control" placeholder="Kategori" required>
      </div>
      <div class="col-md-4">
        <input type="number" name="jumlah[]" class="form-control" placeholder="Jumlah (Rp)" required>
      </div>
      <div class="col-md-2">
        <button type="button" class="btn btn-danger remove-rincian">Hapus</button>
      </div>`;
        container.appendChild(div);
    });

    // Hapus rincian input
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-rincian')) {
            e.target.closest('.rincian-group').remove();
        }
    });
    </script>
    <script>
    function tambahEditRincian(id) {
        const container = document.getElementById('edit-rincian-container-' + id);
        const div = document.createElement('div');
        div.classList.add('row', 'mb-2', 'rincian-group');
        div.innerHTML = `
        <div class="col-md-6">
            <input type="text" name="kategori[]" class="form-control" placeholder="Kategori" required>
        </div>
        <div class="col-md-4">
            <input type="number" name="jumlah[]" class="form-control" placeholder="Jumlah (Rp)" required>
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-danger remove-rincian">Hapus</button>
        </div>`;
        container.appendChild(div);
    }

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-rincian')) {
            e.target.closest('.rincian-group').remove();
        }
    });
    </script>
    <script>
    document.getElementById('formHapusApbd').addEventListener('submit', function(e) {
        e.preventDefault();

        const apbdId = document.getElementById('hapusApbdId').value;

        fetch('hapus_apbd.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'apbd_id=' + encodeURIComponent(apbdId)
            })
            .then(response => response.text())
            .then(response => {
                // Tutup modal konfirmasi
                const modalHapus = bootstrap.Modal.getInstance(document.getElementById('modalHapusApbd'));
                modalHapus.hide();

                // Tampilkan modal sukses
                showNotifModal('Berhasil', 'Data APBD berhasil dihapus!', true, () => {
                    // Reload halaman setelah klik OK
                    location.reload();
                });
            })
            .catch(error => {
                console.error('Gagal menghapus:', error);
                showNotifModal('Gagal', 'Terjadi kesalahan saat menghapus data.', false);
            });
    });
    </script>
    <script>
    function confirmHapusApbd(apbdId) {
        // Isi input hidden di modal dengan ID yang akan dihapus
        document.getElementById('hapusApbdId').value = apbdId;

        // Tampilkan modal konfirmasi hapus
        const modal = new bootstrap.Modal(document.getElementById('modalHapusApbd'));
        modal.show();
    }
    </script>


    <script>
    document.addEventListener("DOMContentLoaded", function() {
        let dbApbd;
        const dbNameApbd = "APBDDB";

        const request = indexedDB.open(dbNameApbd, 1);

        request.onerror = function() {
            console.error("Gagal membuka IndexedDB untuk APBD");
        };

        request.onsuccess = function(event) {
            dbApbd = event.target.result;

            if (navigator.onLine) {
                syncOfflineApbd();
            }
        };

        request.onupgradeneeded = function(event) {
            dbApbd = event.target.result;
            if (!dbApbd.objectStoreNames.contains("apbd")) {
                dbApbd.createObjectStore("apbd", {
                    keyPath: "id",
                    autoIncrement: true
                });
            }
        };

        // Handle submit APBD
        document.querySelector('form[action="simpan_apbd.php"]').addEventListener("submit", function(e) {
            e.preventDefault();
            const form = e.target;

            const tahun = form.tahun.value;
            const jumlah_total = form.jumlah_total.value;

            // Ambil semua rincian
            const kategoriInputs = form.querySelectorAll('input[name="kategori[]"]');
            const jumlahInputs = form.querySelectorAll('input[name="jumlah[]"]');

            let rincian = [];
            for (let i = 0; i < kategoriInputs.length; i++) {
                rincian.push({
                    kategori: kategoriInputs[i].value,
                    jumlah: jumlahInputs[i].value
                });
            }

            const apbdData = {
                tahun,
                jumlah_total,
                rincian,
                timestamp: Date.now()
            };

            if (navigator.onLine) {
                sendApbdToServer(apbdData);
            } else {
                saveApbdToIndexedDB(apbdData);
                showNotifModal(
                    'Offline',
                    'Data APBD disimpan sementara. Akan dikirim saat online.',
                    true
                );



                // ✅ Reset form
                form.reset();

                // ✅ Hapus semua rincian tambahan (selain yang pertama)
                const rincianContainer = document.getElementById('rincian-container');
                const rincianGroups = rincianContainer.querySelectorAll('.rincian-group');
                rincianGroups.forEach((group, index) => {
                    if (index > 0) group.remove(); // sisakan 1 saja
                });

                // ✅ Tutup modal (bootstrap 5)
                var modalEl = document.querySelector('#apbdModal'); // pastikan ID modal sesuai
                var modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) modal.hide();
            }
        });
        document.querySelectorAll('form[action="update_apbd.php"]').forEach(function(form) {
            form.addEventListener("submit", function(e) {
                e.preventDefault();

                const apbd_id = form.apbd_id.value;
                const tahun = form.tahun.value;
                const jumlah_total = form.jumlah_total.value;

                const kategoriInputs = form.querySelectorAll('input[name="kategori[]"]');
                const jumlahInputs = form.querySelectorAll('input[name="jumlah[]"]');

                let rincian = [];
                for (let i = 0; i < kategoriInputs.length; i++) {
                    rincian.push({
                        kategori: kategoriInputs[i].value,
                        jumlah: jumlahInputs[i].value
                    });
                }

                const apbdEditData = {
                    id_apbd: parseInt(apbd_id),
                    tahun: parseInt(tahun),
                    jumlah_total: parseInt(jumlah_total),
                    rincian: rincian,
                    offline_edit: true, // penanda bahwa ini hasil edit offline
                    timestamp: Date.now()
                };

                if (navigator.onLine) {
                    sendApbdToServer(apbdEditData, "update_apbd.php");

                } else {
                    saveApbdEditToIndexedDB(apbdEditData);
                    showNotifModal('Offline',
                        'Perubahan APBD disimpan sementara. Akan dikirim saat online.', true
                    );
                }

                // Tutup modal Bootstrap (jika perlu)
                const modalElement = form.closest('.modal');
                const modalInstance = bootstrap.Modal.getInstance(modalElement);
                if (modalInstance) modalInstance.hide();
            });
        });

        function saveApbdEditToIndexedDB(data) {
            const tx = dbApbd.transaction("apbd", "readwrite");
            const store = tx.objectStore("apbd");
            store.put(data); // pakai `put` untuk update jika id sama
        }


        function saveApbdToIndexedDB(data) {
            const tx = dbApbd.transaction("apbd", "readwrite");
            const store = tx.objectStore("apbd");
            store.add(data);
        }

        function syncOfflineApbd() {
            const tx = dbApbd.transaction("apbd", "readonly");
            const store = tx.objectStore("apbd");
            const getAll = store.getAll();

            getAll.onsuccess = function() {
                const allData = getAll.result;

                if (!allData.length) return;

                allData.forEach((data) => {
                    const isEdit = data.offline_edit === true;
                    const url = isEdit ? "update_apbd.php" : "simpan_apbd.php";

                    sendApbdToServer(data, url, () => {
                        const delTx = dbApbd.transaction("apbd", "readwrite");
                        const delStore = delTx.objectStore("apbd");
                        delStore.delete(data.id);

                        // Jika semua data sudah dikirim, reload halaman
                        delTx.oncomplete = () => {
                            location
                                .reload(); // Refresh tampilan setelah semua data offline dikirim
                        };
                    });
                });
            };
        }


        function sendApbdToServer(data, url = "simpan_apbd.php", callback = () => {}) {
            fetch(url, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(data)
                })
                .then(res => res.text())
                .then(response => {
                    console.log("Server response:", response);

                    // ❗ Tambahkan reload setelah notifikasi OK ditekan
                    showNotifModal("Berhasil", "Data APBD berhasil dikirim!", true, () => {
                        location.reload(); // Reload setelah OK
                    });

                    callback(); // tetap panggil callback jika ada
                })
                .catch(err => {
                    console.error("Gagal kirim APBD ke server:", err);
                    showNotifModal("Gagal", "Gagal mengirim data ke server.", false);
                });
        }




        window.addEventListener("online", syncOfflineApbd);
    });
    </script>

</body>

</html>