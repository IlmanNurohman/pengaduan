<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="description" content="">
    <meta name="author" content="">

    <title>Pod Talk Free CSS Template by TemplateMo</title>

    <!-- CSS FILES -->
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400&family=Sono:wght@200;300;400;500;700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="css/bootstrap.min.css">

    <link rel="stylesheet" href="css/bootstrap-icons.css">

    <link rel="stylesheet" href="css/owl.carousel.min.css">

    <link rel="stylesheet" href="css/owl.theme.default.min.css">

    <link href="css/templatemo-pod-talk.css" rel="stylesheet">
    <!--

TemplateMo 584 Pod Talk

https://templatemo.com/tm-584-pod-talk

-->
</head>

<body>

    <main>

        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand me-lg-5 me-0" href="index.html">
                    <img src="images/garut.png" class="logo-image img-fluid" alt="templatemo pod talk"
                        style="max-height: 50px; width: auto;">Desa Purwajaya
                </a>



                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-lg-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Home</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="about.php">About</a>
                        </li>

                        <li class="nav-item ">
                            <a class="nav-link" href="">Detail Laporan</a>

                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="contact.html">Contact</a>
                        </li>
                    </ul>

                    <div class="ms-4">
                        <a href="#section_2" class="btn custom-btn custom-border-btn smoothscroll">Login</a>
                    </div>
                </div>
            </div>
        </nav>


        <header class="site-header d-flex flex-column justify-content-center align-items-center">
            <div class="container">
                <div class="row">

                    <div class="col-lg-12 col-12 text-center">

                        <h2 class="mb-0">Detail Page</h2>
                    </div>

                </div>
            </div>
        </header>


        <?php
// Koneksi langsung ke database
$servername = "localhost";
$username = "root"; // Ganti jika bukan root
$password = "";     // Ganti sesuai password MySQL kamu
$database = "pengaduan";

