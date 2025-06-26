const CACHE_NAME = "pengaduan-cache-v2"; // Naikkan versi setiap ada perubahan cache

const urlsToCache = [
  "index.php", 
  "about.php",
  "contact.html",
  "admin/detail_laporan.php",
  "login.html",
  "user.php",
  "admin/index.php",
    "admin/daftar_laporan.php",
    "admin/detail.php",
     "admin/tambah_staf.php",
     "admin/tambah_kegiatan.php",
     "admin/apbd.php",
     "admin/admin.php",
  "images/icon-192.png",
  "images/icon-512.png",
  "css/bootstrap.min.css",
  "css/bootstrap-icons.css",
  "js/bootstrap.bundle.min.js",
  "css/owl.carousel.min.css",
  "css/owl.theme.default.min.css",
  "css/templatemo-pod-talk.css",
   "js/index-min.js",
   "js/jquery.min.js",
   "js/chart.umd.js",
   "admin/assets/fontawesome/css/all.min.css",
   "admin/css/styles.css",
   "admin/css/datatables.css",
   "admin/css/bootstrap.min.css",
   "admin/css/datatables.min.css",
   "admin/js/datatables.min.js",
   "admin/js/datatables.js",
   "admin/js/chart.min.js",
   "admin/js/datatables-simple-demo.js",
   "admin/js/jquery.min.js",
   "admin/js/scripts.js",
   "admin/js/tanggapan-offline.js",
   "admin/js/bootstrap.bundle.min.js",
   "js/html2pdf.bundle.min.js",
   "admin/js/jspdf.umd.min.js",
   
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
      icon: icon || "images/icon-192.png"
    });
  }
});
