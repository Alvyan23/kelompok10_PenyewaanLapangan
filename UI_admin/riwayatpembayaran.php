<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Riwayat Pembayaran</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light">
  <div class="container py-4">
    <h2 class="mb-4">Riwayat Pembayaran</h2>

    <div class="table-responsive">
      <table class="table table-bordered table-striped">
        <thead class="table-dark">
          <tr>
            <th>No</th>
            <th>ID Pembayaran</th>
            <th>Nama Pelanggan</th>
            <th>Tanggal Pembayaran</th>
            <th>Metode</th>
            <th>Jumlah Bayar</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php
          // Koneksi ke database
          $conn = new mysqli("localhost", "root", "", "persewaan_lapangan");

          // Periksa koneksi
          if ($conn->connect_error) {
            die("Koneksi gagal: " . $conn->connect_error);
          }

          // Hapus data pembayaran jika ada permintaan hapus
          if (isset($_GET['hapus_id'])) {
            $hapus_id = $conn->real_escape_string($_GET['hapus_id']);

            $stmt = $conn->prepare("CALL hapus_pembayaran(?)");
            $stmt->bind_param("i", $hapus_id);

            if ($stmt->execute()) {
              echo "<div class='alert alert-success'>Data pembayaran dengan ID $hapus_id berhasil dihapus melalui prosedur.</div>";
            } else {
              echo "<div class='alert alert-danger'>Gagal menghapus data: " . $stmt->error . "</div>";
            }

            $stmt->close();
          }

          // Query data dari VIEW
          $sql = "SELECT * FROM v_pembayaran_lengkap ORDER BY Tanggal_Pembayaran DESC";
          $result = $conn->query($sql);

          if ($result->num_rows > 0) {
            $no = 1;
            while ($row = $result->fetch_assoc()) {
              $idBayar = htmlspecialchars($row['ID_Pembayaran']);
              $nama = htmlspecialchars($row['Nama']);
              $tglBayar = htmlspecialchars($row['Tanggal_Pembayaran']);
              $metode = htmlspecialchars($row['Metode_Pembayaran']);
              $jumlah = number_format($row['Jumlah_Bayar'], 0, ',', '.');

              echo "<tr>
                      <td>$no</td>
                      <td>$idBayar</td>
                      <td>$nama</td>
                      <td>$tglBayar</td>
                      <td>$metode</td>
                      <td>Rp $jumlah</td>
                      <td>
                        <a href='?hapus_id=$idBayar' onclick='return confirm(\"Yakin hapus data ini?\")' class='btn btn-sm btn-danger'>Hapus</a>
                      </td>
                    </tr>";
              $no++;
            }
          } else {
            echo "<tr><td colspan='7' class='text-center'>Tidak ada riwayat pembayaran.</td></tr>";
          }

          $conn->close();
          ?>
        </tbody>
      </table>
    </div>

    <a href="index.php" class="btn btn-secondary mt-3">← Kembali ke Dashboard</a>
  </div>
</body>
</html>