$conn = new mysqli($servername, $username, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$sql = "SELECT * FROM laporan WHERE id = $id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

if (!$row) {
    echo "<p>Laporan tidak ditemukan.</p>";
    exit;
}

$imgPath = !empty($row['foto']) ? 'uploads/' . $row['foto'] : 'images/default.jpg';

// Ambil user_id dari laporan, misal:
$userId = isset($row['user_id']) ? intval($row['user_id']) : 0;

// Default foto profil jika tidak ada
$profilePhoto = 'images/default_profile.png';

// Jika ada userId, ambil data foto profil user dari tabel users
if ($userId > 0) {
    $sqlUser = "SELECT foto FROM users WHERE id = $userId LIMIT 1";
    $resultUser = $conn->query($sqlUser);

    if ($resultUser && $resultUser->num_rows > 0) {
        $userData = $resultUser->fetch_assoc();
        if (!empty($userData['foto'])) {
            $profilePhoto = 'uploads/' . $userData['foto']; // Sesuaikan path foto profil
        }
    }
}

?>

        <section class="latest-podcast-section section-padding pb-0" id="section_2">
            <div class="container">
                <div class="row justify-content-center">

                    <div class="col-lg-10 col-12">
                        <div class="section-title-wrap mb-5">
                            <h4 class="section-title">Detail Laporan</h4>
                        </div>

                        <div class="row">
                            <div class="col-lg-3 col-12">
                                <div class="custom-block-icon-wrap">
                                    <div class="custom-block-image-wrap custom-block-image-detail-page">
                                        <img src="<?= htmlspecialchars($imgPath) ?>"
                                            class="custom-block-image img-fluid" alt="">
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-9 col-12">
                                <div class="custom-block-info">
                                    <div class="custom-block-top d-flex mb-1">


                                        <small>
                                            <i class="bi-clock-fill custom-icon"></i>
                                            <?= date("d M Y", strtotime($row['tanggal_lapor'])) ?>
                                        </small>

                                        <small class="ms-auto">Status <span
                                                class="badge"><?= $row['status'] ?></span></small>
                                    </div>

                                    <h2 class="mb-2">Laporan dari <?= htmlspecialchars($row['nama']) ?></h2>

                                    <p><?= nl2br(htmlspecialchars($row['pesan'])) ?></p>

                                    <div
                                        class="profile-block profile-detail-block d-flex flex-wrap align-items-center mt-5">
                                        <div class="d-flex mb-3 mb-lg-0 mb-md-0">
                                            <img src="<?= htmlspecialchars($profilePhoto) ?>"
                                                class="profile-photo rounded-circle me-2" alt="Foto Profil User"
                                                style="width: 40px; height: 40px; object-fit: cover;">


                                            <p>
                                                <?= htmlspecialchars($row['nama']) ?>
                                                <img src="images/verified.png" class="verified-image img-fluid" alt="">
                                                <strong>Pelapor</strong>
                                            </p>
                                        </div>

                                        <ul class="social-icon ms-lg-auto ms-md-auto">
                                            <li class="social-icon-item">
                                                <a href="#" class="social-icon-link bi-instagram"></a>
                                            </li>
                                            <li class="social-icon-item">
                                                <a href="#" class="social-icon-link bi-twitter"></a>
                                            </li>
                                            <li class="social-icon-item">
                                                <a href="#" class="social-icon-link bi-whatsapp"></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>



        <section class="trending-podcast-section section-padding">
            <div class="container">
                <div class="row">

                    <div class="col-lg-12 col-12">
                        <div class="section-title-wrap mb-5">
                            <h4 class="section-title">Daftar Laporan</h4>
                        </div>
                    </div>

                    <?php
           

           $sql = "SELECT laporan.id, laporan.nama, laporan.foto, laporan.status, laporan.tanggal_lapor, 
               users.foto AS foto_profil
        FROM laporan 
        JOIN users ON laporan.user_id = users.id
        ORDER BY laporan.id DESC LIMIT 3";

            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                   $imgPath = !empty($row['foto']) ? 'uploads/' . $row['foto'] : 'images/default.jpg';

            ?>
                    <div class="col-lg-4 col-12 mb-4 mb-lg-0">
                        <div class="custom-block custom-block-full">
                            <div class="custom-block-image-wrap">
                                <a href="detail_laporan.php?id=<?= $row['id'] ?>">

                                    <img src="<?= htmlspecialchars($imgPath) ?>" class="custom-block-image img-fluid"
                                        alt="">
                                </a>
                            </div>

                            <div class="custom-block-info">
                                <h5 class="mb-2">
                                    <a href="detail-page.html">
                                        Pelapor
                                    </a>
                                </h5>

                                <div class="profile-block d-flex">
                                    <img src="uploads/<?php echo htmlspecialchars($row['foto_profil']); ?>"
                                        class="profile-block-image img-fluid" alt="Foto Profil">
                                    <p><?= htmlspecialchars($row['nama']) ?>
                                        <img src="images/verified.png" class="verified-image img-fluid" alt="">
                                        <strong><?= htmlspecialchars($row['status']) ?></strong>
                                    </p>
                                </div>

                                <p class="mb-0">
                                    <i class="bi bi-calendar-event me-1"></i>
                                    <?= date("d M Y", strtotime($row['tanggal_lapor'])) ?>
                                </p>


                            </div>

                            <div class="social-share d-flex flex-column ms-auto">
                                <a href="#" class="badge ms-auto">
                                    <i class="bi-heart"></i>
                                </a>
                                <a href="#" class="badge ms-auto">
                                    <i class="bi-bookmark"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "<p class='col-12'>Belum ada laporan yang tersedia.</p>";
            }
            ?>
                </div>
            </div>
        </section>
    </main>


    <footer class="site-footer">
        <div class="container">
            <div class="row">

                <div class="col-lg-6 col-12 mb-5 mb-lg-0">
                    <div class="subscribe-form-wrap">
                        <h6>Subscribe. Every weekly.</h6>

                        <form class="custom-form subscribe-form" action="#" method="get" role="form">
                            <input type="email" name="subscribe-email" id="subscribe-email" pattern="[^ @]*@[^ @]*"
                                class="form-control" placeholder="Email Address" required="">

                            <div class="col-lg-12 col-12">
                                <button type="submit" class="form-control" id="submit">Subscribe</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-12 mb-4 mb-md-0 mb-lg-0">
                    <h6 class="site-footer-title mb-3">Contact</h6>

                    <p class="mb-2"><strong class="d-inline me-2">Phone:</strong> 010-020-0340</p>

                    <p>
                        <strong class="d-inline me-2">Email:</strong>
                        <a href="#">inquiry@pod.co</a>
                    </p>
                </div>

                <div class="col-lg-3 col-md-6 col-12">
                    <h6 class="site-footer-title mb-3">Download Mobile</h6>

                    <div class="site-footer-thumb mb-4 pb-2">
                        <div class="d-flex flex-wrap">
                            <a href="#">
                                <img src="images/app-store.png" class="me-3 mb-2 mb-lg-0 img-fluid" alt="">
                            </a>

                            <a href="#">
                                <img src="images/play-store.png" class="img-fluid" alt="">
                            </a>
                        </div>
                    </div>

                    <h6 class="site-footer-title mb-3">Social</h6>

                    <ul class="social-icon">
                        <li class="social-icon-item">
                            <a href="#" class="social-icon-link bi-instagram"></a>
                        </li>

                        <li class="social-icon-item">
                            <a href="#" class="social-icon-link bi-twitter"></a>
                        </li>

                        <li class="social-icon-item">
                            <a href="#" class="social-icon-link bi-whatsapp"></a>
                        </li>
                    </ul>
                </div>

            </div>
        </div>

        <div class="container pt-5">
            <div class="row align-items-center">

                <div class="col-lg-2 col-md-3 col-12">
                    <a class="navbar-brand me-lg-5 me-0" href="index.html">
                        <img src="images/garut.png" class="logo-image img-fluid" alt="templatemo pod talk"
                            style="max-height: 50px; width: auto;">Desa Purwajaya
                    </a>
                </div>

                <div class="col-lg-7 col-md-9 col-12">
                    <ul class="site-footer-links">
                        <li class="site-footer-link-item">
                            <a href="#" class="site-footer-link">Homepage</a>
                        </li>

                        <li class="site-footer-link-item">
                            <a href="#" class="site-footer-link">Browse episodes</a>
                        </li>

                        <li class="site-footer-link-item">
                            <a href="#" class="site-footer-link">Help Center</a>
                        </li>

                        <li class="site-footer-link-item">
                            <a href="#" class="site-footer-link">Contact Us</a>
                        </li>
                    </ul>
                </div>

                <div class="col-lg-3 col-12">
                    <p class="copyright-text mb-0">Copyright © 2025 Talk Pod Company
                        <br><br>
                    </p>
                </div>
            </div>
        </div>
    </footer>


    <!-- JAVASCRIPT FILES -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/custom.js"></script>

</body>

</html>