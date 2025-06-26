 <?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
 
$conn = mysqli_connect("localhost", "u637089379_lapordesa", "Lapordesa123","u637089379_lapordesa");

$tahun = $_GET['tahun'] ?? date('Y');

// Ambil semua tahun
$tahunQuery = mysqli_query($conn, "SELECT DISTINCT tahun_anggaran FROM apbd_desa ORDER BY tahun_anggaran DESC");

// Ambil data APBD berdasarkan tahun
$apbdQuery = mysqli_query($conn, "SELECT * FROM apbd_desa WHERE tahun_anggaran = '$tahun'");
$apbdData = mysqli_fetch_assoc($apbdQuery);
$jumlah_total = $apbdData['jumlah_total'] ?? 0;
$apbd_id = $apbdData['id'] ?? 0;

// Ambil total rincian
$rincianQuery = mysqli_query($conn, "SELECT kategori, jumlah FROM apbd_rincian WHERE apbd_id = '$apbd_id'");
$rincianData = [];
$total_rincian = 0;
while ($row = mysqli_fetch_assoc($rincianQuery)) {
    $rincianData[] = $row;
    $total_rincian += $row['jumlah'];
}

// Hitung pengeluaran
$pengeluaran = $total_rincian;




?>
 <!doctype html>
 <html lang="en">

 <head>
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1">

     <meta name="description" content="">
     <meta name="author" content="">

     <title>Website Pengaduan Masyarakat Desa Purwajaya</title>

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
     <link rel="manifest" href="/manifest.json">
     <link rel="icon" href="/images/icon-192.png" type="image/png">
     <link rel="apple-touch-icon" href="/images/icon-192.png">




 </head>

 <body>

     <main>

         <nav class="navbar navbar-expand-lg fixed-top">

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
                             <a class="nav-link active" href="index.html">Home</a>
                         </li>

                         <li class="nav-item">
                             <a class="nav-link" href="about.php">About</a>
                         </li>

                         <li class="nav-item">
                             <a class="nav-link" href="contact.html">Contact</a>
                         </li>
                     </ul>

                     <div class="ms-4">
                         <a href="login.html" class="btn custom-btn custom-border-btn smoothscroll">Login</a>
                     </div>
                 </div>
             </div>
         </nav>

         <section class="hero-section">
             <div class="container">
                 <div class="row">
                     <div class="col-lg-12 col-12">
                         <div class="text-center mb-5 pb-2">
                             <h1 class="text-white">Selamat Datang</h1>
                             <p class="text-white"> Suara Anda sangat berarti. Laporkan permasalahan yang Anda temui
                                 agar segera ditindaklanjuti! </p>
                             <a href="login.html" class="btn custom-btn smoothscroll mt-3">Lakukan Aduan</a>
                         </div>

                         <?php
                $koneksi = new mysqli("localhost", "u637089379_lapordesa", "Lapordesa123" , "u637089379_lapordesa");
                if ($koneksi->connect_error) {
                    die("Koneksi gagal: " . $koneksi->connect_error);
                }

                $query = "SELECT * FROM staf_desa";
                $result = $koneksi->query($query);
                // DEBUG
