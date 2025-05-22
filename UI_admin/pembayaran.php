<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Manajemen Pembayaran - Persewaan Lapangan</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />

  <!-- Custom CSS -->
  <link rel="stylesheet" href="assets/css/style.css" />
</head>
<body>
  <div class="d-flex" style="min-height: 100vh;">
    <!-- Sidebar -->
    <nav class="bg-dark text-white p-3 flex-shrink-0" style="width: 250px;">
      <div class="d-flex align-items-center mb-4">
        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a7/React-icon.svg/1024px-React-icon.svg.png" alt="Logo" style="width:40px; height:40px;" />
        <h4 class="mb-0">Persewaan Lapangan</h4>
      </div>
      <ul class="nav nav-pills flex-column">
        <li class="nav-item"><a href="index.php" class="nav-link text-white">Dashboard</a></li>
        <li class="nav-item"><a href="pelanggan.php" class="nav-link text-white">Pelanggan</a></li>
        <li class="nav-item"><a href="lapangan.php" class="nav-link text-white">Lapangan</a></li>
        <li class="nav-item"><a href="penyewaan.php" class="nav-link text-white">Penyewaan</a></li>
        <li class="nav-item"><a href="pembayaran.php" class="nav-link text-white active">Pembayaran</a></li>
      </ul>
    </nav>

    <!-- Main content -->
    <!-- Main content -->
<main class="flex-grow-1 p-4 bg-light">
  <h1 class="mb-4">Manajemen Pembayaran</h1>

  <!-- Form input pembayaran -->
  <div class="card shadow-sm mb-4">
    <div class="card-header bg-primary text-white">
      <strong>Form Pembayaran</strong>
    </div>
    <div class="card-body">
      <form id="formPembayaran">
        <input type="hidden" id="pembayaranId" />
        <div class="row g-3">
          <div class="col-md-6">
            <label for="pilihPenyewaan" class="form-label">Pilih Penyewaan</label>
            <select id="pilihPenyewaan" class="form-select" required>
              <option value="" disabled selected>-- Pilih Penyewaan --</option>
            </select>
          </div>
          <div class="col-md-6">
            <label for="tanggalBayar" class="form-label">Tanggal Pembayaran</label>
            <input type="date" id="tanggalBayar" class="form-control" required />
          </div>
          <div class="col-md-6">
            <label for="jumlahBayar" class="form-label">Jumlah Bayar (Rp)</label>
            <input type="number" id="jumlahBayar" class="form-control" min="0" placeholder="Masukkan jumlah bayar" required />
          </div>
          <div class="col-md-6">
            <label for="statusBayar" class="form-label">Status Pembayaran</label>
            <select id="statusBayar" class="form-select" required>
              <option value="" disabled selected>-- Pilih Status --</option>
              <option value="Lunas">Lunas</option>
              <option value="Belum Lunas">Belum Lunas</option>
            </select>
          </div>
          <div class="col-12 d-flex justify-content-end">
            <button type="submit" class="btn btn-success">Simpan</button>
            <button type="reset" id="btnBatal" class="btn btn-secondary ms-2">Batal</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Tabel data pembayaran -->
  <div class="card shadow-sm">
    <div class="card-header bg-dark text-white">
      <strong>Data Pembayaran</strong>
    </div>
    <div class="card-body table-responsive">
      <table class="table table-bordered table-hover align-middle mb-0">
        <thead class="table-dark text-center">
          <tr>
            <th>#</th>
            <th>Penyewaan</th>
            <th>Tanggal Bayar</th>
            <th>Jumlah Bayar (Rp)</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody id="tbodyPembayaran" class="text-center"></tbody>
      </table>
    </div>
  </div>

  <footer align="center" class="mt-5 pt-4 border-top text-muted">
    &copy; 2025 Persewaan Lapangan. All rights reserved.
  </footer>
