<?php
require '../data/koneksi.php';
session_start();

if (!isset($_SESSION['id_pelanggan'])) {
    $_SESSION['id_pelanggan'] = 7;
}
$id_pelanggan = $_SESSION['id_pelanggan'];

// Hapus pesanan jika dikirim dari JS
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hapus_id_sewa'])) {
    $id_sewa = $_POST['hapus_id_sewa'];
    $stmt_hapus = $conn->prepare("CALL hapus_satu_pesanan(?, ?)");
    $stmt_hapus->bind_param("ii", $id_sewa, $id_pelanggan);
    $stmt_hapus->execute();
    $stmt_hapus->close();
    echo json_encode(['success' => true]);
    exit;
}

// Ambil data keranjang
$query = "SELECT p.ID_Sewa, l.Nama_Lapangan, p.Tanggal, p.Jam_Mulai, p.Jam_Selesai, p.Total_Biaya 
          FROM penyewaan_lapangan p 
          JOIN lapangan l ON p.ID_Lapangan = l.ID_Lapangan 
          WHERE p.ID_Pelanggan = ? AND p.status = 'tersedia'
          ORDER BY p.Jam_Mulai ASC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_pelanggan);

$data_keranjang = [];
$total_booking = 0;
$total_harga = 0;

if ($stmt) {
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $data_keranjang[] = $row;
        $total_booking++;
        $total_harga += $row['Total_Biaya'];
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Keranjang Booking</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    .cart-card { border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    .cart-img { width: 100px; height: 80px; object-fit: cover; border-radius: 8px; }
    .cart-summary { border-top: 2px dashed #0d6efd; }
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
  <h2 class="fw-bold text-center mb-4">Keranjang Booking Anda</h2>

  <?php if (count($data_keranjang) > 0): ?>
    <?php foreach ($data_keranjang as $row): ?>
      <div class="card cart-card mb-3" id="item-<?= $row['ID_Sewa'] ?>">
        <div class="card-body d-flex align-items-center justify-content-between">
          <div class="d-flex align-items-center">
            <img src="default_lapangan.jpg" alt="<?= htmlspecialchars($row['Nama_Lapangan']) ?>" class="cart-img me-3">
            <div>
              <h5 class="mb-1"><?= htmlspecialchars($row['Nama_Lapangan']) ?></h5>
              <p class="mb-0 text-muted">
                Tanggal: <?= htmlspecialchars($row['Tanggal']) ?> |
                Jam: <?= htmlspecialchars($row['Jam_Mulai']) ?> - <?= htmlspecialchars($row['Jam_Selesai']) ?>
              </p>
            </div>
          </div>
          <div class="text-end">
            <h6 class="mb-2">Rp<?= number_format($row['Total_Biaya'], 0, ',', '.') ?></h6>
            <button class="btn btn-sm btn-outline-danger" onclick="hapusItem(<?= $row['ID_Sewa'] ?>)">Hapus</button>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <div class="alert alert-info text-center">Tidak ada booking dalam keranjang.</div>
  <?php endif; ?>

  <div class="card cart-card mt-4">
    <div class="card-body">
      <h5 class="mb-3">Ringkasan Pembayaran</h5>
      <div class="d-flex justify-content-between mb-2">
        <span>Total Booking</span>
        <span><?= $total_booking ?></span>
      </div>
      <div class="d-flex justify-content-between mb-2">
        <span>Total Harga</span>
        <span>Rp<?= number_format($total_harga, 0, ',', '.') ?></span>
      </div>
      <div class="cart-summary my-3"></div>
      <div class="d-grid">
        <a href="checkout.php" class="btn btn-primary btn-lg text-white">Lanjut ke Pembayaran</a>
      </div>
    </div>
  </div>
</div>

<script>
function hapusItem(id) {
  Swal.fire({
    title: 'Yakin hapus?',
    text: "Item ini akan dihapus dari keranjang!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Ya, hapus!'
  }).then((result) => {
    if (result.isConfirmed) {
      fetch('', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `hapus_id_sewa=${id}`
      }).then(response => response.json())
        .then(data => {
          if (data.success) {
            document.getElementById('item-' + id).remove();
            Swal.fire('Dihapus!', 'Item berhasil dihapus.', 'success')
              .then(() => location.reload());
          }
        });
    }
  })
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
