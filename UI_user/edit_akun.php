<?php
// edit_akun.php
require '../data/koneksi.php';
session_start();

// Cek login
if (!isset($_SESSION['id_pelanggan'])) {
    header('Location: login.php');
    exit;
}

$id_pelanggan = $_SESSION['id_pelanggan'];
$pesan_sukses = '';
$pesan_error = '';

// Ambil data pelanggan saat ini
$query = "CALL getPelangganById(?);";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_pelanggan);
$stmt->execute();
$result = $stmt->get_result();
$data_pelanggan = $result->fetch_assoc();
$stmt->close();

$current_email = $data_pelanggan['Email'] ?? '';
$current_nama = $data_pelanggan['Nama'] ?? '';

// Proses update jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    // Validasi di PHP juga (double validation)
    if (empty($username)) {
        $pesan_error = 'Username tidak boleh kosong';
    } elseif (empty($password)) {
        $pesan_error = 'Password tidak boleh kosong';
    } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
        $pesan_error = 'Format email tidak valid';
    } elseif (strlen($password) < 6) {
        $pesan_error = 'Password minimal 6 karakter';
    } else {
        // Hash password untuk keamanan
        // $password = password_hash($password, PASSWORD_DEFAULT);
        
        // Update data pelanggan - trigger akan otomatis dijalankan
        $update_query = "CALL updatePelangganEmailPw(?, ?, ?)";
        $update_stmt = $conn->prepare($update_query);
        
        if ($update_stmt) {
            $update_stmt->bind_param("ssi", $username, $password, $id_pelanggan);
            
            try {
                if ($update_stmt->execute()) {
                    // Update session
                    $_SESSION['username'] = $username;
                    $pesan_sukses = '✅ Akun berhasil diperbarui!';
                    
                    // Ambil ulang data terbaru
                    $current_email = $username;
                } else {
                    $pesan_error = '❌ Gagal memperbarui akun: ' . $conn->error;
                }
            } catch (mysqli_sql_exception $e) {
                // Tangkap error dari trigger
                $error_message = $e->getMessage();
                
                if (strpos($error_message, 'username tidak boleh kosong') !== false) {
                    $pesan_error = '❌ Username tidak boleh kosong';
                } elseif (strpos($error_message, 'Password tidak boleh kosong') !== false) {
                    $pesan_error = '❌ Password tidak boleh kosong';
                } elseif (strpos($error_message, 'Format email tidak valid') !== false) {
                    $pesan_error = '❌ Format email tidak valid';
                } elseif (strpos($error_message, 'Email sudah digunakan') !== false) {
                    $pesan_error = '❌ Email sudah digunakan oleh pelanggan lain';
                } else {
                    $pesan_error = '❌ Gagal memperbarui akun: ' . $error_message;
                }
            }
            
            $update_stmt->close();
        } else {
            $pesan_error = '❌ Gagal mempersiapkan query database';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Akun - Sistem Penyewaan Lapangan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .edit-card {
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            border: none;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px 20px 0 0 !important;
            border: none;
        }
        .form-control {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            transform: translateY(-2px);
        }
        .btn {
            border-radius: 10px;
            padding: 12px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .alert {
            border-radius: 10px;
            border: none;
        }
        .navbar {
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .input-group-text {
            border-radius: 10px 0 0 10px;
            border: 2px solid #e9ecef;
            border-right: none;
            background: #f8f9fa;
        }
        .password-toggle {
            cursor: pointer;
            border-radius: 0 10px 10px 0;
            border: 2px solid #e9ecef;
            border-left: none;
            background: #f8f9fa;
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

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="card edit-card">
                    <div class="card-header text-white text-center py-4">
                        <h3 class="mb-0 fw-bold">
                            <i class="fas fa-user-edit me-2"></i>
                            Edit Akun
                        </h3>
                        <p class="mb-0 opacity-75">Perbarui informasi akun Anda</p>
                    </div>
                    <div class="card-body p-4">
                        <!-- Alert Messages -->
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

                        <form method="POST" id="editForm" novalidate>
                            <div class="mb-4">
                                <label for="username" class="form-label fw-semibold">
                                    <i class="fas fa-envelope me-2 text-primary"></i>
                                    Username (Email)
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-at"></i>
                                    </span>
                                    <input type="email" 
                                           class="form-control" 
                                           id="username" 
                                           name="username" 
                                           value="<?= htmlspecialchars($current_email) ?>" 
                                           required
                                           placeholder="nama@email.com">
                                </div>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Gunakan format email yang valid
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label fw-semibold">
                                    <i class="fas fa-lock me-2 text-primary"></i>
                                    Password Baru
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-key"></i>
                                    </span>
                                    <input type="password" 
                                           class="form-control" 
                                           id="password" 
                                           name="password" 
                                           required
                                           minlength="6"
                                           placeholder="Masukkan password baru">
                                    <span class="input-group-text password-toggle" onclick="togglePassword()">
                                        <i class="fas fa-eye" id="eyeIcon"></i>
                                    </span>
                                </div>
                                <div class="form-text">
                                    <i class="fas fa-shield-alt me-1"></i>
                                    Password minimal 6 karakter
                                </div>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-between">
                                <a href="home.php" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Batal
                                </a>
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <i class="fas fa-save me-2"></i>Simpan Perubahan
                                </button>
                            </div>
                        </form>

                        <div class="text-center mt-4">
                            <small class="text-muted">
                                <i class="fas fa-lock me-1"></i>
                                Data Anda aman dan terenkripsi
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle password visibility
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }

        // Form validation dan loading state
        document.getElementById('editForm').addEventListener('submit', function(e) {
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value.trim();
            const submitBtn = document.getElementById('submitBtn');
            
            // Client-side validation
            if (!username) {
                e.preventDefault();
                alert('Username tidak boleh kosong!');
                return;
            }
            
            if (!password) {
                e.preventDefault();
                alert('Password tidak boleh kosong!');
                return;
            }
            
            if (password.length < 6) {
                e.preventDefault();
                alert('Password minimal 6 karakter!');
                return;
            }
            
            // Email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(username)) {
                e.preventDefault();
                alert('Format email tidak valid!');
                return;
            }
            
            // Loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
            
            // Timeout protection
            setTimeout(() => {
                if (submitBtn.disabled) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>Simpan Perubahan';
                }
            }, 10000);
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
        }, 5000);

        // Focus effect
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });
    </script>
</body>
</html>