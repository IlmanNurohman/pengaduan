   <?php
                $koneksi = new mysqli("127.0.0.1", "u637089379_lapordesa", "u637089379_lapordesa", "Lapordesa123");
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
   <!doctype html>
   <html lang="en">

   <head>
       <meta charset="utf-8">
       <meta name="viewport" content="width=device-width, initial-scale=1">

       <meta name="description" content="">
       <meta name="author" content="">

       <title>Pod Talk - About Page</title>

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
       <style>
       .hover-effect {
           transition: transform 0.3s ease, box-shadow 0.3s ease;
       }

       .hover-effect:hover {
           transform: translateY(-5px);
           box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
       }

       .icon-circle {
           width: 50px;
           height: 50px;
           background-color: #0d6efd;
           /* warna biru Bootstrap */
           border-radius: 50%;
           display: flex;
           align-items: center;
           justify-content: center;
       }
       </style>
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
                               <a class="nav-link active" href="about.php">About</a>
                           </li>



                           <li class=" nav-item">
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

                           <h2 class="mb-0">About Me</h2>
                       </div>

                   </div>
               </div>
           </header>


           <section class="about-section section-padding" id="section_2">
               <div class="container">
                   <div class="row">

                       <div class="col-lg-8 col-12 mx-auto">
                           <div class="pb-5 mb-5">
                               <div class="section-title-wrap mb-4">
                                   <h4 class="section-title">About me</h4>
                               </div>

                               <p>Website Pengaduan Masyarakat ini dibuat sebagai sarana komunikasi dua arah antara
                                   masyarakat dan pihak berwenang dalam menyampaikan keluhan, saran, atau laporan
                                   terkait
                                   berbagai permasalahan yang terjadi di lingkungan sekitar. Melalui platform ini,
                                   masyarakat dapat dengan mudah mengisi formulir pengaduan, mengunggah bukti foto, dan
                                   memantau status dari laporan yang telah dikirimkan.

                               </p>

                               <p> Website ini dirancang untuk mempermudah proses penanganan pengaduan secara
                                   transparan,
                                   cepat, dan akuntabel. Dengan tampilan yang sederhana dan user-friendly, diharapkan
                                   seluruh lapisan masyarakat dapat menggunakan platform ini tanpa kesulitan.</p>

                               <img src="images/" class="about-image mt-5 img-fluid" alt="">
                           </div>
                       </div>
                       <div class="col-lg-12 col-12">
                           <div class="section-title-wrap mb-5">
                               <h4 class="section-title">Staf Desa</h4>
                           </div>
                       </div>



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
                       < <h6 class="site-footer-title mb-3">Social</h6>

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

                       </ul>
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

   </body>

   </html>