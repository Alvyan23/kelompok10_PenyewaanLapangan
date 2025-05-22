<?php
session_start();
require __DIR__ . '/../data/koneksi.php'; // pastikan file ini ada
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Booking Lapangan Futsal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <style>
    .card-img-top {
      height: 200px;
      object-fit: cover;
    }
    .navbar {
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
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
<!-- Konten -->
<div class="container my-5">
  <h2 class="text-center mb-4">LAPANGAN KAMI</h2>
  <div class="row g-4">

    <!-- Lapangan A -->
    <div class="col-md-4 d-flex">
      <div class="card h-100 w-100">
        <img src="../aset/lapanganA.jpg" class="card-img-top" alt="Lapangan A">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title">Lapangan A</h5>
          <a href="boking.php?lapangan=A" class="btn btn-dark mt-auto">Booking Sekarang!</a>
        </div>
      </div>
    </div>

    <!-- Lapangan B -->
    <div class="col-md-4 d-flex">
      <div class="card h-100 w-100">
        <img src="../aset/lapanganB.jpg" class="card-img-top" alt="Lapangan B">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title">Lapangan B</h5>
          <a href="boking.php?lapangan=B" class="btn btn-dark mt-auto">Booking Sekarang!</a>
        </div>
      </div>
    </div>

    <!-- Lapangan C -->
    <div class="col-md-4 d-flex">
      <div class="card h-100 w-100">
        <img src="../aset/lapanganC.jpg" class="card-img-top" alt="Lapangan C">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title">Lapangan C</h5>
          <a href="boking.php?lapangan=C" class="btn btn-dark mt-auto">Booking Sekarang!</a>
        </div>
      </div>
    </div>

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
