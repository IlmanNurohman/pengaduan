const CACHE_NAME = "pengaduan-cache-v4";

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

self.addEventListener("activate", (event) => {
  event.waitUntil(
    caches.keys().then((cacheNames) =>
      Promise.all(
        cacheNames.map((cacheName) => {
          if (cacheName !== CACHE_NAME) {
            console.log("[SW] Deleting old cache:", cacheName);
            return caches.delete(cacheName);
          }
        })
      )
    )
  );
  self.clients.claim();
});

// ✅ FETCH HANDLER: Stale-While-Revalidate untuk static files
self.addEventListener("fetch", (event) => {
  const requestURL = new URL(event.request.url);

  // Lewati permintaan dari CDN pihak ketiga
  if (
    requestURL.origin.includes("cdn.jsdelivr.net") ||
    requestURL.origin.includes("unpkg.com")
  ) {
    return;
  }

  const cleanPath = requestURL.pathname;

  // Jika PHP atau login → Network First
 if (cleanPath.endsWith(".php")) {
  event.respondWith(
    fetch(event.request)
      .then((networkResponse) => {
        // clone response SEKALI dan simpan
        const cloned = networkResponse.clone();
        caches.open(CACHE_NAME).then((cache) => {
          cache.put(cleanPath, cloned);
        });
        return networkResponse;
      })
      .catch(() => caches.match(cleanPath)) // fallback offline
  );
}

 else {
    // Untuk file statis → Stale While Revalidate
    event.respondWith(
      caches.match(event.request).then((cached) => {
        const fetchPromise = fetch(event.request)
          .then((networkResponse) => {
            caches.open(CACHE_NAME).then((cache) => {
              cache.put(event.request, networkResponse.clone());
            });
            return networkResponse;
          })
          .catch(() => {
            // Gagal fetch karena offline → abaikan
          });

        // Kembalikan cache dulu, update diam-diam
        return cached || fetchPromise;
      })
    );
  }
});

self.addEventListener("message", (event) => {
  if (event.data && event.data.type === "MANUAL_NOTIFICATION") {
    const { title, body, icon } = event.data;
    self.registration.showNotification(title || "Notifikasi", {
      body: body || "Isi notifikasi",
      icon: icon || "images/icon-192.png",
    });
  }
});
