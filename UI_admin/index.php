<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Dashboard Persewaan Lapangan</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />

  <!-- Custom CSS -->
  <link rel="stylesheet" href="assets/css/style.css" />

  <style>
    /* Efek gerak kartu saat mouse hover */
    .card {
      transition: transform 0.2s ease;
      transform-style: preserve-3d;
      will-change: transform;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <div class="d-flex" style="min-height: 100vh;">
    <!-- Sidebar -->
    <nav class="bg-dark text-white p-3 flex-shrink-0" style="width: 250px;">
      <div class="d-flex align-items-center mb-4">
        <!-- Logo besar -->
        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a7/React-icon.svg/1024px-React-icon.svg.png" alt="Logo" style="width:40px; height:40px;" />
        <h4 class="mb-0 d-flex align-items-center">
          <!-- Logo kecil di kiri atas sebelah tulisan -->
          <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a7/React-icon.svg/1024px-React-icon.svg.png" alt="Logo kecil" style="width:20px; height:20px; margin-right:8px;" />
          Persewaan Lapangan
        </h4>
      </div>
      <ul class="nav nav-pills flex-column">
        <li class="nav-item">
          <a href="index.php" class="nav-link text-white active">Dashboard</a>
        </li>
        <li class="nav-item">
          <a href="pelanggan.php" class="nav-link text-white">Pelanggan</a>
        </li>
        <li class="nav-item">
          <a href="lapangan.php" class="nav-link text-white">Lapangan</a>
        </li>
        <li class="nav-item">
          <a href="penyewaan.php" class="nav-link text-white">Penyewaan</a>
        </li>
        <li class="nav-item">
          <a href="pembayaran.php" class="nav-link text-white">Pembayaran</a>
        </li>
      </ul>
    </nav>

    <!-- Main content -->
    <main class="flex-grow-1 p-4 bg-light">
      <!-- Header dengan icon kecil -->
      <h1 class="mb-4 d-flex align-items-center gap-2">
        <i class="bi bi-speedometer2" style="font-size: 2rem; color: #007bff;"></i>
        Dashboard
      </h1>

      <div class="row g-4">
        <div class="col-md-4">
          <a href="datapenyewaan.php" class="text-decoration-none text-dark">
            <div class="card shadow-sm bg-primary text-white">
              <div class="card-body d-flex align-items-center gap-3">
                <i class="bi bi-file-earmark-text-fill" style="font-size: 2rem;"></i>
                <div>
                  <h5 class="card-title">Total Data Penyewaan</h5>
                  <p class="display-6 mb-0">Lihat Data</p>
                </div>
              </div>
            </div>
          </a>
        </div>

        <div class="col-md-4">
          <a href="riwayatpembayaran.php" class="text-decoration-none text-dark">
            <div class="card shadow-sm bg-success text-white">
              <div class="card-body d-flex align-items-center gap-3">
                <i class="bi bi-cash-stack" style="font-size: 2rem;"></i>
                <div>
                  <h5 class="card-title">Riwayat Pembayaran</h5>
                  <p class="display-6 mb-0">Lihat Data</p>
                </div>
              </div>
            </div>
          </a>
        </div>

        <div class="col-md-4">
          <a href="view_lapangan_populer.php" class="text-decoration-none text-dark">
            <div class="card shadow-sm bg-warning text-dark" style="cursor: pointer;">
              <div class="card-body d-flex align-items-center gap-3">
                <i class="bi bi-star-fill" style="font-size: 2rem;"></i>
                <div>
                  <h5 class="card-title">Lapangan Terpopuler</h5>
                  <p id="lapanganPopuler" class="display-6 mb-0">Lihat Data</p>
                </div>
              </div>
            </div>
          </a>
        </div>

        <div class="col-md-4">
          <a href="view_pendapatan_harian.php" class="text-decoration-none text-dark">
            <div class="card shadow-sm bg-info text-white">
              <div class="card-body d-flex align-items-center gap-3">
                <i class="bi bi-calendar2-check-fill" style="font-size: 2rem;"></i>
                <div>
                  <h5 class="card-title">Total Pendapatan Per Hari</h5>
                  <p class="display-6 mb-0">Lihat Data</p>
                </div>
              </div>
            </div>
          </a>
        </div>

        <div class="col-md-4">
          <a href="view_statistik_pegawai.php" class="text-decoration-none text-dark">
            <div class="card shadow-sm bg-danger text-white" style="cursor: pointer;">
              <div class="card-body d-flex align-items-center gap-3">
                <i class="bi bi-people-fill" style="font-size: 2rem;"></i>
                <div>
                  <h5 class="card-title">Pegawai Aktif Transaksi</h5>
                  <p id="pegawaiAktifTransaksi" class="display-6 mb-0">Lihat Data</p>
                </div>
              </div>
            </div>
          </a>
        </div>
      </div>
      <footer align="center" class="mt-5 pt-4 border-top text-muted">
        &copy; 2025 Persewaan Lapangan. All rights reserved.
      </footer>
    </main>
  </div>

  <script>
    fetch("get_lapangan_populer.php")
      .then(response => response.text())
      .then(data => {
        document.getElementById("lapanganPopuler").textContent = data;
      });

    fetch("get_total_penyewaan.php")
      .then(response => response.text())
      .then(data => {
        document.getElementById("totalDataPenyewaan").textContent = data;
      });

    // Efek gerak kartu mengikuti kursor
    const cards = document.querySelectorAll('.card');

    cards.forEach(card => {
      card.addEventListener('mousemove', (e) => {
        const rect = card.getBoundingClientRect();
        const x = (e.clientX - rect.left) / rect.width;
        const y = (e.clientY - rect.top) / rect.height;

        const rotateMax = 10;

        const rotateX = (y - 0.5) * rotateMax * -1;
        const rotateY = (x - 0.5) * rotateMax;

        card.style.transform = rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale(1.05);
      });

      card.addEventListener('mouseleave', () => {
        card.style.transform = 'rotateX(0deg) rotateY(0deg) scale(1)';
      });
    });
  </script>

  <!-- Bootstrap JS (optional, for components like modals) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>