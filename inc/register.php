<?php
// Memulai session PHP
session_start();

// Memanggil file fungsi database karena posisi register.php berada di dalam folder inc/
require_once 'function.php';

// Mengecek apakah tombol 'Register' pada form telah diklik oleh user
if (isset($_POST['Register'])) {
    
    // Langkah 1: Validasi awal memastikan password dan konfirmasi password sama persis
    if ($_POST['password'] !== $_POST['konfirmasi_password']) {
        echo "<script>alert('Password dan Konfirmasi Password tidak cocok!');</script>";
    } else {
        
        // Langkah 2: Menjalankan fungsi registrasi dari function.php untuk menyimpan data ke database
        if (registrasi_masyarakat($_POST)) {
            // Jika berhasil disimpan ke database, tampilkan alert lalu arahkan otomatis ke halaman login
            echo "<script>
                alert('Registrasi Berhasil! Silakan Login.');
                window.location.href = 'login.php';
            </script>";
        } else {
            // Jika gagal (misal NIK atau email sudah terdaftar sebelumnya)
            echo "<script>alert('Registrasi Gagal! NIK atau Email mungkin sudah terdaftar.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Registrasi - Aplikasi Pengaduan Masyarakat</title>
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
                <div class="brand-logo">
                  <h3 class="fw-bold text-primary">PENGADUAN</h3>
                </div>
                <h4>Belum Punya Akun?</h4>
                <h6 class="fw-light">Daftar sekarang untuk mulai melaporkan pengaduan.</h6>
                
                <!-- Form Registrasi menggunakan method POST untuk mengirim data inputan -->
                <form class="pt-3" action="" method="POST">
                  
                  <div class="form-group">
                    <input type="text" class="form-control form-control-lg" name="nik" placeholder="NIK (16 Digit)" required maxlength="16">
                  </div>
                  
                  <div class="form-group">
                    <input type="text" class="form-control form-control-lg" name="nama" placeholder="Nama Lengkap" required>
                  </div>
                  
                  <div class="form-group">
                    <input type="text" class="form-control form-control-lg" name="telepon" placeholder="Nomor Telepon / WA" required maxlength="15">
                  </div>
                  
                  <div class="form-group">
                    <input type="email" class="form-control form-control-lg" name="email" placeholder="Email" required>
                  </div>
                  
                  <div class="form-group">
                    <input type="password" class="form-control form-control-lg" name="password" placeholder="Password" required minlength="6">
                  </div>
                  
                  <div class="form-group">
                    <input type="password" class="form-control form-control-lg" name="konfirmasi_password" placeholder="Konfirmasi Password" required minlength="6">
                  </div>
                  
                  <div class="mb-4">
                    <div class="form-check">
                      <label class="form-check-label text-muted">
                        <input type="checkbox" class="form-check-input" required> Saya setuju dengan syarat & ketentuan 
                      </label>
                    </div>
                  </div>
                  
                  <div class="mt-3 d-grid gap-2">
                    <!-- Tombol submit dengan name="Register" untuk memicu logika PHP di atas -->
                    <button type="submit" name="Register" class="btn btn-block btn-primary btn-lg fw-medium auth-form-btn">DAFTAR SEKARANG</button>
                  </div>
                  
                  <div class="text-center mt-4 fw-light"> 
                    Sudah punya akun? <a href="login.php" class="text-primary">Login di sini</a>
                  </div>
                  
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