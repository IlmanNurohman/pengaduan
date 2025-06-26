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
    <link href="css/datatables.css" rel="stylesheet" />
    <link href="css/datatables.min.css" rel="stylesheet" />
    <link href="assets/fontawesome/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/bootstrap-icons.css">

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
                    <div class="card-body">
                        <h2 class="mt-4">Manajeman</h2>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active"><i class="bi bi-person-lines-fill me-2"></i>Data
                                Perangkat
                            </li>
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
                                        <th class="text-center">Nama</th>
                                        <th class="text-center">Email</th>
                                        <th class="text-center">Alamat</th>
                                        <th class="text-center">Jabatan</th>
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
        $query = mysqli_query($conn, "SELECT * FROM staf_desa");
        while ($data = mysqli_fetch_assoc($query)) {
        ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= htmlspecialchars($data['nama']) ?></td>
                                        <td><?= htmlspecialchars($data['email']) ?></td>
                                        <td><?= htmlspecialchars($data['alamat']) ?></td>
                                        <td><?= htmlspecialchars($data['jabatan']) ?></td>
                                        <td>
                                            <?php if (!empty($data['foto'])): ?>
                                            <img src="../uploads/<?= htmlspecialchars($data['foto']) ?>?v=<?= time() ?>"
                                                alt="Foto" width="100" class="img-fluid">

                                            <?php else: ?>
                                            Tidak ada foto
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="#" class="btn btn-warning btn-sm btn-edit"
                                                data-id="<?= $data['id'] ?>">Edit</a>
                                            <a href="#" class="btn btn-danger btn-sm"
                                                onclick="showConfirmModal(<?= $data['id'] ?>); return false;">
                                                Hapus
                                            </a>

                                        </td>

                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- Modal Edit Staf -->
                        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <form id="formEditStaf" enctype="multipart/form-data" class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModalLabel">Edit Data Staf</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" id="edit_id" name="id">

                                        <div class="mb-3">
                                            <label for="edit_nama" class="form-label">Nama</label>
                                            <input type="text" id="edit_nama" name="nama" class="form-control" required>
                                        </div>


                                        <div class="mb-3">
                                            <label for="edit_email" class="form-label">Email</label>
                                            <input type="email" id="edit_email" name="email" class="form-control"
                                                required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="jabatan" class="form-label">Jabatan</label>
                                            <select class="form-select" id="jabatan" name="jabatan" required>
                                                <option value="">-- Pilih Jabatan --</option>
                                                <option value="Kepada Desa">Kepala Desa</option>
                                                <option value="Sekertaris">Sekertaris</option>
                                                <option value="Bendahara">Bendahara</option>
                                                <option value="Kaur Perencanaan">Kaur Perencanaan</option>
                                                <option value="Kaur Umum">Kaur Umum</option>
                                                <option value="Kasi Pemerintahan">Kasi Pemerintahan</option>
                                                <option value="Kasi Kesejahteraan">Kasi Kesejahteraan</option>
                                                <option value="Kasi Pelayanan">Kasi Pelayanan</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="edit_alamat" class="form-label">Alamat</label>
                                            <textarea id="edit_alamat" name="alamat" class="form-control" rows="3"
                                                required></textarea>
                                        </div>

                                        <div class="mb-3">
                                            <label for="edit_foto" class="form-label">Foto</label>
                                            <input type="file" id="edit_foto" name="foto" class="form-control"
                                                accept="image/*">
                                            <img id="previewFoto" src="" alt="Preview Foto"
                                                style="margin-top:10px; max-width: 100%; display:none;">
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>


                        <!-- Modal Form Tambah Staf -->
                        <div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="formModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form id="formStaf" enctype="multipart/form-data">

                                        <div class="modal-header">
                                            <h5 class="modal-title" id="formModalLabel">Form Tambah Staf</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Tutup"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="nama" class="form-label">Nama Lengkap</label>
                                                <input type="text" class="form-control" id="nama" name="nama" required>
                                            </div>

                                            <div class="mb-3">
                                                <label for="email" class="form-label">Email</label>
                                                <input type="email" class="form-control" id="email" name="email"
                                                    required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="jabatan" class="form-label">Jabatan</label>
                                                <select class="form-select" id="jabatan" name="jabatan" required>
                                                    <option value="">-- Pilih Jabatan --</option>
                                                    <option value="Kepada Desa">Kepala Desa</option>
                                                    <option value="Sekertaris">Sekertaris</option>
                                                    <option value="Bendahara">Bendahara</option>
                                                    <option value="Kaur Perencanaan">Kaur Perencanaan</option>
                                                    <option value="Kaur Umum">Kaur Umum</option>
                                                    <option value="Kasi Pemerintahan">Kasi Pemerintahan</option>
                                                    <option value="Kasi Kesejahteraan">Kasi Kesejahteraan</option>
                                                    <option value="Kasi Pelayanan">Kasi Pelayanan</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="alamat" class="form-label">Alamat</label>
                                                <textarea class="form-control" id="alamat" name="alamat" rows="3"
                                                    required></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label for="foto" class="form-label">Foto Staf</label>
                                                <input type="file" class="form-control" id="foto" name="foto"
                                                    accept="image/*">
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
                <!-- Modal Notifikasi Umum -->
                <div class="modal fade" id="notifModal" tabindex="-1" aria-labelledby="notifModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content text-center p-4">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <i id="notifIcon" class="bi bi-check-circle-fill text-success"
                                        style="font-size: 4rem;"></i>
                                </div>
                                <h5 class="modal-title mb-2" id="notifModalLabel">Berhasil</h5>
                                <p class="text-muted" id="notifMessage">Pesan aksi</p>
                                <button class="btn btn-success mt-3" data-bs-dismiss="modal">OK</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal Konfirmasi Hapus -->
                <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content text-center p-4">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <i class="bi bi-exclamation-circle-fill text-danger" style="font-size: 4rem;"></i>
                                </div>
                                <h5 class="modal-title mb-2" id="confirmDeleteLabel">Konfirmasi Hapus</h5>
                                <p class="text-muted">Yakin ingin menghapus data ini?</p>
                                <button class="btn btn-secondary me-2" data-bs-dismiss="modal">Batal</button>
                                <a href="#" id="confirmDeleteBtn" class="btn btn-danger">Hapus</a>
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
    <script src="js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script src="js/scripts.js"></script>
    <!-- jQuery + DataTables -->
    <script src="js/jquery.min.js"></script>
    <script src="js/datatables.js"></script>
    <script src="js/datatables.min.js"></script>
    <!-- ✅ Tambahkan ini DI ATAS $(document).ready() -->
    <script>
    function showNotifModal(title, message, isSuccess = true, callback = null) {
        $('#notifModalLabel').text(title);
        $('#notifMessage').text(message);
        $('#notifIcon')
            .removeClass('text-success text-danger bi-check-circle-fill bi-x-circle-fill')
            .addClass(isSuccess ? 'text-success bi-check-circle-fill' : 'text-danger bi-x-circle-fill');

        const modal = new bootstrap.Modal(document.getElementById('notifModal'));
        modal.show();

        $('#notifModal').off('hidden.bs.modal').on('hidden.bs.modal', function() {
            if (callback) callback();
        });
    }
    </script>


    <script>
    function showConfirmModal(id) {
        const deleteBtn = document.getElementById('confirmDeleteBtn');
        deleteBtn.href = `hapus_staf.php?id=${id}`;
        const modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
        modal.show();
    }
    </script>

    <script>
    function getQueryParam(param) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(param);
    }

    // Tampilkan modal jika ada parameter hapus=berhasil
    document.addEventListener("DOMContentLoaded", function() {
        const hapusStatus = getQueryParam('hapus');
        if (hapusStatus === 'berhasil') {
            showNotifModal('Berhasil', 'Data staf berhasil dihapus!', true, function() {
                // Hapus parameter dari URL setelah modal ditutup
                const url = new URL(window.location);
                url.searchParams.delete('hapus');
                window.history.replaceState({}, document.title, url);
            });
        }
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





        // Event klik tombol edit
        $('#datastafTable').on('click', '.btn-edit', function(e) {
            e.preventDefault();
            var id = $(this).data('id');

            $.ajax({
                url: 'get_staf.php',
                method: 'GET',
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(data) {
                    $('#edit_id').val(data.id);
                    $('#edit_nama').val(data.nama);
                    $('#edit_email').val(data.email);
                    $('#edit_jabatan').val(data.jabatan);
                    $('#edit_alamat').val(data.alamat);

                    $('#editModal').modal('show');
                },
                error: function() {
                    alert('Gagal mengambil data staf.');
                }
            });
        });




    });
    </script>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        let db;
        const dbName = "StafDB";

        const request = indexedDB.open(dbName, 2);

        request.onerror = function() {
            console.error("Gagal membuka IndexedDB");
        };

        request.onsuccess = function(event) {
            db = event.target.result;
            if (navigator.onLine) {
                syncOfflineData();
            }
        };

        request.onupgradeneeded = function(event) {
            db = event.target.result;
            if (!db.objectStoreNames.contains("staf")) {
                db.createObjectStore("staf", {
                    keyPath: "id",
                    autoIncrement: true
                });
            }
            if (!db.objectStoreNames.contains("editStaf")) {
                db.createObjectStore("editStaf", {
                    keyPath: "id"
                });
            }
        };

        // Form Tambah Staf
        document.getElementById("formStaf").addEventListener("submit", function(e) {
            e.preventDefault();
            const form = e.target;
            const nama = form.nama.value;
            const email = form.email.value;
            const jabatan = form.jabatan.value;
            const alamat = form.alamat.value;
            const fotoFile = form.foto.files[0];

            const reader = new FileReader();
            reader.onload = function() {
                const stafData = {
                    nama,
                    email,
                    jabatan,
                    alamat,
                    foto: reader.result, // Data URL disimpan langsung
                    timestamp: Date.now()
                };

                if (navigator.onLine) {
                    sendToServer(stafData, () => form.reset());
                } else {
                    saveToIndexedDB(stafData);
                    showNotifModal("Offline",
                        "Data tambah staf disimpan sementara. Akan dikirim saat online.", true,
                        () => form.reset());
                }
            };

            if (fotoFile) reader.readAsDataURL(fotoFile);
            else reader.onload();
        });

        // Form Edit Staf
        document.getElementById("formEditStaf").addEventListener("submit", function(e) {
            e.preventDefault();
            const form = e.target;

            const id = parseInt(form.elements["id"].value);
            const nama = form.elements["nama"].value;
            const email = form.elements["email"].value;
            const jabatan = form.elements["jabatan"].value;
            const alamat = form.elements["alamat"].value;
            const fotoFile = form.elements["foto"].files[0];

            const reader = new FileReader();
            reader.onload = function() {
                const editData = {
                    id,
                    nama,
                    email,
                    jabatan,
                    alamat,
                    foto: reader.result,
                    timestamp: Date.now()
                };

                if (navigator.onLine) {
                    sendEditToServer(editData, () => $('#editModal').modal('hide'));
                } else {
                    saveEditToIndexedDB(editData);
                    showNotifModal("Offline",
                        "Perubahan edit staf disimpan sementara. Akan dikirim saat online.",
                        true, () => $('#editModal').modal('hide'));
                }
            };

            if (fotoFile) reader.readAsDataURL(fotoFile);
            else reader.onload();
        });

        function saveToIndexedDB(data) {
            const tx = db.transaction("staf", "readwrite");
            tx.objectStore("staf").add(data);
        }

        function saveEditToIndexedDB(data) {
            const tx = db.transaction("editStaf", "readwrite");
            tx.objectStore("editStaf").put(data);
        }

        function syncOfflineData() {
            const tx1 = db.transaction("staf", "readonly");
            const store1 = tx1.objectStore("staf");
            store1.getAll().onsuccess = function(event) {
                const allData = event.target.result;
                allData.forEach(data => {
                    // Setiap data diproses unik lewat closure yang benar
                    sendToServer(Object.assign({}, data), function() {
                        const delTx = db.transaction("staf", "readwrite");
                        delTx.objectStore("staf").delete(data.id);
                    });
                });
            };

            const tx2 = db.transaction("editStaf", "readonly");
            const store2 = tx2.objectStore("editStaf");
            store2.getAll().onsuccess = function(event) {
                const allEditData = event.target.result;
                allEditData.forEach(data => {
                    sendEditToServer(Object.assign({}, data), function() {
                        const delTx = db.transaction("editStaf", "readwrite");
                        delTx.objectStore("editStaf").delete(data.id);
                    });
                });
            };
        }

        function sendToServer(data, callback = () => {}) {
            const formData = new FormData();
            formData.append("nama", data.nama);
            formData.append("email", data.email);
            formData.append("jabatan", data.jabatan);
            formData.append("alamat", data.alamat);
            if (data.foto) formData.append("foto", dataURLtoBlob(data.foto), "foto_" + data.timestamp + ".png");

            fetch("proses_tambah_staf.php", {
                    method: "POST",
                    body: formData
                })
                .then(res => res.json())
                .then(response => {
                    if (response.status === "success") {
                        showNotifModal("Berhasil", response.message, true, () => location.reload());
                    } else {
                        showNotifModal("Gagal", response.message, false);
                    }
                    callback();
                })
                .catch(err => console.error("Gagal kirim tambah ke server:", err));
        }

        function sendEditToServer(data, callback = () => {}) {
            const formData = new FormData();
            formData.append("id", data.id);
            formData.append("nama", data.nama);
            formData.append("email", data.email);
            formData.append("jabatan", data.jabatan);
            formData.append("alamat", data.alamat);
            if (data.foto) formData.append("foto", dataURLtoBlob(data.foto), "edit_" + data.id + ".png");

            fetch("proses_edit_staf.php", {
                    method: "POST",
                    body: formData
                })
                .then(res => res.json())
                .then(response => {
                    if (response.status === "success") {
                        showNotifModal("Berhasil", response.message, true, () => location.reload());
                    } else {
                        showNotifModal("Gagal", response.message, false);
                    }
                    callback();
                })
                .catch(err => console.error("Gagal kirim edit ke server:", err));
        }

        function dataURLtoBlob(dataURL) {
            const arr = dataURL.split(",");
            const mime = arr[0].match(/:(.*?);/)[1];
            const bstr = atob(arr[1]);
            let n = bstr.length;
            const u8arr = new Uint8Array(n);
            while (n--) {
                u8arr[n] = bstr.charCodeAt(n);
            }
            return new Blob([u8arr], {
                type: mime
            });
        }

        window.addEventListener("online", syncOfflineData);
    });
    </script>








</body>

</html>