if ($result->num_rows === 0) {
    echo "<p style='color:black;'>Tidak ada data staf_desa!</p>";
}
                ?>

                         <div class="owl-carousel owl-theme">
                             <?php while($row = $result->fetch_assoc()): 
                        $fotoPath = str_replace('../', '', $row['foto']); // buang ../ dari path
                    ?>
                             <div class="owl-carousel-info-wrap item">
                                 <img src="uploads/<?php echo basename($row['foto']); ?>"
                                     class="owl-carousel-image img-fluid" alt="">

                                 <!-- Info tidak dalam posisi absolute -->
                                 <div class="owl-carousel-info">
                                     <h4 class="mb-2">
                                         <?php echo htmlspecialchars($row['nama']); ?>
                                         <img src="images/verified.png" class="owl-carousel-verified-image" alt="">
                                     </h4>
                                     <span class="badge"><?php echo htmlspecialchars($row['jabatan']); ?></span>
                                 </div>

                                 <div class="social-share">
                                     <ul class="social-icon">
                                         <li class="social-icon-item">
                                             <a href="#" class="social-icon-link bi-instagram"></a>
                                         </li>
                                         <li class="social-icon-item">
                                             <a href="#" class="social-icon-link bi-youtube"></a>
                                         </li>
                                     </ul>
                                 </div>
                             </div>

                             <?php endwhile; ?>
                         </div>

                     </div>

                 </div>
             </div>
         </section>


         <section class="latest-podcast-section section-padding pb-0" id="section_2">
             <div class="container">
                 <div class="row justify-content-center">

                     <div class="col-lg-12 col-12">
                         <div class="section-title-wrap mb-5">
                             <h4 class="section-title">Jumlah Pengaduan & APBD</h4>
                         </div>
                     </div>

                     <!-- BOX GRAFIK -->
                     <div class="col-lg-6 col-12 mb-4 mb-lg-0">
                         <div class="custom-block d-flex h-100">
                             <div class="p-4 rounded shadow-lg border flex-grow-1 w-100 d-flex align-items-center justify-content-center"
                                 style="height: 250px;">
                                 <div class="chart-container" style="position: relative; height:100%; width:100%;">
                                     <canvas id="pengaduanChart"></canvas>
                                 </div>
                             </div>
                         </div>
                     </div>

                     <!-- BOX DATA APBD -->
                     <div class="col-lg-6 col-12 mb-4 mb-lg-0">
                         <div class="custom-block d-flex h-100">
                             <div class="p-4 rounded shadow-lg border flex-grow-1 w-100"
                                 style="height: 250px; overflow-y: auto; background-color: #ffffff; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                                 <!-- Form Pilih Tahun -->
                                 <div style="display: flex; justify-content: flex-end; margin-bottom: 10px;">
                                     <form method="GET">
                                         <select name="tahun" id="tahun" onchange="this.form.submit()"
                                             style="padding: 5px 10px; color: black; border-radius: 5px; width: 200px;">
                                             <option disabled selected hidden>Pilih tahun</option>
                                             <?php while ($row = mysqli_fetch_assoc($tahunQuery)): ?>
                                             <option value="<?= $row['tahun_anggaran'] ?>"
                                                 <?= $tahun == $row['tahun_anggaran'] ? 'selected' : '' ?>>
                                                 <?= $row['tahun_anggaran'] ?>
                                             </option>
                                             <?php endwhile; ?>
                                         </select>

                                     </form>
                                 </div>

                                 <!-- Jumlah APBD dan Pengeluaran -->
                                 <div
                                     style="display: flex; justify-content: space-between; gap: 10px; margin-bottom: 10px;">

                                     <!-- Jumlah APBD -->
                                     <div style="
        background-color: white; 
        padding: 10px 10px 10px 15px; 
        flex: 1; 
        border-radius: 4px; 
        box-shadow: 0 2px 4px rgba(0,0,0,0.1); 
        text-align: center; 
        position: relative;
    ">
                                         <!-- Strip Kiri -->
                                         <div
                                             style="position: absolute; left: 0; top: 0; bottom: 0; width: 6px; background-color: #337cf6; border-top-left-radius: 4px; border-bottom-left-radius: 4px;">
                                         </div>
                                         <strong style="color: #555;">
                                             Jumlah APBD <i class="bi bi-arrow-down-up"></i>
                                         </strong><br>
                                         Rp.<?= number_format($jumlah_total, 2, ',', '.') ?>
                                     </div>

                                     <!-- Pengeluaran -->
                                     <div style="
        background-color: white; 
        padding: 10px 10px 10px 15px; 
        flex: 1; 
        border-radius: 4px; 
        box-shadow: 0 2px 4px rgba(0,0,0,0.1); 
        text-align: center; 
        position: relative;
    ">
                                         <!-- Strip Kiri -->
                                         <div
                                             style="position: absolute; left: 0; top: 0; bottom: 0; width: 6px; background-color: #28a745; border-top-left-radius: 4px; border-bottom-left-radius: 4px;">
                                         </div>
                                         <strong style="color: #555;">
                                             Pengeluaran <i class="bi bi-arrow-down-up"></i>
                                         </strong><br>
                                         Rp.<?= number_format($pengeluaran, 2, ',', '.') ?>
                                     </div>

                                 </div>



                                 <!-- Tabel Rincian -->
                                 <div
                                     style="background-color: white; padding: 10px; border-radius: 4px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                                     <strong>Rincian</strong>
                                     <table style="width: 100%; margin-top: 10px; border-collapse: collapse;">
                                         <tbody>
                                             <?php if (count($rincianData) > 0): ?>
                                             <?php foreach ($rincianData as $item): ?>
                                             <tr>
                                                 <td><?= htmlspecialchars($item['kategori']) ?></td>
                                                 <td>Rp.<?= number_format($item['jumlah'], 2, ',', '.') ?></td>
                                             </tr>
                                             <?php endforeach; ?>
                                             <?php else: ?>
                                             <tr>
                                                 <td colspan="2">Tidak ada rincian data untuk tahun ini.</td>
                                             </tr>
                                             <?php endif; ?>
                                         </tbody>
                                     </table>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
         </section>



         <section class="topics-section section-padding pb-0" id="section_3">
             <div class="container">
                 <div class="row">

                     <div class="col-lg-12 col-12">
                         <div class="section-title-wrap mb-5">
                             <h4 class="section-title">Dokumentasi Kegiatan</h4>
                         </div>
                     </div>

                     <?php
            // Pastikan koneksi sudah dilakukan sebelumnya
            $sql = "SELECT nama_kegiatan, foto, tanggal_kegiatan FROM kegiatan ORDER BY id DESC LIMIT 4";

            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
            ?>
                     <div class="col-lg-3 col-md-6 col-12 mb-4 mb-lg-0">
                         <div class="custom-block custom-block-overlay">
                             <a href="detail_laporan.php" class="custom-block-image-wrap">
                                 <img src="<?= htmlspecialchars($row['foto']) ?>" class="custom-block-image img-fluid"
                                     alt="<?= htmlspecialchars($row['nama_kegiatan']) ?>">
                             </a>
                             <div class="custom-block-info custom-block-overlay-info">
                                 <h5 class="mb-1">
                                     <a href="listing-page.html">
                                         <?= htmlspecialchars($row['nama_kegiatan']) ?>
                                     </a>
                                 </h5>
                                 <p class="badge mb-0"><?= date('d M Y', strtotime($row['tanggal_kegiatan'])) ?></p>

                             </div>
                         </div>
                     </div>
                     <?php
                }
            } else {
                echo "<div class='col-12'><p>Tidak ada data kegiatan.</p></div>";
            }
            ?>

                 </div>
             </div>
         </section>



         <section id="detail-laporan" class="trending-podcast-section section-padding">
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
        WHERE laporan.status = 'diterima'
        ORDER BY laporan.id DESC 
        LIMIT 3";



            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                   $imgPath = !empty($row['foto']) ? 'uploads/' . $row['foto'] : 'images/profile/man-potrait.jpg';

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
                         <a href="#">desapurwajaya@gmail.com</a>
                     </p>
                 </div>

                 <div class="col-lg-3 col-md-6 col-12">


                     <h6 class="site-footer-title mb-3">Social Media</h6>

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
                     <a class="navbar-brand" href="index.html">
                         <img src="images/garut.png" class="logo-image img-fluid" alt="templatemo pod talk"
                             style="max-height: 50px; width: auto;">Desa Purwajaya
                     </a>
                 </div>

                 <div class="col-lg-7 col-md-9 col-12">
                     <ul class="site-footer-links">

                 </div>

                 <div class="col-lg-3 col-12">
                     <p class="copyright-text mb-0">Copyright © 2025
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
     <!-- Tambahkan Chart.js (CDN) di bagian <head> atau sebelum </body> -->
     <script src="js/chart.umd.js"></script>
     <script>
     fetch('grafik_pengaduan.php')
         .then(response => response.json())
         .then(chartData => {
             const ctx = document.getElementById('pengaduanChart').getContext('2d');
             new Chart(ctx, {
                 type: 'line',
                 data: {
                     labels: chartData.labels,
                     datasets: [{
                         label: 'Jumlah Pengaduan',
                         data: chartData.data,
                         borderColor: '#36A2EB',
                         backgroundColor: 'rgba(54, 162, 235, 0.2)',
                         pointBackgroundColor: '#fff',
                         pointBorderColor: '#36A2EB',
                         pointHoverBackgroundColor: '#36A2EB',
                         pointHoverBorderColor: '#fff',
                         borderWidth: 3,
                         pointRadius: 6,
                         pointHoverRadius: 8,
                         tension: 0.4,
                         fill: true,
                     }]
                 },
                 options: {
                     responsive: true,
                     maintainAspectRatio: false, // <-- ini penting!
                     plugins: {
                         legend: {
                             display: true,
                             labels: {
                                 color: '#000',
                                 font: {
                                     weight: 'bold'
                                 }
                             }
                         },
                         tooltip: {
                             enabled: true,
                             backgroundColor: '#36A2EB',
                             titleColor: '#fff',
                             bodyColor: '#fff'
                         }
                     },
                     scales: {
                         y: {
                             beginAtZero: true,
                             title: {
                                 display: true,
                                 text: 'Jumlah Pengaduan',
                                 color: '#000',
                                 font: {
                                     size: 14,
                                     weight: 'bold'
                                 }
                             },
                             ticks: {
                                 color: '#000'
                             },
                             grid: {
                                 color: '#e0e0e0'
                             }
                         },
                         x: {
                             title: {
                                 display: true,
                                 text: 'Bulan',
                                 color: '#000',
                                 font: {
                                     size: 14,
                                     weight: 'bold'
                                 }
                             },
                             ticks: {
                                 color: '#000'
                             },
                             grid: {
                                 color: '#f0f0f0'
                             }
                         }
                     }
                 }

             });
         });

     if ('serviceWorker' in navigator) {
         navigator.serviceWorker.register('/sekdes/service-worker.js')
             .then(() => console.log("Service Worker Registered"))
             .catch((error) => console.error("SW registration failed:", error));
     }
     </script>

     <script>
     // Reset saat login ulang (misalnya di halaman login.php)
     localStorage.setItem('loginBaru', 'true');
     sessionStorage.removeItem('baruKirimOffline');
     sessionStorage.removeItem('pengaduanLoginAktif');
     </script>



 </body>

 </html>