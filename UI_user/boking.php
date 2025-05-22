<?php
require '../data/koneksi.php';
session_start();

// Simulasi login sementara
if (!isset($_SESSION['id_pelanggan'])) {
    $_SESSION['id_pelanggan'] = 7;
}

$pesan = '';
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $pesan = "✅ Booking berhasil ditambahkan!";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pelanggan = $_SESSION['id_pelanggan'];
    $id_lapangan = $_POST['id_lapangan'] ?? '';
    $tanggal = $_POST['tanggal'] ?? '';
    $jam_mulai = $_POST['jam_mulai'] ?? '';
    $durasi = $_POST['durasi'] ?? 0;
    $id_pegawai = 1;

    if (!$id_lapangan || !$tanggal || !$jam_mulai || $durasi < 1) {
        $pesan = "❌ Data input tidak lengkap atau tidak valid.";
    } else {
        $stmt = $conn->prepare("CALL boking_lapangan(?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            $pesan = "❌ Gagal mempersiapkan query: " . $conn->error;
        } else {
            try {
                mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
                $stmt->bind_param("iissii", $id_pelanggan, $id_lapangan, $tanggal, $jam_mulai, $durasi, $id_pegawai);
                $stmt->execute();

                // Jika berhasil, redirect agar tidak submit ulang
                header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
                exit;
            } catch (mysqli_sql_exception $e) {
                $pesan = "❌ Gagal booking: " . $e->getMessage();
            }
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Detail Booking Lapangan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .card { border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .table th { background-color: #0d6efd; color: white; }
        .form-control:focus, .form-select:focus { box-shadow: none; border-color: #0d6efd; }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="#"><i class="fas fa-futbol me-2"></i>LAPANGAN SPORT</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="home.php"><i class="fas fa-home me-1"></i>Home</a></li>
                <li class="nav-item"><a class="nav-link" href="event.php"><i class="fas fa-calendar me-1"></i>Event</a></li>
                <li class="nav-item"><a class="nav-link" href="foto.php"><i class="fas fa-camera me-1"></i>Foto</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"><i class="fas fa-info-circle me-1"></i>Profil</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-users me-2"></i>Tentang Kami</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-phone me-2"></i>Kontak</a></li>
                    </ul>
                </li>
                <li class="nav-item"><a class="nav-link" href="keranjang.php"><i class="fas fa-shopping-cart me-1"></i>Keranjang</a></li>
            </ul>

            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-1"></i>
                        Hi, <?= isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Pengguna'; ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item active" href="edit_akun.php"><i class="fas fa-edit me-2"></i>Edit Akun</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="../login.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container py-5">
    <div class="card p-4">
        <h3 class="fw-bold mb-4 text-center">📝 Detail Booking Lapangan</h3>

        <?php if ($pesan): ?>
            <div class="alert alert-info"><?= htmlspecialchars($pesan) ?></div>
        <?php endif; ?>

        <form method="POST" class="row g-2 align-items-end mb-4" autocomplete="off">
            <div class="col-md-3">
                <label class="form-label" for="id_lapangan">Lapangan</label>
                <select class="form-select" name="id_lapangan" id="id_lapangan" required>
                    <?php
                    $result = $conn->query("SELECT ID_Lapangan, Nama_Lapangan, Harga_Per_Jam FROM lapangan ORDER BY Nama_Lapangan ASC");
                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()):
                    ?>
                        <option value="<?= htmlspecialchars($row['ID_Lapangan']) ?>">
                            <?= htmlspecialchars($row['Nama_Lapangan']) ?> - Rp<?= number_format($row['Harga_Per_Jam'], 0, ',', '.') ?>
                        </option>
                    <?php endwhile;
                    } else {
                        echo '<option disabled>Tidak ada lapangan</option>';
                    } ?>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label" for="tanggal">Tanggal</label>
                <input type="date" class="form-control" name="tanggal" id="tanggal" required min="<?= date('Y-m-d') ?>" />
            </div>

            <div class="col-md-2">
                <label class="form-label" for="jam_mulai">Jam Mulai</label>
                <input type="time" class="form-control" name="jam_mulai" id="jam_mulai" required />
            </div>

            <div class="col-md-2">
                <label class="form-label" for="durasi">Durasi (jam)</label>
                <input type="number" class="form-control" name="durasi" id="durasi" min="1" required />
            </div>

            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">➕ Tambah</button>
            </div>
        </form>

        <hr />

        <h5 class="text-center mb-3">Lihat Booking Hari Ini</h5>
        <div class="table-responsive">
            <table class="table table-bordered text-center align-middle">
                <thead>
                    <tr>
                        <th>Lapangan</th>
                        <th>Tanggal</th>
                        <th>Jam Mulai</th>
                        <th>Jam Selesai</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT * FROM checkout";
                      

                    $stmt = $conn->prepare($query);
                    if (!$stmt) {
                        echo '<tr><td colspan="5" class="text-danger">Error: ' . htmlspecialchars($conn->error) . '</td></tr>';
                    } else {
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if ($result && $result->num_rows > 0):
                            while ($row = $result->fetch_assoc()):
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($row['Nama_Lapangan']) ?></td>
                            <td><?= htmlspecialchars($row['Tanggal']) ?></td>
                            <td><?= htmlspecialchars($row['Jam_Mulai']) ?></td>
                            <td><?= htmlspecialchars($row['Jam_Selesai']) ?></td>
                            <td>Rp<?= number_format($row['Total_Biaya'], 0, ',', '.') ?></td>
                        </tr>
                    <?php endwhile;
                        else: ?>
                        <tr><td colspan="5">Tidak ada booking hari ini.</td></tr>
                    <?php endif;
                        $stmt->close();
                    }
                    ?>
                </tbody>
            </table>

            <div class="d-flex justify-content-end mt-4 gap-2">
                <a href="home.php" class="btn btn-outline-primary">🛒 Lanjut ke Belanja</a>
                <a href="checkout.php" class="btn btn-success">✅ Checkout</a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
