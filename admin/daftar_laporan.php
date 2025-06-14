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
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Form Pengaturan</title>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <!-- Bootstrap 5 CSS -->
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
                <div class="container-fluid px-4">
                    <h2 class="mt-4">Pengaduan</h2>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">
                            <i class="bi bi-inbox me-2"></i>Daftar Pengaduan
                        </li>
                    </ol>
                    <div class="card-body">

                        <div class="table-responsive">
                            <table id="pengaduanTable" class="table table-bordered table-striped text-center">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Nama</th>
                                        <th class="text-center">Email</th>
                                        <th class="text-center">Status</th> <!-- Kolom status baru -->
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
        $query = mysqli_query($conn, "SELECT * FROM laporan");
        while ($data = mysqli_fetch_assoc($query)) {
        ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= htmlspecialchars($data['nama']) ?></td>
                                        <td><?= htmlspecialchars($data['email']) ?></td>
                                        <td>
                                            <?php
                    if ($data['status'] == 'Diterima') {
                        echo '<span class="badge bg-success">Diterima</span>';
                    } elseif ($data['status'] == 'Ditolak') {
                        echo '<span class="badge bg-danger">Ditolak</span>';
                    } else {
                        echo '<span class="badge bg-warning text-dark">Menunggu</span>';
                    }
                ?>
                                        </td>
                                        <td>
                                            <a href="#" class="btn btn-info btn-sm lihat-btn"
                                                data-id="<?= $data['id'] ?>">Lihat</a>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>

                        </div>
                        <!-- Modal Detail -->
                        <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h5 class="modal-title text-center" id="detailModalLabel">Detail Laporan</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Tutup"></button>
                                    </div>

                                    <div class="modal-body" id="modalContent">
                                        <!-- Konten detail akan dimuat lewat AJAX -->
                                        <div class="text-center">Memuat data...</div>
                                    </div>

                                    <div class="modal-footer">
                                        <div class="text-end w-100">
                                            <button class="btn btn-primary w-auto" onclick="printPDF()">Cetak
                                                PDF</button>
                                        </div>
                                    </div>


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

        <script src="../js/index-min.js"></script>
        <script src="js/bootstrap.bundle.min.js"></script>
        <script src="js/scripts.js"></script>

        <!-- jQuery + DataTables -->
        <script src="js/jquery.min.js"></script>
        <script src="js/dataTables.min.js"></script>
        <script src="js/dataTables.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
        <script src="tanggapan-offline.js"></script>


        <!-- Pastikan jQuery dan DataTables JS sudah diload di atas -->
        <script>
        $(document).ready(function() {
            const table = $('#pengaduanTable').DataTable({
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
                    zeroRecords: "Tidak ada data yang ditemukan",
                }
            });

            // Event delegation untuk tombol "Lihat" agar tetap bisa diklik setelah pagination
            $('#pengaduanTable tbody').on('click', '.lihat-btn', function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                const modalContent = $('#modalContent');
                modalContent.html('<div class="text-center">Memuat data...</div>');

                fetch('detail.php?id=' + id)
                    .then(res => res.text())
                    .then(data => {
                        modalContent.html(data);
                        new bootstrap.Modal(document.getElementById('detailModal')).show();
                    })
                    .catch(err => {
                        modalContent.html('<div class="text-danger">Gagal memuat data.</div>');
                        console.error(err);
                    });
            });
        });
        </script>


        <script>
        $(document).ready(function() {
            let clickedButton = '';

            // Tampilkan modal detail laporan
            $('.lihat-btn').click(function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                $('#modalContent').html('<div class="text-center">Memuat data...</div>');

                $.ajax({
                    url: 'detail.php',
                    type: 'GET',
                    data: {
                        id: id
                    },
                    success: function(response) {
                        $('#modalContent').html(response);
                        $('#detailModal').modal('show');
                    },
                    error: function() {
                        $('#modalContent').html(
                            '<div class="text-danger">Gagal memuat data.</div>');
                    }
                });
            });

            // Deteksi tombol klik (terima/tolak)
            $(document).on('click', '#form-tanggapan button[type="submit"]', function() {
                clickedButton = $(this).attr('name');
            });

            // Submit form tanggapan
            $(document).on('submit', '#form-tanggapan', async function(e) {
                e.preventDefault();

                const formData = $(this).serializeArray();
                let dataObj = {};
                formData.forEach(item => dataObj[item.name] = item.value);
                dataObj[clickedButton] = "1"; // Tambahkan field 'terima' atau 'tolak'

                const laporanId = dataObj['id'];

                if (!navigator.onLine) {
                    const db = await idb.openDB("tanggapan-db", 1, {
                        upgrade(db) {
                            if (!db.objectStoreNames.contains("tanggapan")) {
                                db.createObjectStore("tanggapan", {
                                    keyPath: "id"
                                });
                            }
                        }
                    });

                    await db.put("tanggapan", dataObj);
                    alert("📦 Offline: tanggapan disimpan dan akan dikirim otomatis saat online.");
                    $('#detailModal').modal('hide');
                    return;
                }

                // Kirim langsung ke server
                $.ajax({
                    url: 'proses_laporan.php',
                    type: 'POST',
                    data: $.param(dataObj),
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            if (clickedButton === 'terima') {
                                window.location.href = 'daftar_laporan.php';
                            } else {
                                $.get('detail.php', {
                                    id: laporanId
                                }, function(data) {
                                    $('#modalContent').html(data);

                                    if ($('#qrcode').length) {
                                        new QRCode(document.getElementById(
                                            "qrcode"), {
                                            text: "http://localhost/sekdes/admin/verifikasi.php?id=" +
                                                laporanId,
                                            width: 128,
                                            height: 128
                                        });
                                    }
                                });
                            }
                        } else {
                            alert('Gagal: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("XHR:", xhr.responseText);
                        console.error("Status:", status);
                        console.error("Error:", error);
                        alert('Terjadi kesalahan saat memproses data.');
                    }
                });
            });

            // Fungsi sync data saat online
            async function syncTanggapanData() {
                const db = await idb.openDB("tanggapan-db", 1, {
                    upgrade(db) {
                        if (!db.objectStoreNames.contains("tanggapan")) {
                            db.createObjectStore("tanggapan", {
                                keyPath: "id"
                            });
                        }
                    }
                });

                const all = await db.getAll("tanggapan");

                for (const entry of all) {
                    try {
                        const response = await $.ajax({
                            url: 'proses_laporan.php',
                            type: 'POST',
                            data: $.param(entry),
                            dataType: 'json'
                        });

                        if (response.status === 'success') {
                            await db.delete("tanggapan", entry.id);
                            console.log('✅ Tanggapan berhasil dikirim:', entry);
                        } else {
                            console.warn('❌ Gagal kirim:', response.message);
                        }
                    } catch (err) {
                        console.error("⚠️ Gagal sync:", err);
                    }
                }
            }

            // Jalankan sync saat online
            if (navigator.onLine) {
                syncTanggapanData();
            }

            // Sinkronisasi saat kembali online
            window.addEventListener('online', () => {
                console.log("🌐 Online kembali. Sinkronisasi...");
                syncTanggapanData();
            });
        });
        </script>



        <script>
        function printPDF() {
            const element = document.getElementById("modalContent");

            // Tunggu gambar selesai dimuat
            const images = element.querySelectorAll("img");
            let loadedCount = 0;

            if (images.length === 0) {
                generatePDF(element);
            } else {
                images.forEach((img) => {
                    if (img.complete) {
                        loadedCount++;
                        if (loadedCount === images.length) generatePDF(element);
                    } else {
                        img.onload = () => {
                            loadedCount++;
                            if (loadedCount === images.length) generatePDF(element);
                        };
                        img.onerror = () => {
                            loadedCount++;
                            if (loadedCount === images.length) generatePDF(element);
                        };
                    }
                });
            }
        }

        function generatePDF(element) {
            const noPrintElements = document.querySelectorAll('.no-print');

            // Sembunyikan elemen-elemen yang tidak ingin dicetak
            noPrintElements.forEach(el => el.style.display = 'none');

            const opt = {
                margin: 0.5,
                filename: 'laporan.pdf',
                image: {
                    type: 'jpeg',
                    quality: 0.98
                },
                html2canvas: {
                    scale: 2
                },
                jsPDF: {
                    unit: 'in',
                    format: 'letter',
                    orientation: 'portrait'
                }
            };

            html2pdf().set(opt).from(element).save().then(() => {
                // Tampilkan kembali elemen yang disembunyikan
                noPrintElements.forEach(el => el.style.display = '');
            });
        }
        </script>

</body>

</html>