<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Galeri Lapangan Futsal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .gallery-img {
      width: 100%;
      height: 220px;
      object-fit: cover;
      border-radius: 12px;
      cursor: pointer;
      transition: transform 0.3s, box-shadow 0.3s;
    }
    .gallery-img:hover {
      transform: scale(1.03);
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    }
    .section-title {
      font-weight: bold;
      font-size: 1.5rem;
      margin-top: 40px;
      margin-bottom: 20px;
      border-left: 5px solid #0d6efd;
      padding-left: 15px;
    }
  </style>
</head>
<body>
<!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#">
                <i class="fas fa-futbol me-2"></i>LAPANGAN SPORT
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="home.php">
                            <i class="fas fa-home me-1"></i>Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="event.php">
                            <i class="fas fa-calendar me-1"></i>Event
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="foto.php">
                            <i class="fas fa-camera me-1"></i>Foto
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-info-circle me-1"></i>Profil
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-users me-2"></i>Tentang Kami</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-phone me-2"></i>Kontak</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="keranjang.php">
                            <i class="fas fa-shopping-cart me-1"></i>Keranjang
                        </a>
                    </li>
                </ul>

                <!-- Dropdown User -->
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i>
                            Hi, <?= isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Pengguna'; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item active" href="edit_akun.php">
                                <i class="fas fa-edit me-2"></i>Edit Akun
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="../login.php">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
<div class="container my-5">
  <h2 class="text-center fw-bold mb-4">GALERI LAPANGAN FUTSAL</h2>
  <p class="text-center text-muted mb-5">Dokumentasi fasilitas dan suasana lapangan A, B, dan C</p>

  <!-- Lapangan A -->
  <div class="section-title">Lapangan A</div>
  <div class="row g-4">
    <div class="col-md-4">
      <img src="lapangan_a1.jpg" class="gallery-img" alt="Lapangan A - 1" data-bs-toggle="modal" data-bs-target="#modalA1">
    </div>
    <div class="col-md-4">
      <img src="lapangan_a2.jpg" class="gallery-img" alt="Lapangan A - 2" data-bs-toggle="modal" data-bs-target="#modalA2">
    </div>
    <div class="col-md-4">
      <img src="lapangan_a3.jpg" class="gallery-img" alt="Lapangan A - 3" data-bs-toggle="modal" data-bs-target="#modalA3">
    </div>
  </div>

  <!-- Lapangan B -->
  <div class="section-title">Lapangan B</div>
  <div class="row g-4">
    <div class="col-md-4">
      <img src="lapangan_b1.jpg" class="gallery-img" alt="Lapangan B - 1" data-bs-toggle="modal" data-bs-target="#modalB1">
    </div>
    <div class="col-md-4">
      <img src="lapangan_b2.jpg" class="gallery-img" alt="Lapangan B - 2" data-bs-toggle="modal" data-bs-target="#modalB2">
    </div>
    <div class="col-md-4">
      <img src="lapangan_b3.jpg" class="gallery-img" alt="Lapangan B - 3" data-bs-toggle="modal" data-bs-target="#modalB3">
    </div>
  </div>

  <!-- Lapangan C -->
  <div class="section-title">Lapangan C</div>
  <div class="row g-4">
    <div class="col-md-4">
      <img src="lapangan_c1.jpg" class="gallery-img" alt="Lapangan C - 1" data-bs-toggle="modal" data-bs-target="#modalC1">
    </div>
    <div class="col-md-4">
      <img src="lapangan_c2.jpg" class="gallery-img" alt="Lapangan C - 2" data-bs-toggle="modal" data-bs-target="#modalC2">
    </div>
    <div class="col-md-4">
      <img src="lapangan_c3.jpg" class="gallery-img" alt="Lapangan C - 3" data-bs-toggle="modal" data-bs-target="#modalC3">
    </div>
  </div>
</div>

<!-- Modal Template -->
<!-- Ulangi modal untuk setiap gambar -->
<div class="modal fade" id="modalA1" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content bg-transparent border-0">
      <img src="lapangan_a1.jpg" class="img-fluid rounded">
    </div>
  </div>
</div>

<div class="modal fade" id="modalA2" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content bg-transparent border-0">
      <img src="lapangan_a2.jpg" class="img-fluid rounded">
    </div>
  </div>
</div>

<div class="modal fade" id="modalA3" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content bg-transparent border-0">
      <img src="lapangan_a3.jpg" class="img-fluid rounded">
    </div>
  </div>
</div>

<!-- Tambahkan modal lainnya untuk B dan C -->
<div class="modal fade" id="modalB1" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content bg-transparent border-0">
      <img src="lapangan_b1.jpg" class="img-fluid rounded">
    </div>
  </div>
</div>

<div class="modal fade" id="modalB2" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content bg-transparent border-0">
      <img src="lapangan_b2.jpg" class="img-fluid rounded">
    </div>
  </div>
</div>

<div class="modal fade" id="modalB3" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content bg-transparent border-0">
      <img src="lapangan_b3.jpg" class="img-fluid rounded">
    </div>
  </div>
</div>

<div class="modal fade" id="modalC1" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content bg-transparent border-0">
      <img src="lapangan_c1.jpg" class="img-fluid rounded">
    </div>
  </div>
</div>

<div class="modal fade" id="modalC2" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content bg-transparent border-0">
      <img src="lapangan_c2.jpg" class="img-fluid rounded">
    </div>
  </div>
</div>

<div class="modal fade" id="modalC3" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content bg-transparent border-0">
      <img src="lapangan_c3.jpg" class="img-fluid rounded">
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
