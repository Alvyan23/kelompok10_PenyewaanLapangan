<?php
require '../data/koneksi.php';
session_start();

if (!isset($_SESSION['id_pelanggan'])) {
    header('Location: keranjang.php');
    exit;
}

$id_pelanggan = $_SESSION['id_pelanggan'];
$pesanan = [];
$total_harga = 0;
$pesan_sukses = '';
$pesan_error = '';

// Ambil data pesanan (status 'tersedia')
$query = "SELECT l.Nama_Lapangan, p.Tanggal, p.Jam_Mulai, p.Jam_Selesai, p.Total_Biaya 
          FROM penyewaan_lapangan p 
          JOIN lapangan l ON p.ID_Lapangan = l.ID_Lapangan 
          WHERE p.ID_Pelanggan = ? AND p.STATUS = 'tersedia'
          ORDER BY p.Tanggal, p.Jam_Mulai";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_pelanggan);

if ($stmt) {
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $pesanan[] = $row;
        $total_harga += $row['Total_Biaya'];
    }
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $metode = trim($_POST['metode'] ?? '');

    if (empty($metode)) {
        $pesan_error = 'Silakan pilih metode pembayaran.';
    } else {
        // Validasi metode pembayaran
        $metode_valid = ['OVO', 'DANA', 'Gopay'];
        if (!in_array($metode, $metode_valid)) {
            $pesan_error = 'Metode pembayaran tidak valid.';
        } else {
            // Cek lagi apakah masih ada pesanan sebelum proses
            $check_stmt = $conn->prepare("SELECT COUNT(*) as jumlah FROM penyewaan_lapangan WHERE ID_Pelanggan = ? AND status = 'tersedia'");
            $check_stmt->bind_param("i", $id_pelanggan);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();
            $check_data = $check_result->fetch_assoc();
            $check_stmt->close();
            
            if ($check_data['jumlah'] == 0) {
                $pesan_error = 'Tidak ada pesanan yang tersedia untuk dibayar.';
            } else {
                // Panggil stored procedure
                $stmt = $conn->prepare("CALL checkout_pesanan_single_payment(?, ?)");
                if ($stmt) {
                    $stmt->bind_param("is", $id_pelanggan, $metode);
                    try {
                        $stmt->execute();
                        
                        // Ambil hasil dari stored procedure
                        $result = $stmt->get_result();
                        $hasil_pembayaran = null;
                        if ($result) {
                            $hasil_pembayaran = $result->fetch_assoc();
                            $result->free();
                        }

                        // Bersihkan multiple result set (penting untuk CALL procedure)
                        while ($stmt->more_results() && $stmt->next_result()) {
                            if ($res = $stmt->get_result()) {
                                $res->free();
                            }
                        }
                        $stmt->close();

                        // Tampilkan pesan sukses dengan detail
                        if ($hasil_pembayaran) {
                            $pesan_sukses = '✅ Pembayaran berhasil dikonfirmasi!<br>' .
                                          '<strong>ID Pembayaran:</strong> ' . $hasil_pembayaran['ID_Pembayaran'] . '<br>' .
                                          '<strong>Total Dibayar:</strong> Rp' . number_format($hasil_pembayaran['Total_Pembayaran'], 0, ',', '.') . '<br>' .
                                          '<strong>Jumlah Pesanan:</strong> ' . $hasil_pembayaran['Jumlah_Pesanan'] . ' pesanan<br>' .
                                          '<strong>Metode:</strong> ' . htmlspecialchars($metode);
                        } else {
                            $pesan_sukses = '✅ Pembayaran berhasil dikonfirmasi!';
                        }

                        // Refresh pesanan setelah bayar (ambil ulang data)
                        $pesanan = [];
                        $total_harga = 0;

                        // Ambil ulang pesanan yang sudah update status (harusnya kosong karena sudah dibayar)
                        $stmt2 = $conn->prepare($query);
                        $stmt2->bind_param("i", $id_pelanggan);
                        $stmt2->execute();
                        $result2 = $stmt2->get_result();
                        while ($row = $result2->fetch_assoc()) {
                            $pesanan[] = $row;
                            $total_harga += $row['Total_Biaya'];
                        }
                        $stmt2->close();

                    } catch (mysqli_sql_exception $e) {
                        $error_msg = $e->getMessage();
                        
                        // Handle specific error messages dari stored procedure
                        if (strpos($error_msg, 'Tidak ada pesanan') !== false) {
                            $pesan_error = '❌ Tidak ada pesanan yang tersedia untuk dibayar.';
                        } elseif (strpos($error_msg, 'duplicate') !== false) {
                            $pesan_error = '❌ Pembayaran sudah pernah diproses sebelumnya.';
                        } else {
                            $pesan_error = '❌ Gagal memproses pembayaran: ' . $error_msg;
                        }
                    } catch (Exception $e) {
                        $pesan_error = '❌ Terjadi kesalahan sistem. Silakan coba lagi.';
                    }
                } else {
                    $pesan_error = '❌ Gagal mempersiapkan pernyataan database.';
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Pembayaran</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <style>
    .navbar {
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .payment-card { 
      border-radius: 12px; 
      box-shadow: 0 5px 15px rgba(0,0,0,0.1); 
      transition: transform 0.2s ease;
    }
    .payment-card:hover {
      transform: translateY(-2px);
    }
    .section-title { 
      font-weight: 700; 
    }
    .total-highlight {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 15px;
      border-radius: 8px;
      margin-top: 10px;
    }
    .btn-pay {
      background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
      border: none;
      padding: 12px 20px;
      font-weight: 600;
      transition: all 0.3s ease;
    }
    .btn-pay:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
    }
    .btn-pay:disabled {
      background: #6c757d;
      transform: none;
      box-shadow: none;
    }
    .alert {
      border-radius: 10px;
      border: none;
    }
    .list-group-item {
      border: none;
      border-bottom: 1px solid #eee;
      padding: 12px 0;
    }
    .list-group-item:last-child {
      border-bottom: none;
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
  <h2 class="text-center fw-bold mb-4">
    <i class="fas fa-credit-card me-2 text-primary"></i>
    Konfirmasi Pembayaran
  </h2>

  <?php if ($pesan_sukses): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="fas fa-check-circle me-2"></i>
      <?= $pesan_sukses ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php elseif ($pesan_error): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <i class="fas fa-exclamation-triangle me-2"></i>
      <?= htmlspecialchars($pesan_error) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>

  <div class="card payment-card p-4 mb-4">
    <h5 class="section-title mb-3">
      <i class="fas fa-list me-2"></i>
      Detail Pesanan
    </h5>
    <?php if (count($pesanan) > 0): ?>
      <ul class="list-group mb-3">
        <?php foreach ($pesanan as $row): ?>
          <li class="list-group-item">
            <div class="row align-items-center">
              <div class="col-md-8">
                <strong><i class="fas fa-futbol me-2 text-success"></i><?= htmlspecialchars($row['Nama_Lapangan']) ?></strong><br />
                <small class="text-muted">
                  <i class="fas fa-calendar me-1"></i>
                  Tanggal: <?= date('d/m/Y', strtotime($row['Tanggal'])) ?> |
                  <i class="fas fa-clock me-1"></i>
                  Jam: <?= date('H:i', strtotime($row['Jam_Mulai'])) ?> - <?= date('H:i', strtotime($row['Jam_Selesai'])) ?>
                </small>
              </div>
              <div class="col-md-4 text-end">
                <span class="badge bg-success fs-6">
                  Rp<?= number_format($row['Total_Biaya'], 0, ',', '.') ?>
                </span>
              </div>
            </div>
          </li>
        <?php endforeach; ?>
      </ul>
      <div class="total-highlight">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <strong>Total Pembayaran:</strong>
            <br><small><?= count($pesanan) ?> pesanan</small>
          </div>
          <h4 class="mb-0 fw-bold">Rp<?= number_format($total_harga, 0, ',', '.') ?></h4>
        </div>
      </div>
    <?php else: ?>
      <div class="alert alert-warning text-center">
        <i class="fas fa-shopping-cart fa-3x mb-3 text-muted"></i>
        <h5>Tidak ada pesanan untuk dibayar</h5>
        <p class="mb-0">Keranjang Anda kosong atau pesanan sudah dibayar.</p>
      </div>
    <?php endif; ?>
  </div>

  <?php if (count($pesanan) > 0): ?>
    <div class="card payment-card p-4">
      <h5 class="section-title mb-3">
        <i class="fas fa-credit-card me-2"></i>
        Pilih Metode Pembayaran
      </h5>
      <form method="POST" id="paymentForm">
        <div class="mb-3">
          <label for="metode" class="form-label fw-semibold">
            <i class="fas fa-wallet me-2"></i>Metode Pembayaran
          </label>
          <select name="metode" id="metode" class="form-select" required>
            <option value="">-- Pilih Metode Pembayaran --</option>
            <option value="OVO">💜 OVO</option>
            <option value="DANA">💙 DANA</option>
            <option value="Gopay">💚 Gopay</option>
          </select>
        </div>
        <div class="d-grid">
          <button type="submit" class="btn btn-success btn-pay btn-lg" id="btnPay">
            <i class="fas fa-lock me-2"></i>
            Konfirmasi & Bayar (Rp<?= number_format($total_harga, 0, ',', '.') ?>)
          </button>
        </div>
        <div class="text-center mt-3">
          <small class="text-muted">
            <i class="fas fa-shield-alt me-1"></i>
            Pembayaran Anda aman dan terlindungi
          </small>
        </div>
      </form>
    </div>
  <?php endif; ?>
  
  <div class="text-center mt-4">
    <a href="keranjang.php" class="btn btn-outline-secondary">
      <i class="fas fa-arrow-left me-2"></i>Kembali ke Keranjang
    </a>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Form validation dan loading state
document.getElementById('paymentForm')?.addEventListener('submit', function(e) {
    const btnPay = document.getElementById('btnPay');
    const metode = document.getElementById('metode').value;
    
    if (!metode) {
        e.preventDefault();
        alert('Silakan pilih metode pembayaran!');
        return;
    }
    
    // Konfirmasi pembayaran
    if (!confirm(`Konfirmasi pembayaran dengan ${metode}?\nTotal: Rp<?= number_format($total_harga, 0, ',', '.') ?>`)) {
        e.preventDefault();
        return;
    }
    
    // Loading state
    btnPay.disabled = true;
    btnPay.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses Pembayaran...';
    
    // Timeout untuk mencegah hang
    setTimeout(() => {
        if (btnPay.disabled) {
            btnPay.disabled = false;
            btnPay.innerHTML = '<i class="fas fa-lock me-2"></i>Konfirmasi & Bayar (Rp<?= number_format($total_harga, 0, ',', '.') ?>)';
            alert('Proses pembayaran memakan waktu terlalu lama. Silakan coba lagi.');
        }
    }, 15000);
});

// Auto dismiss alerts
setTimeout(() => {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        if (alert.classList.contains('show')) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }
    });
}, 8000);
</script>
</body>
</html>