<?php
require './data/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama     = $_POST['username'];
    $nohp     = $_POST['nohp'];
    $email    = $_POST['email'];
    $password = $_POST['password'];

    // Panggil stored procedure
    $stmt = $conn->prepare("CALL tambahakun(?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nama, $nohp, $email, $password);

    try {
        if ($stmt->execute()) {
            echo "<script>
                    alert('Akun berhasil ditambahkan!');
                    window.location.href = 'login.html';
                  </script>";
            exit;
        } else {
            echo "<script>
                    alert('Gagal menambahkan akun.');
                    window.history.back();
                  </script>";
            exit;
        }
    } catch (mysqli_sql_exception $e) {
        echo "<script>
                alert('Gagal menambahkan akun: " . addslashes($e->getMessage()) . "');
                window.history.back();
              </script>";
        exit;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Tambah Akun | Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <style>
    body {
      background: linear-gradient(to right, #141e30, #243b55);
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      color: #fff;
    }
    .form-card {
      background-color: rgba(255, 255, 255, 0.05);
      padding: 30px;
      border-radius: 15px;
      backdrop-filter: blur(10px);
      box-shadow: 0 8px 20px rgba(0,0,0,0.4);
      width: 100%;
      max-width: 500px;
    }
    .form-control {
      background-color: rgba(255, 255, 255, 0.2);
      color: #fff;
      border: none;
    }
    .form-control::placeholder {
      color: #ddd;
    }
    .form-control:focus {
      background-color: rgba(255, 255, 255, 0.3);
      color: #fff;
      box-shadow: none;
    }
    .btn-submit {
      background-color: #00b894;
      border: none;
    }
    .btn-submit:hover {
      background-color: #00917c;
    }
  </style>
</head>
<body>

  <div class="form-card">
    <h3 class="text-center mb-4">Tambah Akun </h3>
    <form action="" method="post">
      <div class="mb-3">
        <label for="username" class="form-label">Nama</label>
        <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan nama" required>
      </div>
      <div class="mb-3">
        <label for="nohp" class="form-label">No.Hp</label>
        <input type="text" class="form-control" id="nohp" name="nohp" placeholder="Masukkan No Telepon" required>
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Email </label>
        <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan email" required>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Kata Sandi</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan kata sandi" required>
      </div>
      
      <button type="submit" class="btn btn-submit w-100">Simpan Akun</button>
      <div class="mt-3" style="text-align: center;">
        <small>masuk akun anda <a href="login.php" class="text-white text-decoration-underline">Login</a></small>
      </div>
    </form>
  </div>
    
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php