</main>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    // Ambil data penyewaan dan pelanggan dari localStorage
    function getPenyewaanData() {
      const data = localStorage.getItem("penyewaanData");
      return data ? JSON.parse(data) : [];
    }

    function getPelangganData() {
      const data = localStorage.getItem("pelangganData");
      return data ? JSON.parse(data) : [];
    }

    function getPembayaranData() {
      const data = localStorage.getItem("pembayaranData");
      return data ? JSON.parse(data) : [];
    }

    function setPembayaranData(data) {
      localStorage.setItem("pembayaranData", JSON.stringify(data));
    }

    function generateId() {
      return Date.now().toString();
    }

    // Render opsi penyewaan di dropdown pilihPenyewaan
    function renderPenyewaanOptions() {
      const penyewaanData = getPenyewaanData();
      const pelangganData = getPelangganData();
      const select = document.getElementById("pilihPenyewaan");

      select.innerHTML = <option value="" disabled selected>-- Pilih Penyewaan --</option>;

      if (penyewaanData.length === 0) {
        select.innerHTML += <option disabled>Belum ada data penyewaan</option>;
        return;
      }

      penyewaanData.forEach(penyewaan => {
        // Cari nama pelanggan sesuai idPelanggan
        const pelanggan = pelangganData.find(p => p.id === penyewaan.idPelanggan);
        const namaPelanggan = pelanggan ? pelanggan.nama : "Tidak ditemukan";

        const text = #${penyewaan.id} - ${namaPelanggan} (${penyewaan.tanggalSewa});
        select.innerHTML += <option value="${penyewaan.id}">${text}</option>;
      });
    }

    // Render tabel data pembayaran
    function renderData() {
      const pembayaranData = getPembayaranData();
      const penyewaanData = getPenyewaanData();
      const pelangganData = getPelangganData();
      const tbody = document.getElementById("tbodyPembayaran");
      tbody.innerHTML = "";

      if (pembayaranData.length === 0) {
        tbody.innerHTML = <tr><td colspan="6" class="text-center">Data pembayaran kosong</td></tr>;
        return;
      }

      pembayaranData.forEach((bayar, i) => {
        // Cari penyewaan
        const penyewaan = penyewaanData.find(p => p.id === bayar.idPenyewaan);
        let namaPelanggan = "-";
        let tanggalSewa = "-";
        if (penyewaan) {
          const pelanggan = pelangganData.find(p => p.id === penyewaan.idPelanggan);
          namaPelanggan = pelanggan ? pelanggan.nama : "-";
          tanggalSewa = penyewaan.tanggalSewa || "-";
        }

        const penyewaanText = #${bayar.idPenyewaan} - ${namaPelanggan} (${tanggalSewa});

        tbody.innerHTML += `
          <tr>
            <td>${i + 1}</td>
            <td>${penyewaanText}</td>
            <td>${bayar.tanggalBayar}</td>
            <td>Rp ${Number(bayar.jumlahBayar).toLocaleString("id-ID")}</td>
            <td>${bayar.status}</td>
            <td>
              <button class="btn btn-sm btn-warning me-2" onclick="editPembayaran('${bayar.id}')">Edit</button>
              <button class="btn btn-sm btn-danger" onclick="hapusPembayaran('${bayar.id}')">Hapus</button>
            </td>
          </tr>
        `;
      });
    }

    // Event submit form
    document.getElementById("formPembayaran").addEventListener("submit", function(e) {
      e.preventDefault();

      const id = document.getElementById("pembayaranId").value;
      const idPenyewaan = document.getElementById("pilihPenyewaan").value;
      const tanggalBayar = document.getElementById("tanggalBayar").value;
      const jumlahBayar = Number(document.getElementById("jumlahBayar").value);
      const status = document.getElementById("statusBayar").value;

      if (!idPenyewaan || !tanggalBayar || !jumlahBayar || !status) {
        alert("Semua field wajib diisi.");
        return;
      }

      let pembayaranData = getPembayaranData();

      if (id) {
        // Edit data
        const idx = pembayaranData.findIndex(p => p.id === id);
        if (idx !== -1) {
          pembayaranData[idx] = { id, idPenyewaan, tanggalBayar, jumlahBayar, status };
        }
      } else {
        // Tambah data baru
        pembayaranData.push({ id: generateId(), idPenyewaan, tanggalBayar, jumlahBayar, status });
      }

      setPembayaranData(pembayaranData);
      renderData();
      this.reset();
      document.getElementById("pembayaranId").value = "";
    });

    // Reset form saat batal
    document.getElementById("btnBatal").addEventListener("click", function() {
      document.getElementById("pembayaranId").value = "";
    });

    // Edit pembayaran
    function editPembayaran(id) {
      const pembayaranData = getPembayaranData();
      const pembayaran = pembayaranData.find(p => p.id === id);
      if (!pembayaran) return alert("Data tidak ditemukan!");

      document.getElementById("pembayaranId").value = pembayaran.id;
      document.getElementById("pilihPenyewaan").value = pembayaran.idPenyewaan;
      document.getElementById("tanggalBayar").value = pembayaran.tanggalBayar;
      document.getElementById("jumlahBayar").value = pembayaran.jumlahBayar;
      document.getElementById("statusBayar").value = pembayaran.status;
    }

    // Hapus pembayaran
    function hapusPembayaran(id) {
      if (!confirm("Yakin ingin menghapus data pembayaran ini?")) return;

      let pembayaranData = getPembayaranData();
      pembayaranData = pembayaranData.filter(p => p.id !== id);
      setPembayaranData(pembayaranData);
      renderData();
    }

    window.onload = () => {
      renderPenyewaanOptions();
      renderData();
    };
  </script>
</body>
</html>