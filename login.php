<?php
session_start();
require __DIR__ . '/data/koneksi.php';

$pesan = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Cek di view login_admin
    $stmt = $conn->prepare("SELECT * FROM login_admin WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultAdmin = $stmt->get_result();
    $dataAdmin = $resultAdmin->fetch_assoc();
    $stmt->close();

    if ($dataAdmin) {
        // Jika email ditemukan di login_admin
        if ($password === $dataAdmin['pw']) {
            $_SESSION['email'] = $dataAdmin['Email'];
            $_SESSION['role'] = 'admin';

            echo "<script>
                alert('Login sebagai admin berhasil');
                window.location.href = 'UI_admin/index.php';
            </script>";
            exit;
        } else {
            $pesan = "❌ Password salah untuk admin!";
        }
    } else {
        // Cek di view login_user
        $stmt = $conn->prepare("SELECT * FROM login_user WHERE Email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultUser = $stmt->get_result();
        $dataUser = $resultUser->fetch_assoc();
        $stmt->close();

        if ($dataUser) {
            // Jika email ditemukan di login_user
            if ($password === $dataUser['pw']) {  
                $_SESSION['email'] = $dataUser['Email'];
                $_SESSION['role'] = 'user';
                $_SESSION['id_pelanggan'] = $dataUser['ID_Pelanggan'];

                echo "<script>
                    alert('Login sebagai user berhasil');
                    window.location.href = 'UI_user/home.php';
                </script>";
                exit;
            } else {
                $pesan = "❌ Password salah untuk user!";
            }
        } else {
            // Email tidak ditemukan di kedua view
            $pesan = "❌ Data tidak ditemukan. Silakan buat akun terlebih dahulu.";
        }
    }
}
?>






<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login | Booking Futsal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #0f2027, #203a43, #2c5364);
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #fff;
    }
    .login-card {
      background-color: rgba(255, 255, 255, 0.1);
      border: none;
      border-radius: 15px;
      padding: 30px;
      width: 100%;
      max-width: 400px;
      backdrop-filter: blur(10px);
      box-shadow: 0 8px 20px rgba(0,0,0,0.3);
    }
    .form-control {
      background-color: rgba(255, 255, 255, 0.2);
      border: none;
      color: #fff;
    }
    .form-control::placeholder {
      color: #ddd;
    }
    .form-control:focus {
      background-color: rgba(255, 255, 255, 0.25);
      color: #fff;
      box-shadow: none;
    }
    .btn-login {
      background-color: #00c851;
      border: none;
    }
    .btn-login:hover {
      background-color: #007e33;
    }
    .logo {
      width: 60px;
      height: 60px;
      margin-bottom: 15px;
    }
  </style>
</head>
<body>

  <div class="login-card text-center">
    <img src="aset/logo.png" alt="Logo" class="logo rounded-circle" />
    <h4 class="mb-4">Login</h4>

    <?php if (!empty($pesan)) : ?>
      <div class="alert alert-light text-dark py-2" role="alert">
        <?= $pesan ?>
      </div>
    <?php endif; ?>

    <form action="" method="post">
      <div class="form-floating mb-3">
        <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
        <label for="email">Email</label>
      </div>
      <div class="form-floating mb-4">
        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
        <label for="password">Password</label>
      </div>
      <button type="submit" class="btn btn-login w-100 py-2">Login</button>
    </form>
    <div class="mt-3">
      <small>Belum punya akun? <a href="register.php" class="text-white text-decoration-underline">Buat Akun</a></small>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php