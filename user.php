<?php
session_start(); // Tambahkan ini untuk mulai session
$host = "localhost";
$user = "u637089379_lapordesa";
$pass = "Lapordesa123";
$db   = "u637089379_lapordesa";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}


$user_id = $_SESSION['user_id'];
$query = mysqli_query($conn, "SELECT foto FROM users WHERE id = '$user_id'");
$data = mysqli_fetch_assoc($query);
$foto = $data['foto'] ? $data['foto'] : 'default.png'; // fallback jika foto kosong

$user_id = $_SESSION['user_id']; // Ambil user_id dari session

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama   = $_POST['nama'];
    $email  = $_POST['email'];
    $alamat = $_POST['alamat'];
    $pesan  = $_POST['pesan'];
    $tanggal_lapor = $_POST['tanggal'];
    $foto = $_FILES['foto']['name'];

    if (!DateTime::createFromFormat('Y-m-d', $tanggal_lapor)) {
        die("Format tanggal tidak valid.");
    }

    if ($foto) {
        $folder = 'uploads/';
        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }
        move_uploaded_file($_FILES['foto']['tmp_name'], $folder . $foto);
    }

    // Masukkan user_id ke dalam query
    $stmt = $conn->prepare("INSERT INTO laporan (user_id, nama, email, alamat, pesan, foto, tanggal_lapor) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssss", $user_id, $nama, $email, $alamat, $pesan, $foto, $tanggal_lapor);

    if ($stmt->execute()) {
        header("Location: user.php");
        exit();
    } else {
        echo "Gagal menyimpan data: " . $stmt->error;
    }

    $stmt->close();
}
// Ambil data pengaduan user yang sedang login
$sql = "SELECT * FROM laporan WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();


$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Form Pengaturan</title>
    <link href="admin/css/bootstrap.min.css" rel="stylesheet">
    <link href="admin/css/styles.css" rel="stylesheet" />
    <link href="admin/css/datatables.css" rel="stylesheet" />
    <link href="admin/css/datatables.min.css" rel="stylesheet" />
    <link href="admin/assets/fontawesome/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap-icons.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <style>
    /* Ukuran tengah-tengah antara default dan modal-lg */
    @media (min-width: 768px) {
        .custom-modal-width {
            max-width: 600px;
            /* Sesuaikan sesuai kebutuhan, default ~500px, modal-lg ~800px */
        }
    }
    </style>

</head>

