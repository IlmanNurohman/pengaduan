document.addEventListener("DOMContentLoaded", function () {
  const tahun = 2025; // Ganti sesuai tahun yang kamu inginkan
  fetch("get_apbd_data.php?tahun=" + tahun)
    .then((response) => response.json())
    .then((jsonData) => {
      const ctx = document.getElementById("myPieChart").getContext("2d");

      // Hapus chart sebelumnya jika ada
      if (typeof window.myPieChart !== 'undefined' && window.myPieChart instanceof Chart) {
        window.myPieChart.destroy();
      }

      // Buat chart baru
      window.myPieChart = new Chart(ctx, {
        type: "pie",
        data: {
          labels: jsonData.labels,
          datasets: [{
            data: jsonData.data,
            backgroundColor: [
              "#007bff", "#dc3545", "#ffc107",
              "#28a745", "#6f42c1", "#20c997", "#fd7e14"
            ],
          }],
        },
        options: {
          responsive: true,
          maintainAspectRatio: true,  // Menjaga proporsi lingkaran
          legend: {
            position: 'bottom'
          }
        }
      });
    })
    .catch((error) => console.error("Gagal memuat data:", error));
});
