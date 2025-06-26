document.addEventListener("DOMContentLoaded", function () {
    const tahunSelect = document.getElementById("tahunSelect");

    function generateColors(count) {
        const colors = [];
        for (let i = 0; i < count; i++) {
            const hue = Math.floor((360 / count) * i);
            colors.push(`hsl(${hue}, 70%, 60%)`);
        }
        return colors;
    }

    function loadChart(tahun) {
        console.log("📌 Tahun yang dipilih:", tahun); // Debug tahun

        fetch("get_apbd_data.php?tahun=" + tahun)
            .then(response => {
                if (!response.ok) {
                    throw new Error("HTTP error " + response.status);
                }
                return response.json();
            })
            .then(jsonData => {
                console.log("✅ Data dari server:", jsonData); // Debug isi JSON

                const canvas = document.getElementById("myPieChart");

                if (!canvas) {
                    console.error("❌ Elemen canvas tidak ditemukan!");
                    return;
                }

                const ctx = canvas.getContext("2d");
                console.log("🧠 Context Canvas ditemukan:", ctx); // Debug context

                // Destroy chart lama jika ada
                if (window.myPieChart instanceof Chart) {
                    console.log("🔁 Menghapus chart lama...");
                    window.myPieChart.destroy();
                }

                // Cek apakah ada data
                if (!jsonData.labels || jsonData.labels.length === 0) {
                    alert("⚠️ Tidak ada data untuk tahun " + tahun);
                    return;
                }

                console.log("📊 Membuat chart baru...");
                window.myPieChart = new Chart(ctx, {
                    type: "pie",
                    data: {
                        labels: jsonData.labels,
                        datasets: [{
                            data: jsonData.data,
                            backgroundColor: generateColors(jsonData.data.length),
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            })
            .catch(error => console.error("❌ Gagal memuat data:", error));
    }

    // Muat chart pertama kali
    console.log("🔄 Muat chart awal untuk tahun:", tahunSelect.value);
    loadChart(tahunSelect.value);

    // Update chart saat dropdown berubah
    tahunSelect.addEventListener("change", function () {
        console.log("📥 Tahun diubah oleh user:", this.value);
        loadChart(this.value);
    });
});
