<?php 
session_start();
require_once '../inc/function.php'; 

// Cek apakah pengguna sudah login
if (!isset($_SESSION["email"])) {
    header("location: ../inc/login.php");
    exit;
}

// Ambil nilai role (1 = Admin, 2 = Masyarakat)
$ROLE = $_SESSION['role'] ?? '';

// Kunci Halaman: Tendang jika BUKAN Admin (Role 1)
if ($ROLE != 1) {
    if ($ROLE == 2) {
        echo "<script>
            alert('Akses Ditolak! Anda login sebagai Masyarakat.');
            window.location.href = '../dashboard_user/index.php';
        </script>";
        exit;
    } else {
        header("location: ../inc/login.php");
        exit;
    }
}

$EMAIL = $_SESSION['email'];

// Ambil data profil admin menggunakan relasi JOIN
$SQL_LOGIN = tampil("SELECT tbl_admin.nama_admin, tbl_users.email, tbl_users.id_users 
                     FROM tbl_admin
                     LEFT JOIN tbl_users ON tbl_admin.id_users = tbl_users.id_users 
                     WHERE tbl_users.email = '$EMAIL'");

foreach ($SQL_LOGIN as $USER_LOGIN) {
    $NAMA_USER = $USER_LOGIN['nama_admin'];
    $ID_USER = $USER_LOGIN['id_users'];
    $TIPE_USER = 'Admin'; 
}
?>

<!DOCTYPE html>
<html lang="id">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Dashboard Admin - Pengaduan Masyarakat</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="assets/vendors/feather/feather.css">
    <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="assets/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="assets/vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/vendors/typicons/typicons.css">
    <link rel="stylesheet" href="assets/vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css">
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css">
    <link rel="stylesheet" type="text/css" href="assets/js/select.dataTables.min.css">
    <!-- inject:css -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="shortcut icon" href="assets/images/favicon.png" />
  </head>
  <body class="with-welcome-text">
    <div class="container-scroller">
      
      <!-- partial:partials/_navbar.html -->
      <nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
        <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
          <div class="me-3">
            <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
              <span class="icon-menu"></span>
            </button>
          </div>
          <div>
            <a class="navbar-brand brand-logo" href="index.php">
              <h3 class="fw-bold text-primary mb-0">PENGADUAN</h3>
            </a>
            <a class="navbar-brand brand-logo-mini" href="index.php">
              <h3 class="fw-bold text-primary mb-0">P</h3>
            </a>
          </div>
        </div>
        <div class="navbar-menu-wrapper d-flex align-items-top">
          <ul class="navbar-nav">
            <li class="nav-item fw-semibold d-none d-lg-block ms-0">
              <h1 class="welcome-text">Selamat Datang, <span class="text-black fw-bold"><?= $NAMA_USER; ?></span></h1>
              <h3 class="welcome-sub-text">Sistem Informasi Pengaduan Masyarakat Terpadu</h3>
            </li>
          </ul>
          <ul class="navbar-nav ms-auto">
            <li class="nav-item d-none d-lg-block">
              <div id="datepicker-popup" class="input-group date datepicker navbar-date-picker">
                <span class="input-group-addon input-group-prepend border-right">
                  <span class="icon-calendar input-group-text calendar-icon"></span>
                </span>
                <input type="text" class="form-control" disabled>
              </div>
            </li>
            
            <!-- Profil Dropdown -->
            <li class="nav-item dropdown d-none d-lg-block user-dropdown">
              <a class="nav-link" id="UserDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                <img class="img-xs rounded-circle" src="assets/images/faces/face8.jpg" alt="Profile image"> 
              </a>
              <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
                <div class="dropdown-header text-center">
                  <img class="img-md rounded-circle" src="assets/images/faces/face8.jpg" alt="Profile image">
                  <p class="mb-1 mt-3 fw-semibold"><?= $NAMA_USER; ?></p>
                  <p class="fw-light text-muted mb-0"><?= $EMAIL; ?></p>
                </div>
                <!-- Tombol Logout yang mengarah ke file inc/logout.php -->
                <a href="../inc/logout.php" class="dropdown-item">
                  <i class="dropdown-item-icon mdi mdi-power text-danger me-2"></i>Logout Keluar
                </a>
              </div>
            </li>
          </ul>
          <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-bs-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
          </button>
        </div>
      </nav>
      <!-- partial -->

      <div class="container-fluid page-body-wrapper">
        
        <!-- partial:partials/_sidebar.html -->
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
          <ul class="nav">
            <li class="nav-item">
              <a class="nav-link" href="index.php">
                <i class="mdi mdi-grid-large menu-icon"></i>
                <span class="menu-title">Dashboard</span>
              </a>
            </li>
            
            <li class="nav-item nav-category">Kelola Data</li>
            <li class="nav-item">
              <a class="nav-link" href="?pages=pengaduan">
                <i class="menu-icon mdi mdi-file-document-box-outline"></i>
                <span class="menu-title">Data Pengaduan</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="?pages=masyarakat">
                <i class="menu-icon mdi mdi-account-group"></i>
                <span class="menu-title">Data Masyarakat</span>
              </a>
            </li>

            <li class="nav-item nav-category">Laporan</li>
            <li class="nav-item">
              <a class="nav-link" href="?pages=laporan">
                <i class="menu-icon mdi mdi-printer"></i>
                <span class="menu-title">Cetak Laporan</span>
              </a>
            </li>
          </ul>
        </nav>
        <!-- partial -->
        
        <div class="main-panel">
          <div class="content-wrapper">
            <!-- Konten dinamis yang memanggil menu.php -->
            <?php include __DIR__ . '/../inc/menu.php'; ?>
          </div>
          <!-- content-wrapper ends -->
          
          <!-- partial:partials/_footer.html -->
          <footer class="footer">
            <div class="d-sm-flex justify-content-center justify-content-sm-between">
              <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Sistem Informasi Pengaduan Masyarakat</span>
              <span class="float-none float-sm-end d-block mt-1 mt-sm-0 text-center">Copyright © 2026. All rights reserved.</span>
            </div>
          </footer>
          <!-- partial -->
        </div>
        <!-- main-panel ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    
    <!-- plugins:js -->
    <script src="assets/vendors/js/vendor.bundle.base.js"></script>
    <script src="assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <!-- endinject -->
    
    <!-- Plugin js for this page -->
    <script src="assets/vendors/chart.js/chart.umd.js"></script>
    <script src="assets/vendors/progressbar.js/progressbar.min.js"></script>
    <!-- End plugin js for this page -->
    
    <!-- inject:js -->
    <script src="assets/js/off-canvas.js"></script>
    <script src="assets/js/template.js"></script>
    <script src="assets/js/settings.js"></script>
    <script src="assets/js/hoverable-collapse.js"></script>
    <script src="assets/js/todolist.js"></script>
    <!-- endinject -->
    
    <!-- Custom js for this page-->
    <script src="assets/js/jquery.cookie.js" type="text/javascript"></script>
    <script src="assets/js/dashboard.js"></script>
    <!-- End custom js for this page-->
  </body>
</html>