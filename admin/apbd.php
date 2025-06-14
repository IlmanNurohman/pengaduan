<?php
session_start(); // Tambahkan ini untuk mulai session
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


// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    die("Akses ditolak. Silakan login terlebih dahulu.");
}
$user_id = $_SESSION['user_id'];
$query = mysqli_query($koneksi, "SELECT foto FROM users WHERE id = '$user_id'");
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
                    <li><a class="dropdown-item" href="../index.php"><i class="bi bi-door-open me-1"></i>Logout</a></li>
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
            $data = $koneksi->query("SELECT * FROM apbd_desa ORDER BY tahun_anggaran DESC");
            while ($row = $data->fetch_assoc()) :
              $apbd_id = $row['id'];
              $rincian = $conn->query("SELECT * FROM apbd_rincian WHERE apbd_id = $apbd_id");
            ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td class="text-center"><?= $row['tahun_anggaran'] ?></td>
                                        <td class="text-center"><?= number_format($row['jumlah_total'], 0, ',', '.') ?>
                                        </td>
                                        <td>
                                            <!-- Tombol buka modal -->
                                            <button class="btn btn-info btn-sm" type="button" data-bs-toggle="modal"
                                                data-bs-target="#modalRincian<?= $apbd_id ?>">
                                                Lihat Rincian
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Modal Rincian -->
                                    <div class="modal fade" id="modalRincian<?= $apbd_id ?>" tabindex="-1"
                                        aria-labelledby="modalRincianLabel<?= $apbd_id ?>" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalRincianLabel<?= $apbd_id ?>">
                                                        Rincian APBD Tahun <?= $row['tahun_anggaran'] ?></h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Tutup"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p><strong>Tahun Anggaran:</strong>
                                                        <?= $row['tahun_anggaran'] ?>
                                                    </p>
                                                    <p><strong>Jumlah Total:</strong> Rp
                                                        <?= number_format($row['jumlah_total'], 0, ',', '.') ?></p>
                                                    <hr>
                                                    <p><strong>Rincian Penggunaan Dana:</strong></p>
                                                    <ul>
                                                        <?php while ($r = $rincian->fetch_assoc()) : ?>
                                                        <li><?= htmlspecialchars($r['kategori']) ?>: Rp
                                                            <?= number_format($r['jumlah'], 0, ',', '.') ?></li>
                                                        <?php endwhile; ?>
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
        modalElement.addEventListener('hidden.bs.modal', function() {
            if (callback) callback();
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
                alert("Offline: Data APBD disimpan sementara. Akan dikirim saat online.");

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
                    sendApbdToServer(data, () => {
                        const delTx = dbApbd.transaction("apbd", "readwrite");
                        const delStore = delTx.objectStore("apbd");
                        delStore.delete(data.id);
                    });
                });
            };
        }

        function sendApbdToServer(data, callback = () => {}) {
            fetch("simpan_apbd.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(data)
                })
                .then(res => res.text())
                .then(response => {
                    console.log("Server response:", response);

                    // Inisialisasi dan tampilkan modal
                    var myModal = new bootstrap.Modal(document.getElementById('notifModal'));

                    // Set isi modal
                    document.getElementById('notifModalLabel').textContent = "Berhasil";
                    document.getElementById('notifMessage').textContent = "Data APBD berhasil disimpan!";
                    document.getElementById('notifIcon').className = "bi bi-check-circle-fill text-success";

                    // Tampilkan modal
                    myModal.show();

                    // Tambahkan event listener untuk tombol OK di modal
                    document.querySelector('#notifModal .btn-success').addEventListener('click',
                        function() {
                            // Callback function (misalnya redirect atau refresh table)
                            if (typeof callback === 'function') {
                                callback();
                            }
                            // Redirect ke halaman apbd.php
                            window.location.href = 'apbd.php';
                        }, {
                            once: true
                        }); // once:true agar listener hanya terpanggil sekali
                })

                .catch(err => {
                    console.error("Gagal kirim APBD ke server:", err);

                    // Panggil modal gagal
                    var myModal = new bootstrap.Modal(document.getElementById('notifModal'));
                    document.getElementById('notifModalLabel').textContent = "Gagal";
                    document.getElementById('notifMessage').textContent =
                        "Gagal mengirim data APBD ke server.";
                    document.getElementById('notifIcon').className = "bi bi-x-circle-fill text-danger";
                    myModal.show();
                });
        }


        window.addEventListener("online", syncOfflineApbd);
    });
    </script>

</body>

</html>