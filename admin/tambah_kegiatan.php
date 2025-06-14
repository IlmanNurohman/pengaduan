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
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Staf</title>
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
        <a class="navbar-brand ps-3" href="index.php">
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
                <div class="container-fluid px-4">
                    <div class="card-body">
                        <h2 class="mt-4">Manajeman</h2>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active"><i class="bi bi-calendar me-2"></i>Data Kegiatan</li>
                        </ol>
                        <button id="tambahBtn" class="btn btn-primary mb-3" data-bs-toggle="modal"
                            data-bs-target="#formModal">
                            <i class="bi bi-plus-lg"></i> Tambah
                        </button>
                        <div class="table-responsive">
                            <table id="datastafTable" class="table table-bordered table-striped text-center">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Nama kegiatan</th>
                                        <th class="text-center">Tanggal Kegiatan</th>
                                        <th class="text-center">Foto</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "pengaduan";
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$no = 1;
$query = mysqli_query($conn, "SELECT * FROM kegiatan ORDER BY id DESC");
while ($data = mysqli_fetch_assoc($query)) {
?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= htmlspecialchars($data['nama_kegiatan']) ?></td>
                                        <td><?= htmlspecialchars($data['tanggal_kegiatan']) ?></td>
                                        <td>
                                            <?php if (!empty($data['foto'])): ?>
                                            <img src="../<?= htmlspecialchars($data['foto']) ?>" alt="Foto" width="100"
                                                class="img-fluid" />
                                            <?php else: ?>
                                            Tidak ada foto
                                            <?php endif; ?>
                                        </td>


                                        <td>
                                            <!-- Tombol Edit -->
                                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#editModal<?= $data['id'] ?>">
                                                Edit
                                            </button>

                                            <!-- Tombol Hapus -->
                                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#hapusModal" data-id="<?= $data['id'] ?>">
                                                Hapus
                                            </button>


                                        </td>
                                    </tr>
                                    <!-- Modal Edit -->
                                    <div class="modal fade" id="editModal<?= $data['id'] ?>" tabindex="-1"
                                        aria-labelledby="editModalLabel<?= $data['id'] ?>" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form method="POST" action="edit_kegiatan.php"
                                                    enctype="multipart/form-data">
                                                    <div class="modal-header bg-warning">
                                                        <h5 class="modal-title" id="editModalLabel<?= $data['id'] ?>">
                                                            Edit Kegiatan
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <input type="hidden" name="id" value="<?= $data['id'] ?>">
                                                        <div class="mb-3">
                                                            <label for="nama_kegiatan" class="form-label">Nama
                                                                Kegiatan</label>
                                                            <input type="text" class="form-control" name="nama_kegiatan"
                                                                value="<?= htmlspecialchars($data['nama_kegiatan']) ?>"
                                                                required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="tanggal_kegiatan" class="form-label">Tanggal
                                                                Kegiatan</label>
                                                            <input type="date" class="form-control"
                                                                name="tanggal_kegiatan"
                                                                value="<?= $data['tanggal_kegiatan'] ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="foto" class="form-label">Ganti Foto
                                                                (opsional)</label>
                                                            <input type="file" class="form-control" name="foto">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" name="update"
                                                            class="btn btn-warning">Simpan
                                                            Perubahan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                </tbody>

                            </table>
                        </div>

                        <!-- Modal Form Tambah Staf -->
                        <div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="formModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form id="formKegiatan" action="simpan_kegiatan.php" method="POST"
                                        enctype="multipart/form-data">

                                        <div class="modal-header">
                                            <h5 class="modal-title" id="formModalLabel">Form Kegiatan</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Tutup"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="nama" class="form-label">Nama Kegiatan</label>
                                                <input type="text" class="form-control" id="nama" name="nama_kegiatan"
                                                    required>
                                            </div>

                                            <div class="mb-3">
                                                <label for="tanggal" class="form-label">Tanggal Kegiatan</label>
                                                <input type="date" class="form-control" id="tanggal"
                                                    name="tanggal_kegiatan" required>
                                            </div>

                                            <div class="mb-3">
                                                <label for="foto" class="form-label">Foto Kegiatan</label>
                                                <input type="file" class="form-control" id="foto" name="foto"
                                                    accept="image/*" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Tutup</button>
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal fade" id="tambahSuccessModal" tabindex="-1" aria-labelledby="tambahSuccessModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content text-center p-4">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                                </div>
                                <h5 class="modal-title mb-2" id="tambahSuccessModalLabel">Kegiatan Berhasil Ditambahkan
                                </h5>
                                <button type="button" class="btn btn-success mt-3"
                                    data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="editSuccessModal" tabindex="-1" aria-labelledby="editSuccessModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content text-center p-4">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <i class="bi bi-check-circle-fill text-warning" style="font-size: 4rem;"></i>
                                </div>
                                <h5 class="modal-title mb-2" id="editSuccessModalLabel">Kegiatan Berhasil Diedit</h5>
                                <button type="button" class="btn btn-warning mt-3"
                                    data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="hapusSuccessModal" tabindex="-1" aria-labelledby="hapusSuccessModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content text-center p-4">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <i class="bi bi-trash-fill text-danger" style="font-size: 4rem;"></i>
                                </div>
                                <h5 class="modal-title mb-2" id="hapusSuccessModalLabel">Kegiatan Berhasil Dihapus</h5>
                                <button type="button" class="btn btn-danger mt-3" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal Konfirmasi Hapus -->
                <div class="modal fade" id="hapusModal" tabindex="-1" aria-labelledby="hapusModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content text-center p-4">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 3rem;"></i>
                                </div>
                                <h5 class="modal-title mb-3" id="hapusModalLabel">Yakin ingin menghapus data ini?</h5>
                                <form id="hapusForm" method="GET" action="hapus_kegiatan.php">
                                    <input type="hidden" name="id" id="hapusId">
                                    <button type="button" class="btn btn-secondary me-2"
                                        data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                </form>
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
    <script src="js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script src="js/scripts.js"></script>
    <!-- jQuery + DataTables -->
    <script src="js/jquery.min.js"></script>
    <script src="/js/dataTables.min.js"></script>
    <script src="js/dataTables.min.js"></script>
    <?php
    $status = $_GET['status'] ?? '';
?>
    <script>
    window.addEventListener('DOMContentLoaded', () => {
        const status = "<?= $status ?>";
        if (status === "tambah_sukses") {
            new bootstrap.Modal(document.getElementById('tambahSuccessModal')).show();
        } else if (status === "edit_sukses") {
            new bootstrap.Modal(document.getElementById('editSuccessModal')).show();
        } else if (status === "hapus_sukses") {
            new bootstrap.Modal(document.getElementById('hapusSuccessModal')).show();
        }
    });
    </script>
    <script>
    const hapusModal = document.getElementById('hapusModal');
    hapusModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        const inputHapusId = hapusModal.querySelector('#hapusId');
        inputHapusId.value = id;
    });
    </script>


    <script>
    $(document).ready(function() {
        // Inisialisasi DataTable
        var table = $('#datastafTable').DataTable({
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
    </script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        let dbKegiatan;
        const dbNameKegiatan = "KegiatanDB";

        const request = indexedDB.open(dbNameKegiatan);

        request.onerror = function() {
            console.error("Gagal membuka IndexedDB untuk kegiatan");
        };

        request.onsuccess = function(event) {
            dbKegiatan = event.target.result;

            if (navigator.onLine) {
                syncOfflineKegiatan();
            }
        };

        request.onupgradeneeded = function(event) {
            dbKegiatan = event.target.result;
            if (!dbKegiatan.objectStoreNames.contains("kegiatan")) {
                dbKegiatan.createObjectStore("kegiatan", {
                    keyPath: "id",
                    autoIncrement: true
                });
            }
        };

        document.getElementById("formKegiatan").addEventListener("submit", function(e) {
            e.preventDefault();
            const form = e.target;

            const nama_kegiatan = form.nama_kegiatan.value;
            const tanggal_kegiatan = form.tanggal_kegiatan.value;
            const fotoFile = form.foto.files[0];

            const reader = new FileReader();
            reader.onload = function() {
                const kegiatanData = {
                    nama_kegiatan,
                    tanggal_kegiatan,
                    foto: reader.result,
                    timestamp: Date.now()
                };

                if (navigator.onLine) {
                    sendKegiatanToServer(kegiatanData, () => {
                        form.reset(); // Reset form setelah sukses
                        const successModal = new bootstrap.Modal(document.getElementById(
                            'tambahSuccessModal'));
                        successModal.show();

                        // OPTIONAL REDIRECT:
                        window.location.href = "tambah_kegiatan.php?status=tambah_sukses";
                    });
                } else {
                    saveKegiatanToIndexedDB(kegiatanData);
                    alert("Offline: Data kegiatan disimpan sementara. Akan dikirim saat online.");
                    form.reset(); // Reset meski offline
                }
            };

            if (fotoFile) {
                reader.readAsDataURL(fotoFile);
            } else {
                reader.onload(); // Handle kasus tanpa foto
            }
        });

        function saveKegiatanToIndexedDB(data) {
            const tx = dbKegiatan.transaction("kegiatan", "readwrite");
            const store = tx.objectStore("kegiatan");
            store.add(data);
        }

        function syncOfflineKegiatan() {
            const tx = dbKegiatan.transaction("kegiatan", "readonly");
            const store = tx.objectStore("kegiatan");
            const getAll = store.getAll();

            getAll.onsuccess = function() {
                const allData = getAll.result;
                if (!allData.length) return;

                allData.forEach((data) => {
                    sendKegiatanToServer(data, () => {
                        const delTx = dbKegiatan.transaction("kegiatan", "readwrite");
                        const delStore = delTx.objectStore("kegiatan");
                        delStore.delete(data.id);
                    });
                });
            };
        }

        function sendKegiatanToServer(data, callback = () => {}) {
            const formData = new FormData();
            formData.append("nama_kegiatan", data.nama_kegiatan);
            formData.append("tanggal_kegiatan", data.tanggal_kegiatan);

            if (data.foto) {
                const blob = dataURLtoBlob(data.foto);
                formData.append("foto", blob, "foto_kegiatan.png");
            }

            fetch("simpan_kegiatan.php", {
                    method: "POST",
                    body: formData,
                })
                .then(res => res.text())
                .then(response => {
                    console.log("Response dari server:", response);
                    callback(); // panggil callback sukses
                })
                .catch(err => {
                    console.error("Gagal kirim ke server:", err);
                });
        }

        function dataURLtoBlob(dataURL) {
            const arr = dataURL.split(","),
                mime = arr[0].match(/:(.*?);/)[1],
                bstr = atob(arr[1]);
            let n = bstr.length,
                u8arr = new Uint8Array(n);
            while (n--) {
                u8arr[n] = bstr.charCodeAt(n);
            }
            return new Blob([u8arr], {
                type: mime
            });
        }

        window.addEventListener("online", syncOfflineKegiatan);
    });
    </script>



</body>

</html>