<body class="sb-nav-fixed">
    <?php if (isset($_SESSION['user_id'])): ?>

    <?php else: ?>
    <!-- Login OFFLINE -->
    <script>
    const userData = localStorage.getItem('userData');
    if (userData) {
        const user = JSON.parse(userData);


    } else {
        alert("Akses ditolak. Silakan login terlebih dahulu.");
        window.location.href = "login.html";
    }
    </script>
    <?php endif; ?>



    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="index.html">
            <img src="images/garut.png" alt="" style="height: 30px; margin-right: 10px;">
            Desa Purwajaya
        </a>

        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!">
            <i class="fas fa-bars"></i>
        </button>
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
                    <li><a class="dropdown-item" href="profil.php"><i class="bi bi-person me-1"></i>Profil</a></li>

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
                        <a class="nav-link" href="user.php">
                            <div class="sb-nav-link-icon"><i class="bi-columns-gap"></i></div>
                            Pengaduan
                        </a>
                    </div>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h2 class="mt-4">Dashboard</h2>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">
                            <i class="bi bi-journal-plus me-2"></i></i>Pengaduan
                        </li>
                    </ol>


                    <!-- Tombol di sebelah kiri -->
                    <div class="d-flex justify-content-start my-4">
                        <button id="tambahPengaduanBtn" class="btn btn-primary"><i class="bi bi-plus-lg"></i>Tambah
                            Pengaduan</button>
                    </div>


                    <!-- Tabel Pengaduan -->
                    <div class="table-responsive">
                        <table id="pengaduanTable" class="table table-bordered table-striped text-center">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Tanggal</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
            $no = 1; 
            $modals = '';
            $qrScripts = '';
            $result->data_seek(0);
            while ($row = $result->fetch_assoc()) {
                $id = $row['id'];
            ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= date('d-m-Y', strtotime($row['tanggal_lapor'])); ?></td>
                                    <td>
                                        <?php if ($row['status'] == 'Menunggu') { ?>
                                        <span class="badge bg-warning">Menunggu</span>
                                        <?php } elseif ($row['status'] == 'Diterima') { ?>
                                        <span class="badge bg-success">Diterima</span>
                                        <?php } elseif ($row['status'] == 'Ditolak') { ?>
                                        <span class="badge bg-danger">Ditolak</span>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#detailModal<?= $id ?>">Lihat Detail</button>
                                    </td>
                                </tr>

                                <?php ob_start(); ?>
                                <!-- Modal Detail -->
                                <div class="modal fade" id="detailModal<?= $id ?>" tabindex="-1"
                                    aria-labelledby="detailModalLabel<?= $id ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered custom-modal-width">

                                        <div id="modalContent-<?= $id ?>" class="modal-content px-4 py-3">

                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>

                                            <div class="modal-body">
                                                <!-- Kop Surat -->
                                                <div class="row align-items-center border-bottom pb-3 mb-3">
                                                    <div class="col-12 col-md-2 text-center mb-2 mb-md-0">
                                                        <img src="admin/assets/img/garut.png" alt="Logo" width="90">
                                                    </div>
                                                    <div
                                                        class="col-12 col-md-10 d-flex flex-column align-items-center text-center mx-auto">
                                                        <h5 class="fw-bold mb-0">PEMERINTAH KABUPATEN GARUT</h5>
                                                        <h6 class="fw-bold mb-0">KECAMATAN PEUNDEUY</h6>
                                                        <h5 class="fw-bold">DESA PURWAJAYA</h5>
                                                        <small>Jalan Desa Purwajaya No. 01 Peundeuy-Garut Post
                                                            44178</small>
                                                    </div>

                                                </div>


                                            </div>

                                            <h6 class="text-center fw-bold  mb-4">LAPORAN
                                                MASYARAKAT</h6>

                                            <!-- Isi Laporan -->
                                            <div class="row justify-content-center">
                                                <div class="col-md-10">
                                                    <table class="table table-borderless table-sm">
                                                        <tr>
                                                            <td style="width: 130px;">Nama</td>
                                                            <td style="width: 10px;">:</td>
                                                            <td><?= htmlspecialchars($row['nama']) ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Alamat</td>
                                                            <td>:</td>
                                                            <td><?= htmlspecialchars($row['alamat']) ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Pesan</td>
                                                            <td>:</td>
                                                            <td><?= nl2br(htmlspecialchars($row['pesan'])) ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Tanggal</td>
                                                            <td>:</td>
                                                            <td><?= date('d-m-Y', strtotime($row['tanggal_lapor'])) ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Status</td>
                                                            <td>:</td>
                                                            <td><?= htmlspecialchars($row['status']) ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Foto</td>
                                                            <td>:</td>
                                                            <td>
                                                                <?php if (!empty($row['foto'])): ?>
                                                                <img src="uploads/<?= htmlspecialchars($row['foto']) ?>"
                                                                    alt="Foto" width="200">
                                                                <?php else: ?>
                                                                <em class="text-muted">Tidak tersedia</em>
                                                                <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Tanggapan</td>
                                                            <td>:</td>
                                                            <td><?= nl2br(htmlspecialchars($row['tanggapan'] ?? '-')) ?>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>

                                            <!-- Tanda Tangan Digital -->
                                            <?php if (strtolower($row['status']) === 'diterima'): ?>
                                            <div class="text-center mt-4">
                                                <p class="mb-1 fw-semibold">Tanda Tangan Digital</p>
                                                <div id="qrcode-<?= $id ?>" class="mb-2 d-inline-block"></div>

                                            </div>
                                            <?php endif; ?>
                                            <div class="text-end mt-3">
                                                <button onclick="downloadPDF('modalContent-<?= $id ?>')"
                                                    class="btn btn-primary no-print">
                                                    Download PDF
                                                </button>
                                            </div>




                                        </div>

                                    </div>
                                </div>
                    </div>
                    <?php 
            $modals .= ob_get_clean();

            if (strtolower($row['status']) === 'diterima') {
                $qrScripts .= "new QRCode(document.getElementById('qrcode-{$id}'), {
                    text: 'https://lapordesa.site/admin/verifikasi.php?id={$id}',
                    width: 128,
                    height: 128
                });\n";
            }
            } // End while
            ?>
                    </tbody>
                    </table>
                </div>

                <!-- Modal Output -->
                <?= $modals ?>

                <!-- QR Code Script -->
                <script>
                document.addEventListener("DOMContentLoaded", function() {
                    <?= $qrScripts ?>
                });
                </script>


                <!-- Modal Form Pengaduan -->
                <div class="modal fade" id="pengaduanModal" tabindex="-1" aria-labelledby="pengaduanModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form id="pengaduanForm" enctype="multipart/form-data">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="pengaduanModalLabel">Form Pengaduan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Tutup"></button>
                                </div>
                                <div class="modal-body">
                                    <!-- Nama otomatis -->
                                    <div class="mb-3">
                                        <label for="nama" class="form-label">Nama</label>
                                        <input type="text" class="form-control" id="nama" name="nama"
                                            value="<?= $_SESSION['nama']; ?>" readonly />
                                    </div>

                                    <!-- Email otomatis -->
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            value="<?= $_SESSION['email']; ?>" readonly />
                                    </div>

                                    <div class="mb-3">
                                        <label for="alamat" class="form-label">Alamat</label>
                                        <input type="text" class="form-control" id="alamat" name="alamat"
                                            placeholder=" Contoh Pamukiman 003/001 " required />
                                    </div>

                                    <!-- Pesan wajib diisi -->
                                    <div class=" mb-3">
                                        <label for="pesan" class="form-label">Pesan</label>
                                        <textarea class="form-control" id="pesan" name="pesan" rows="3"
                                            required></textarea>
                                    </div>

                                    <!-- Foto opsional -->
                                    <div class="mb-3">
                                        <label for="foto" class="form-label">Foto (Opsional)</label>
                                        <input type="file" class="form-control" id="foto" name="foto" accept="image/*"
                                            onchange="cekUkuranFile(this)" />
                                        <small id="peringatanUkuran" class="text-danger"></small>
                                    </div>

                                    <div class="mb-3">
                                        <label for="tanggal" class="form-label">Tanggal</label>
                                        <input type="date" id="tanggal" name="tanggal" required class="form-control" />
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-success" id="submitButton">Kirim</button>

                                </div>
                            </form>

                        </div>
                    </div>
                </div>
        </div>
        <!-- Modal Notifikasi Pengaduan -->
        <div class="modal fade" id="notifikasiModal" tabindex="-1" aria-labelledby="notifikasiModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content text-center p-4">
                    <div class="modal-body">
                        <div class="mb-3">
                            <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                        </div>
                        <h5 class="modal-title mb-2" id="notifikasiModalLabel">Pengaduan Berhasil Dikirim</h5>
                        <p class="text-muted">Terima kasih atas partisipasi Anda.</p>
                        <button type="button" class="btn btn-success mt-3" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="offlineWarningModal" tabindex="-1" aria-labelledby="offlineWarningModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content text-center p-4">
                    <div class="modal-body">
                        <div class="mb-3">
                            <i class="bi bi-exclamation-triangle-fill text-warning" style="font-size: 4rem;"></i>
                        </div>
                        <h5 class="modal-title mb-2" id="offlineWarningModalLabel">Pengaduan Belum Terkirim</h5>
                        <p class="text-muted">Anda memiliki pengaduan yang belum terkirim. Silakan sambungkan ke
                            internet agar data dapat dikirim ke server.</p>
                        <button type="button" class="btn btn-warning mt-3" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="offlineModal" tabindex="-1" aria-labelledby="offlineModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content text-center p-4">
                    <div class="modal-body">
                        <div class="mb-3">
                            <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                        </div>
                        <h5 class="modal-title mb-2 text-success" id="offlineModalLabel">Data Disimpan Sementara</h5>
                        <p class="text-muted">Anda sedang offline. Pengaduan akan dikirim otomatis saat online kembali.
                        </p>
                        <button type="button" class="btn btn-success mt-3"
                            onclick="window.location.href='user.php?pengaduan=terkirim_offline'">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Proses Kirim -->
        <div class="modal fade" id="prosesModal" tabindex="-1" aria-labelledby="prosesModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content text-center">
                    <div class="modal-body p-4">
                        <div class="spinner-border text-primary mb-3" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p>Sedang mengirim pengaduan...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Konfirmasi Logout -->
        <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content text-center p-4">
                    <div class="modal-body">
                        <div class="mb-3">
                            <i class="bi bi-question-circle-fill text-warning" style="font-size: 4rem;"></i>
                        </div>
                        <h5 class="modal-title mb-2" id="logoutModalLabel">Yakin ingin logout?</h5>

                        <div class="d-flex justify-content-center gap-3 mt-3">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <a href="logout.php" class="btn btn-danger">Ya</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        </main>

    </div>
    </div>
    <!-- Library IDB untuk IndexedDB Promised -->
    <script src="js/index-min.js"></script>



    <script src="admin/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script src="admin/js/scripts.js"></script>
    <!-- jQuery + DataTables -->
    <script src="admin/js/jquery.min.js"></script>
    <script src="admin/js/datatables.js"></script>
    <script src="admin/js/datatables.min.js"></script>
    <script src="js/html2pdf.bundle.min.js"></script>

    <script>
    // Tandai status login awal
    if (localStorage.getItem('loginBaru') === null) {
        if (!navigator.onLine) {
            // Login pertama OFFLINE
            localStorage.setItem('loginBaru', 'false');
        } else {
            // Login pertama ONLINE
            localStorage.setItem('loginBaru', 'true');
        }
    }
    sessionStorage.removeItem('baruKirimOffline');
    </script>

    <script>
    document.addEventListener('DOMContentLoaded', async () => {
        // Reset flag baruSimpanOffline agar modal tidak muncul terus menerus
        sessionStorage.removeItem('baruSimpanOffline');

        if (!window.idb || !window.idb.openDB) {
            console.error("Library IDB gagal dimuat.");
            alert("Gagal memuat fitur offline.");
            return;
        }

        const {
            openDB
        } = window.idb;

        async function openIndexedDB() {
            return await openDB("pengaduan-db", 1, {
                upgrade(db) {
                    if (!db.objectStoreNames.contains("u637089379_lapordesa")) {
                        db.createObjectStore("u637089379_lapordesa", {
                            keyPath: "id",
                            autoIncrement: true
                        });
                    }
                },
            });
        }

        async function syncOfflineData() {
            const db = await openIndexedDB();
            const all = await db.getAll("u637089379_lapordesa");
            let berhasilSync = false;

            for (const data of all) {
                const formData = new FormData();
                for (const key in data) {
                    if (key !== 'id') formData.append(key, data[key]);
                }

                try {
                    const response = await fetch("proses_pengaduan.php", {
                        method: "POST",
                        body: formData
                    });

                    if (response.ok) {
                        await db.delete("u637089379_lapordesa", data.id);
                        console.log("✅ Data dikirim dan dihapus dari IndexedDB:", data);
                        berhasilSync = true;
                    } else {
                        console.error("❌ Gagal kirim:", response.statusText);
                    }
                } catch (err) {
                    console.error("❌ Error kirim offline data:", err);
                }
            }

            if (berhasilSync) {
                sessionStorage.setItem("pengaduan_berhasil", "1");
                location.reload();
            }
        }

        const form = document.getElementById("pengaduanForm");
        const prosesModal = new bootstrap.Modal(document.getElementById('prosesModal'));

        form.addEventListener("submit", async function(e) {
            e.preventDefault();
            const data = new FormData(form);
            const obj = {};
            data.forEach((val, key) => obj[key] = val);

            if (!navigator.onLine) {
                // === OFFLINE MODE ===
                try {
                    const db = await openIndexedDB();
                    await db.add("u637089379_lapordesa", obj);
                    console.log("📦 Data DISIMPAN ke IndexedDB karena OFFLINE:", obj);
                    sessionStorage.setItem('baruSimpanOffline', '1');

                    // Sembunyikan modal proses
                    prosesModal.hide();

                    // Tampilkan modal offline sukses
                    const offlineModal = new bootstrap.Modal(document.getElementById(
                        'offlineModal'));
                    offlineModal.show();

                    // ❌ Tidak reload halaman di sini
                    form.reset(); // Opsional: reset form saja

                } catch (err) {
                    alert("Gagal simpan offline: " + err.message);
                }
                offlineModal._element.addEventListener('hidden.bs.modal', () => {
                    // Pastikan tidak reload
                    console.log("✅ Modal ditutup, tetap di halaman cache.");
                });


            } else {
                // === ONLINE MODE ===
                prosesModal.show();
                try {
                    const response = await fetch("proses_pengaduan.php", {
                        method: "POST",
                        body: data
                    });

                    prosesModal.hide();

                    if (response.ok) {
                        console.log("🟢 Data dikirim langsung tanpa masuk IndexedDB.");
                        sessionStorage.setItem('baruKirimOffline', '1');

                        const notifikasiModal = new bootstrap.Modal(document.getElementById(
                            'notifikasiModal'));
                        notifikasiModal.show();

                        // ❌ Hapus event reload — cukup reset form saja
                        form.reset();

                    } else {
                        console.error("❌ Gagal kirim saat online.");
                        alert("Gagal mengirim pengaduan.");
                    }

                } catch (err) {
                    prosesModal.hide();
                    alert("Kesalahan saat kirim data: " + err.message);
                }
            }
        });


        // Jalankan sync jika online
        if (navigator.onLine) {
            await syncOfflineData();
        }

        // Tampilkan modal jika sebelumnya berhasil sync
        if (sessionStorage.getItem("pengaduan_berhasil") === "1") {
            const notifikasiModal = new bootstrap.Modal(document.getElementById('notifikasiModal'));
            notifikasiModal.show();
            sessionStorage.removeItem("pengaduan_berhasil");
        }

        // ✅ Jalankan warning modal HANYA saat login ulang
        if (localStorage.getItem('loginBaru') === 'true') {
            await checkPendingReports();
            localStorage.removeItem('loginBaru');
        }

        // Saat kembali online, lakukan sinkronisasi
        window.addEventListener("online", () => {
            console.log("🌐 Online kembali. Sinkronisasi data...");
            syncOfflineData();
        });

        async function checkPendingReports() {
            if (!navigator.onLine) {
                const db = await openIndexedDB();
                const all = await db.getAll("u637089379_lapordesa");

                const sudahLogin = sessionStorage.getItem('pengaduanLoginAktif');
                const baruKirim = sessionStorage.getItem('baruKirimOffline');
                const baruSimpan = sessionStorage.getItem('baruSimpanOffline');
                const loginBaru = localStorage.getItem('loginBaru'); // ambil info login awal

                // ⚠️ HANYA munculkan modal jika login awal ONLINE
                if (all.length > 0 && !sudahLogin && !baruKirim && !baruSimpan && loginBaru ===
                    'true') {
                    const offlineModal = new bootstrap.Modal(document.getElementById(
                        'offlineWarningModal'));
                    offlineModal.show();
                    sessionStorage.setItem('pengaduanLoginAktif', 'true');
                }
            }
        }

    });
    </script>






    <script>
    function cekUkuranFile(input) {
        const file = input.files[0];
        const maxSize = 2 * 1024 * 1024; // 2MB
        const warning = document.getElementById("peringatanUkuran");

        if (file && file.size > maxSize) {
            warning.textContent = "Ukuran file maksimal 2MB!";
            input.value = ""; // Reset input file
        } else {
            warning.textContent = "";
        }
    }
    </script>

    <script>
    // Event untuk menampilkan modal tambah pengaduan
    document.getElementById("tambahPengaduanBtn").addEventListener("click", function() {
        var modal = new bootstrap.Modal(document.getElementById("pengaduanModal"));
        modal.show();
    });

    // Inisialisasi DataTable
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
            zeroRecords: "Tidak ada data yang ditemukan"
        }
    });
    </script>
    <script>
    function downloadPDF(elementId) {
        const element = document.getElementById(elementId);

        // Sembunyikan tombol download (dan elemen lain dengan class 'no-print')
        const hiddenElements = element.querySelectorAll('.no-print');
        hiddenElements.forEach(el => el.style.display = 'none');

        var opt = {
            margin: 0.3,
            filename: 'detail_pengaduan.pdf',
            image: {
                type: 'jpeg',
                quality: 0.98
            },
            html2canvas: {
                scale: 2
            },
            jsPDF: {
                unit: 'in',
                format: 'a4',
                orientation: 'portrait'
            }
        };

        html2pdf().set(opt).from(element).save().then(() => {
            // Tampilkan kembali elemen yang disembunyikan
            hiddenElements.forEach(el => el.style.display = '');
        });
    }
    </script>

</body>

</html>