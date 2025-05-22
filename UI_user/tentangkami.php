<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Tentang Kami</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f8f9fa;
    }
    .hero {
      background: linear-gradient(to right, #0d6efd, #1e90ff);
      color: white;
      padding: 100px 20px;
      text-align: center;
    }
    .hero h1 {
      font-size: 3rem;
      font-weight: 700;
      margin-bottom: 15px;
    }
    .hero p {
      font-size: 1.2rem;
      max-width: 600px;
      margin: auto;
    }
    .section-title {
      font-weight: 700;
      font-size: 2rem;
      margin-bottom: 30px;
      color: #0d6efd;
    }
    .icon-box i {
      font-size: 2rem;
      color: #0d6efd;
    }
    .highlight-box {
      background-color: white;
      border-left: 5px solid #0d6efd;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">LOGO ANDA</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link active" href="home.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="event.php">Event</a></li>
        <li class="nav-item"><a class="nav-link" href="foto.php">Foto</a></li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Profil</a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">Tentang Kami</a></li>
            <li><a class="dropdown-item" href="#">Kontak</a></li>
          </ul>
        </li>
        <li class="nav-item"><a class="nav-link" href="keranjang.php">Keranjang</a></li>
      </ul>

      <!-- Dropdown User -->
      <ul class="navbar-nav">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle text-white" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
            Hi, <?= isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Pengguna'; ?>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- Hero Section -->
<section class="hero">
  <div class="container">
    <h1>Selamat Datang di Arena Futsal</h1>
    <p>Kami memberikan fasilitas futsal terbaik dengan pelayanan profesional dan harga terjangkau.</p>
  </div>
</section>

<!-- Tentang Kami -->
<section class="py-5">
  <div class="container">
    <h2 class="section-title text-center">Tentang Kami</h2>
    <div class="row align-items-center">
      <div class="col-md-6 mb-4">
        <img src="aset/tim-futsal.jpg" alt="Tentang Kami" class="img-fluid rounded shadow">
      </div>
      <div class="col-md-6">
        <div class="highlight-box">
          <p><strong>Arena Futsal</strong> berdiri sejak tahun 2020 dengan misi memberikan fasilitas olahraga berkualitas tinggi di lingkungan yang ramah dan bersih. Kami melayani semua kalangan — dari pelajar, komunitas, hingga perusahaan.</p>
          <ul class="list-unstyled mt-3">
            <li class="mb-2"><i class="bi bi-check2-circle text-success me-2"></i> Fasilitas lapangan berstandar</li>
            <li class="mb-2"><i class="bi bi-check2-circle text-success me-2"></i> Layanan booking online cepat</li>
            <li class="mb-2"><i class="bi bi-check2-circle text-success me-2"></i> Staff profesional & ramah</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Visi & Misi -->
<section class="bg-light py-5">
  <div class="container">
    <h2 class="section-title text-center">Visi & Misi</h2>
    <div class="row text-center">
      <div class="col-md-6 mb-4">
        <div class="p-4 bg-white rounded shadow icon-box h-100">
          <i class="bi bi-eye-fill mb-3"></i>
          <h5>Visi</h5>
          <p>Menjadi tempat futsal pilihan utama di kota ini dengan pelayanan terbaik.</p>
        </div>
      </div>
      <div class="col-md-6 mb-4">
        <div class="p-4 bg-white rounded shadow icon-box h-100">
          <i class="bi bi-bullseye mb-3"></i>
          <h5>Misi</h5>
          <p>Menyediakan fasilitas olahraga berkualitas tinggi dan meningkatkan gaya hidup sehat masyarakat.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Footer -->
<footer class="bg-dark text-white text-center py-3">
  <div class="container">
    &copy; 2025 Arena Futsal. All rights reserved.
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
