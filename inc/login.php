<?php
// Memulai session untuk melacak status login pengguna di browser
session_start();

// Memanggil file koneksi dan fungsi utama database
require_once 'function.php'; 

// Memasukkan script SweetAlert2 via CDN agar pop-up peringatan error tampil lebih interaktif
echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';

// Pengecekan Sesi: Jika pengguna sudah terlanjur login, cegah masuk ke halaman login
// Langsung tendang ke dashboard masing-masing sesuai hak aksesnya (role)
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 2) { // 2 = Role khusus Masyarakat
        header("location: ../dashboard_user/index.php");
        exit; 
    } elseif ($_SESSION['role'] == 1) { // 1 = Role khusus Admin
        header("location: ../dashboard_admin/index.php");
        exit; 
    }
}

// Mengecek apakah tombol 'Login' pada form telah diklik oleh user
if (isset($_POST['Login'])) {
    // Mengamankan input email dari potensi celah SQL Injection
    $EMAIL = mysqli_real_escape_string($CONN, $_POST['email']);
    $PASSWORD = $_POST['password'];

    // Melakukan query untuk mencocokkan email di dalam tabel tbl_users
    $cek_user = mysqli_query($CONN, "SELECT * FROM tbl_users WHERE email = '$EMAIL'");
    
    // Memastikan apakah email tersebut ditemukan (hanya ada 1 baris data yang cocok)
    if (mysqli_num_rows($cek_user) === 1) {
        $row = mysqli_fetch_assoc($cek_user);
        
        // Memverifikasi kecocokan password input dengan password terenkripsi (hash) di database
        if (password_verify($PASSWORD, $row['password'])) {
            
            // Jika valid, simpan informasi penting ke dalam variabel Session
            $_SESSION['id_users'] = $row['id_users'];
            $_SESSION['email']    = $row['email'];
            $_SESSION['role']     = $row['role'];

            // Mengarahkan halaman otomatis berdasarkan nilai role pengguna
            if ($row['role'] == 1) {
                header("location: ../dashboard_admin/index.php");
                exit;
            } elseif ($row['role'] == 2) {
                header("location: ../dashboard_user/index.php");
                exit;
            }
        } else {
            // Kondisi jika Password salah: Tampilkan pop-up SweetAlert error
            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Login Gagal',
                    text: 'Password yang Anda masukkan salah!',
                    confirmButtonColor: '#435ebe'
                }).then(function() {
                    window.location.href = 'login.php';
                });
            });
            </script>";
        }
    } else {
        // Kondisi jika Email tidak terdaftar di database
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Login Gagal',
                text: 'Email tidak terdaftar!',
                confirmButtonColor: '#435ebe'
            }).then(function() {
                window.location.href = 'login.php';
            });
        });
        </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login - Aplikasi Pengaduan</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="assets/vendors/feather/feather.css">
    <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="assets/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="assets/vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/vendors/typicons/typicons.css">
    <link rel="stylesheet" href="assets/vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="shortcut icon" href="assets/images/favicon.png" />
  </head>
  <body>
    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth px-0">
          <div class="row w-100 mx-0">
            <div class="col-lg-4 mx-auto">
              <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                <div class="brand-logo"></div>
                <h4>Selamat Datang di Aplikasi Pengaduan</h4>
                <h6 class="fw-light">Sign in to continue.</h6>
                
                <!-- Form Login menggunakan method POST -->
                <form class="pt-3" method="POST" action="">
                  <div class="form-group">
                    <input type="email" name="email" class="form-control form-control-lg" placeholder="Email" required>
                  </div>
                  <div class="form-group">
                    <input type="password" name="password" class="form-control form-control-lg" placeholder="Password" required>
                  </div>
                  <div class="mt-3 d-grid gap-2">
                    <!-- Tombol submit dengan name="Login" untuk ditangkap di PHP atas -->
                    <button type="submit" name="Login" class="btn btn-block btn-primary btn-lg fw-medium auth-form-btn">SIGN IN</button>
                  </div>
                  <div class="text-center mt-4 fw-light"> Don't have an account? <a href="register.php" class="text-primary">Create</a></div>
                </form>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- plugins:js -->
    <script src="assets/vendors/js/vendor.bundle.base.js"></script>
    <script src="assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="assets/js/off-canvas.js"></script>
    <script src="assets/js/template.js"></script>
    <script src="assets/js/settings.js"></script>
    <script src="assets/js/hoverable-collapse.js"></script>
    <script src="assets/js/todolist.js"></script>
  </body>
</html>