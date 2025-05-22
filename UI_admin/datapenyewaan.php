<?php
// aktifkan error reporting untuk debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// koneksi ke database
$host = "localhost";
$user = "root";
$pass = "";
$db = "persewaan_lapangan";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
  die("Koneksi gagal: " . $conn->connect_error);
}

// query dari view
$sql = "SELECT * FROM view_detail_penyewaan ORDER BY Tanggal DESC, ID_Sewa ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Detail Penyewaan Lapangan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
  <style>
    .status-badge {
      font-size: 0.8em;
      padding: 0.25rem 0.5rem;
    }
    .table-responsive {
      font-size: 0.9rem;
    }
    .currency {
      font-weight: 600;
      color: #28a745;
    }
  </style>
</head>
<body>
  <div class="container-fluid mt-4">
    <div class="card shadow">
      <div class="card-header bg-primary text-white">
        <h3 class="card-title mb-0">
          <i class="fas fa-calendar-alt me-2"></i>
          Detail Data Penyewaan Lapangan
        </h3>
      </div>
      <div class="card-body">
        <?php if ($result && $result->num_rows > 0): ?>
          <div class="table-responsive">
            <table class="table table-striped table-hover">
              <thead class="table-dark">
                <tr>
                  <th>ID Penyewaan</th>
                  <th>Pelanggan</th>
                  <th>Lapangan</th>
                  <th>Jenis</th>
                  <th>Tanggal</th>
                  <th>Waktu</th>
                  <th>Durasi (jam)</th>
                  <th>Total Biaya</th>
                  <th>Status</th>
                  <th>Pembayaran</th>
                </tr>
              </thead>
              <tbody>
                <?php
                // Hitung summary
                $total_pendapatan = 0;
                $sudah_bayar = 0;
                $rows = [];
                while ($row = $result->fetch_assoc()) {
                  $rows[] = $row; // simpan untuk nanti cetak summary
                  if (strtolower($row['Status']) === 'sudah bayar' || strtolower($row['Status']) === 'lunas') {
                    $total_pendapatan += $row['Total_Biaya'];
                    $sudah_bayar++;
                  }
                }
                foreach ($rows as $row):
                  // Status pembayaran bisa dari kolom Status atau buat logika sendiri
                  $status_pembayaran = strtolower($row['Status']) === 'sudah bayar' || strtolower($row['Status']) === 'lunas' ? 'Sudah Bayar' : 'Belum Bayar';
                  $payment_class = $status_pembayaran === 'Sudah Bayar' ? 'bg-success' : 'bg-danger';
                ?>
                  <tr>
                    <td><span class="badge bg-secondary">#<?= htmlspecialchars($row['ID_Sewa']) ?></span></td>
                    <td><?= htmlspecialchars($row['Pelanggan']) ?></td>
                    <td><?= htmlspecialchars($row['Lapangan']) ?></td>
                    <td><span class="badge bg-info"><?= htmlspecialchars($row['Jenis']) ?></span></td>
                    <td><?= date('d/m/Y', strtotime($row['Tanggal'])) ?></td>
                    <td><?= htmlspecialchars($row['Waktu']) ?></td>
                    <td><span class="badge bg-warning text-dark"><?= htmlspecialchars($row['Durasi']) ?> Jam</span></td>
                    <td><span class="currency">Rp <?= number_format($row['Total_Biaya'], 0, ',', '.') ?></span></td>
                    <td>
                      <span class="badge bg-secondary status-badge"><?= htmlspecialchars($row['Status']) ?></span>
                    </td>
                    <td>
                      <span class="badge <?= $payment_class ?> status-badge"><?= $status_pembayaran ?></span>
                      <?php if (!empty($row['Metode_pembayaran'])): ?>
                        <br><small class="text-muted"><?= htmlspecialchars($row['Metode_pembayaran']) ?></small>
                      <?php endif; ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>

          <!-- Summary -->
          <div class="row mt-4">
            <div class="col-md-3">
              <div class="card bg-primary text-white text-center">
                <div class="card-body">
                  <h5>Total Penyewaan</h5>
                  <h3><?= count($rows) ?></h3>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="card bg-success text-white text-center">
                <div class="card-body">
                  <h5>Sudah Bayar</h5>
                  <h3><?= $sudah_bayar ?></h3>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="card bg-warning text-dark text-center">
                <div class="card-body">
                  <h5>Belum Bayar</h5>
                  <h3><?= count($rows) - $sudah_bayar ?></h3>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="card bg-info text-white text-center">
                <div class="card-body">
                  <h5>Total Pendapatan</h5>
                  <h4>Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></h4>
                </div>
              </div>
            </div>
          </div>
        <?php else: ?>
          <div class="alert alert-info text-center">
            <i class="fas fa-info-circle me-2"></i>
            <strong>Tidak ada data penyewaan ditemukan</strong><br>
            Belum ada transaksi penyewaan lapangan.
          </div>
        <?php endif; ?>

        <div class="mt-4">
          <a href="index.html" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard</a>
          <a href="tambah_penyewaan.php" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Tambah Penyewaan</a>
          <button onclick="window.print()" class="btn btn-outline-primary"><i class="fas fa-print me-2"></i>Cetak</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
