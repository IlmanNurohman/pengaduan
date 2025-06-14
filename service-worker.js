const CACHE_NAME = "pengaduan-cache-v2"; // Naikkan versi setiap ada perubahan cache

const urlsToCache = [
  "/sekdes/index.php", // statis masih bisa
  "/sekdes/about.php",
  "/sekdes/login.html",
  "/sekdes/user.php",
  "/sekdes/logout.php",
  "/sekdes/admin/index.php",
    "/sekdes/admin/daftar_laporan.php",
    "/sekdes/admin/detail.php",
     "/sekdes/admin/tambah_staf.php",
     "/sekdes/admin/tambah_kegiatan.php",
     "/sekdes/admin/apbd.php",
     "/sekdes/admin/admin.php",
  "/sekdes/images/icon-192.png",
  "/sekdes/images/icon-512.png",
  "/sekdes/css/bootstrap.min.css",
  "/sekdes/css/bootstrap-icons.css",
  "/sekdes/js/bootstrap.bundle.min.js",
  "/sekdes/css/owl.carousel.min.css",
  "/sekdes/css/owl.theme.default.min.css",
  "/sekdes/css/templatemo-pod-talk.css",
   "/sekdes/js/index-min.js",
   "/sekdes/js/jquery.min.js",
   "/sekdes/js/chart.umd.js",
   "/sekdes/admin/assets/fontawesome/css/all.min.css",
   "/sekdes/admin/css/styles.css",
   "/sekdes/admin/css/datatables.css",
   "/sekdes/admin/css/bootstrap.min.css",
   "/sekdes/admin/css/datatables.min.css",
   "/sekdes/admin/js/datatables.min.js",
   "/sekdes/admin/js/datatables.js",
   "/sekdes/admin/js/chart.min.js",
   "/sekdes/admin/js/datatables-simple-demo.js",
   "/sekdes/admin/js/jquery.min.js",
   "/sekdes/admin/js/scripts.js",
   "/sekdes/admin/js/tanggapan-offline.js",
   "/sekdes/admin/js/bootstrap.bundle.min.js",
   "/sekdes/js/html2pdf.bundle.min.js",
   
  "https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js",
  
];

// Cache file statis saat install
self.addEventListener("install", (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then(async (cache) => {
      for (const url of urlsToCache) {
        try {
          await cache.add(url);
          console.log(`[SW] Cached: ${url}`);
        } catch (error) {
          console.error(`[SW] Failed to cache ${url}:`, error);
        }
      }
    })
  );
  self.skipWaiting();
});

// Hapus cache lama saat activate
self.addEventListener("activate", (event) => {
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.map((cacheName) => {
          if (cacheName !== CACHE_NAME) {
            console.log("[SW] Deleting old cache:", cacheName);
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
  self.clients.claim();
});

// Tangani semua request
self.addEventListener("fetch", (event) => {
  const requestURL = new URL(event.request.url);

  // Lewatkan request ke CDN eksternal (biarkan browser handle)
  if (requestURL.origin.includes("cdn.jsdelivr.net") || requestURL.origin.includes("unpkg.com")) {
    return;
  }

  // Jika file PHP atau login.html => network first
  if (
    requestURL.pathname.endsWith(".php") ||
    requestURL.pathname.endsWith("login.html")
  ) {
    event.respondWith(
      fetch(event.request)
        .then((response) => response)
        .catch(() => caches.match(event.request))
    );
  } else {
    // Untuk file statis: cache first
    event.respondWith(
      caches.match(event.request).then((cached) => cached || fetch(event.request))
    );
  }
});


// Notifikasi dari halaman
self.addEventListener("message", (event) => {
  console.log("[SW] Message received:", event.data);
  if (event.data && event.data.type === "MANUAL_NOTIFICATION") {
    const { title, body, icon } = event.data;
    self.registration.showNotification(title || "Notifikasi", {
      body: body || "Isi notifikasi",
      icon: icon || "/sekdes/images/icon-192.png"
    });
  }
});
