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
                    aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">

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
                        <a class="nav-link" href="index.html">
                            <div class="sb-nav-link-icon"><i class="bi bi-people"></i></div>
                            Daftar Akun
                        </a>
                    </div>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="card mb-4">
                    <div class="card-body">
                        <h2 class="mt-4">Manajeman Akun</h2>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active"><i class="bi bi-people"></i>Daftar Akun</li>
                        </ol>
                        <button id="tambahBtn" class="btn btn-primary mb-3" data-bs-toggle="modal"
                            data-bs-target="#formModal">
                            <i class="bi bi-plus-lg"></i> Tambah
                        </button>
                        <div class="table-responsive">
                            <table id="datausersTable" class="table table-bordered table-striped text-center">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Nama</th>
                                        <th class="text-center">Username</th>
                                        <th class="text-center">Email</th>
                                        <th class="text-center">Alamat</th>
                                        <th class="text-center">Level</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
        $host = "localhost";
        $user = "u637089379_lapordesa";
        $pass = "Lapordesa123";
        $db   = "u637089379_lapordesa";
        $conn = new mysqli($host, $user, $pass, $db);

        if ($conn->connect_error) {
            die("Koneksi gagal: " . $conn->connect_error);
        }

        $no = 1;
        $query = mysqli_query($conn, "SELECT * FROM users");
        while ($data = mysqli_fetch_assoc($query)) {
        ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= htmlspecialchars($data['nama']) ?></td>
                                        <td><?= htmlspecialchars($data['username']) ?></td>
                                        <td><?= htmlspecialchars($data['email']) ?></td>
                                        <td><?= htmlspecialchars((string)($data['alamat'] ?? '')) ?></td>

                                        <td><?= htmlspecialchars($data['level']) ?></td>

                                        <td>

                                            <a href="#" class="btn btn-warning btn-sm btn-edit"
                                                data-id="<?= $data['id'] ?>">Edit</a> <a href="#"
                                                class="btn btn-danger btn-sm btn-delete"
                                                data-id="<?= $data['id'] ?>">Hapus</a>

                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Modal Edit Users -->
                        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <form id="formEditUsers" enctype="multipart/form-data" class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModalLabel">Edit Data Users</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" id="edit_id" name="id">

                                        <div class="mb-3">
                                            <label for="edit_nama" class="form-label">Nama Lengkap</label>
                                            <input type="text" id="edit_nama" name="nama" class="form-control" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="edit_username" class="form-label">Username</label>
                                            <input type="text" id="edit_username" name="username" class="form-control"
                                                required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="edit_password" class="form-label">Password (Kosongkan jika tidak
                                                diubah)</label>
                                            <input type="password" id="edit_password" name="password"
                                                class="form-control">
                                        </div>



                                        <div class="mb-3">
                                            <label for="edit_email" class="form-label">Email</label>
                                            <input type="email" id="edit_email" name="email" class="form-control"
                                                required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="level" class="form-label">Level</label>
                                            <select class="form-select" id="level" name="level" required>
                                                <option value="">-- Pilih Level --</option>
                                                <option value="kades">Kades</option>
                                                <option value="sekdes">Sekdes</option>
                                                <option value="masyarakat">Masyarakat</option>
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


                        <!-- Modal Form Tambah User -->
                        <div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="formModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <!-- Tambahkan modal-lg agar form lebih lebar -->
                                <div class="modal-content">
                                    <form id="formUsers" action="simpan_users.php" method="POST"
                                        enctype="multipart/form-data">

                                        <div class="modal-header">
                                            <h5 class="modal-title" id="formModalLabel">Form Users</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Tutup"></button>
                                        </div>

                                        <div class="modal-body">
                                            <div class="container-fluid">
                                                <div class="row">
                                                    <!-- Kolom Kiri -->
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="nama" class="form-label">Nama Lengkap</label>
                                                            <input type="text" class="form-control" id="nama"
                                                                name="nama" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="username" class="form-label">Username</label>
                                                            <input type="text" class="form-control" id="username"
                                                                name="username" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="password" class="form-label">Password</label>
                                                            <input type="password" class="form-control" id="password"
                                                                name="password" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="email" class="form-label">Email</label>
                                                            <input type="email" class="form-control" id="email"
                                                                name="email" required>
                                                        </div>
                                                    </div>

                                                    <!-- Kolom Kanan -->
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="level" class="form-label">Level</label>
                                                            <select class="form-select" id="level" name="level"
                                                                required>
                                                                <option value="">-- Pilih Level --</option>
                                                                <option value="kades">Kades</option>
                                                                <option value="sekdes">Sekdes</option>
                                                                <option value="masyarakat">Masyarakat</option>
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="alamat" class="form-label">Alamat</label>
                                                            <textarea class="form-control" id="alamat" name="alamat"
                                                                rows="3" required></textarea>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="foto" class="form-label">Foto</label>
                                                            <input type="file" class="form-control" id="foto"
                                                                name="foto" accept="image/*">
                                                        </div>
                                                    </div>
                                                </div>
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
                                    <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 4rem;"></i>
                                </div>
                                <h5 class="modal-title mb-2" id="confirmDeleteLabel">Konfirmasi Hapus</h5>
                                <p class="text-muted">Yakin ingin menghapus data ini?</p>
                                <div class="d-flex justify-content-center gap-2 mt-3">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Batal</button>
                                    <a id="confirmDeleteBtn" href="#" class="btn btn-danger">Hapus</a>
                                </div>
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

    <script>
    $(document).ready(function() {
        // Inisialisasi DataTables
        const table = $('#datausersTable').DataTable({
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
        $('#datausersTable').on('click', '.btn-edit', function(e) {
            e.preventDefault();
            const id = $(this).data('id');

            $.ajax({
                url: 'get_user.php',
                method: 'GET',
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(data) {
                    $('#edit_id').val(data.id);
                    $('#edit_nama').val(data.nama);
                    $('#edit_username').val(data.username);
                    $('#edit_password').val(data.password);
                    $('#edit_email').val(data.email);
                    $('#edit_level').val(data.level);
                    $('#edit_alamat').val(data.alamat);

                    $('#editModal').modal('show');
                },
                error: function() {
                    alert('Gagal mengambil data users.');
                }
            });
        });
        // Submit form edit staf
        $('#formEditUsers').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                url: 'edit_user.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    $('#editModal').modal('hide'); // Tutup modal edit

                    setTimeout(() => {
                        // Atur pesan dan ikon
                        $('#notifMessage').text('Data user berhasil diperbarui!');
                        $('#notifIcon')
                            .removeClass()
                            .addClass('bi bi-check-circle-fill text-success')
                            .css('font-size', '4rem');

                        // Tampilkan modal notifikasi
                        const notifModal = new bootstrap.Modal(document
                            .getElementById('notifModal'));
                        notifModal.show();

                        // Hindari multiple trigger dengan .off()
                        $('#notifModal').off('hidden.bs.modal').on(
                            'hidden.bs.modal',
                            function() {
                                location.reload();
                            });
                    }, 500); // Delay agar modal edit benar-benar tertutup
                },
                error: function() {
                    alert('Gagal memperbarui data staf.');
                }
            });
        });



    });
    </script>

    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
    <script>
    window.addEventListener('DOMContentLoaded', () => {
        $('#notifMessage').text("Akun baru berhasil ditambahkan!");
        $('#notifIcon')
            .removeClass()
            .addClass('bi bi-check-circle-fill text-success')
            .css('font-size', '4rem');

        const notifModal = new bootstrap.Modal(document.getElementById('notifModal'));
        notifModal.show();

        // Pastikan reload hanya sekali
        $('#notifModal').off('hidden.bs.modal').on('hidden.bs.modal', function() {
            // Hapus parameter success dari URL sebelum reload
            const url = new URL(window.location);
            url.searchParams.delete('success');
            window.location.href = url.toString();
        });
    });
    </script>
    <?php endif; ?>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const deleteButtons = document.querySelectorAll('.btn-delete');
        const deleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
        const confirmBtn = document.getElementById('confirmDeleteBtn');

        deleteButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const userId = button.getAttribute('data-id');
                confirmBtn.setAttribute('href', 'hapus_user.php?id=' + userId);
                deleteModal.show();
            });
        });
    });
    </script>



</body>

</html>