<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Event Futsal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <style>
    .event-card {
      transition: transform 0.3s, box-shadow 0.3s;
    }
    .event-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 20px rgba(0,0,0,0.2);
    }
    .event-img {
      height: 200px;
      object-fit: cover;
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

<!-- Header -->
<div class="container text-center my-5">
  <h2 class="fw-bold">EVENT FUTSAL TERBARU</h2>
  <p class="text-muted">Ikuti event menarik yang kami adakan di lapangan kami!</p>
</div>

<!-- Event Grid -->
<div class="container">
  <div class="row g-4">

    <!-- Event 1 -->
    <div class="col-md-4">
      <div class="card event-card">
        <img src="../aset/event1.jpg" class="card-img-top event-img" alt="Event 1">
        <div class="card-body">
          <h5 class="card-title">Turnamen Futsal Antar Kampus</h5>
          <p class="card-text text-muted">Tanggal: 25 Juni 2025</p>
          <p class="card-text">Ayo daftar dan bawa pulang hadiah total jutaan rupiah!</p>
          <a href="#" class="btn btn-primary">Lihat Detail</a>
        </div>
      </div>
    </div>

    <!-- Event 2 -->
    <div class="col-md-4">
      <div class="card event-card">
        <img src="../aset/event2.jpg" class="card-img-top event-img" alt="Event 2">
        <div class="card-body">
          <h5 class="card-title">Fun Match Komunitas</h5>
          <p class="card-text text-muted">Tanggal: 30 Juni 2025</p>
          <p class="card-text">Event santai untuk semua komunitas futsal, gratis pendaftaran!</p>
          <a href="#" class="btn btn-primary">Lihat Detail</a>
        </div>
      </div>
    </div>

    <!-- Event 3 -->
    <div class="col-md-4">
      <div class="card event-card">
        <img src="../aset/event3.jpg" class="card-img-top event-img" alt="Event 3">
        <div class="card-body">
          <h5 class="card-title">Liga Malam Minggu</h5>
          <p class="card-text text-muted">Tanggal: 6 Juli 2025</p>
          <p class="card-text">Pertandingan liga mini setiap Sabtu malam, terbuka untuk umum.</p>
          <a href="#" class="btn btn-primary">Lihat Detail</a>
        </div>
      </div>
    </div>

  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
