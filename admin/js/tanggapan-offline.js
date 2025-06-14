// Buat/akses IndexedDB
let db;
const request = indexedDB.open("TanggapanDB", 1);

request.onupgradeneeded = function(event) {
    db = event.target.result;
    const objectStore = db.createObjectStore("tanggapan", { keyPath: "id", autoIncrement: true });
};

request.onsuccess = function(event) {
    db = event.target.result;
    checkAndSendPendingData(); // Cek data tertunda saat dibuka
};

request.onerror = function(event) {
    console.error("Database error:", event.target.errorCode);
};

// Simpan ke IndexedDB jika offline
function saveTanggapanOffline(data) {
    const transaction = db.transaction(["tanggapan"], "readwrite");
    const store = transaction.objectStore("tanggapan");
    store.add(data);
}

// Kirim semua data tertunda ke server
function checkAndSendPendingData() {
    const transaction = db.transaction(["tanggapan"], "readonly");
    const store = transaction.objectStore("tanggapan");
    const getAllRequest = store.getAll();

    getAllRequest.onsuccess = function() {
        const dataList = getAllRequest.result;
        dataList.forEach(item => {
            $.ajax({
                url: "proses_laporan.php",
                type: "POST",
                data: item,
                dataType: "json",
                success: function(response) {
                    if (response.status === "success") {
                        // Hapus data dari IndexedDB jika berhasil dikirim
                        const deleteTx = db.transaction(["tanggapan"], "readwrite");
                        const deleteStore = deleteTx.objectStore("tanggapan");
                        deleteStore.delete(item.id);
                        console.log("Data terkirim dan dihapus:", item);
                    } else {
                        console.warn("Gagal kirim data:", response.message);
                    }
                },
                error: function() {
                    console.error("Gagal terhubung ke server.");
                }
            });
        });
    };
}

// Listener saat kembali online
window.addEventListener("online", checkAndSendPendingData);

// Intersep submit tanggapan
$(document).on('submit', '#form-tanggapan', function(e) {
    e.preventDefault();
    const clickedButton = $(this).find("button[type=submit]:focus").attr("name");
    const formData = {
        id: $(this).find("input[name=id]").val(),
        tanggapan: $(this).find("textarea[name=tanggapan]").val(),
    };
    formData[clickedButton] = 1; // 'terima' atau 'tolak'

    if (navigator.onLine) {
        // Kirim langsung ke server
        $.ajax({
            url: "proses_laporan.php",
            type: "POST",
            data: formData,
            dataType: "json",
            success: function(response) {
                if (response.status === 'success') {
                    alert("Tanggapan berhasil dikirim.");
                    window.location.reload();
                } else {
                    alert("Gagal: " + response.message);
                }
            },
            error: function() {
                alert("Koneksi ke server gagal.");
            }
        });
    } else {
        // Simpan offline
        saveTanggapanOffline(formData);
        alert("Tidak ada koneksi. Tanggapan disimpan dan akan dikirim saat online.");
    }
});
