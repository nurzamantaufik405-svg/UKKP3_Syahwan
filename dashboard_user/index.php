<?php 
session_start();
// Pastikan nama file koneksi di bawah ini sesuai dengan yang ada di folder inc (function.php atau funtion.php)
require_once '../inc/function.php'; 

// 1. KUNCI HALAMAN: Tendang jika belum login
if (!isset($_SESSION["email"])) {
    header("location: ../inc/login.php");
    exit;
}

// 2. KUNCI ROLE: Tendang jika bukan Masyarakat (Role 2)
$ROLE = $_SESSION['role'] ?? '';
if ($ROLE != 2) {
    if ($ROLE == 1) {
        echo "<script>
            alert('Admin tidak bisa mengakses dashboard masyarakat!');
            window.location.href = '../dashboard_admin/index.php';
        </script>";
        exit;
    } else {
        header("location: ../inc/login.php");
        exit;
    }
}

// 3. AMBIL DATA PROFIL MASYARAKAT DARI DATABASE
$EMAIL = $_SESSION['email'];
$NAMA_USER = "Masyarakat";
$NIK_USER = "-";
$TELP_USER = "-";
$EMAIL_USER = $EMAIL;

$SQL_LOGIN = tampil("SELECT tbl_masyarakat.nik, tbl_masyarakat.nama, tbl_masyarakat.telepon, tbl_users.email 
                     FROM tbl_masyarakat 
                     LEFT JOIN tbl_users ON tbl_masyarakat.id_users = tbl_users.id_users 
                     WHERE tbl_users.email = '$EMAIL'");
                     
foreach ($SQL_LOGIN as $USER_LOGIN) {
    $NAMA_USER = $USER_LOGIN['nama'];
    $NIK_USER = $USER_LOGIN['nik'];
    $TELP_USER = $USER_LOGIN['telepon'];
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Dashboard Masyarakat - PENGADUAN MVP</title>
  <meta name="description" content="Layanan Pengaduan Masyarakat Terpadu">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">
</head>

<body class="index-page">

  <header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container-fluid position-relative d-flex align-items-center justify-content-between">

      <a href="index.php" class="logo d-flex align-items-center me-auto me-xl-0">
        <h1 class="sitename">PENGADUAN MVP</h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="#hero" class="active">Beranda</a></li>
          <li><a href="#alur">Alur Pengaduan</a></li>
          <li><a href="#kategori">Kategori</a></li>
          <li><a href="#contact">Kontak</a></li>
          
          <!-- Dropdown Profil & Logout -->
          <li class="dropdown ms-lg-4">
            <a href="#" class="d-flex align-items-center">
              <i class="bi bi-person-circle fs-4 me-2 text-primary"></i>
              <span class="fw-bold"><?= $NAMA_USER; ?></span> 
              <i class="bi bi-chevron-down toggle-dropdown ms-1"></i>
            </a>
            <ul class="shadow rounded-4 p-3" style="min-width: 260px; border: 1px solid #eee;">
              
              <!-- Info Data Diri -->
              <li class="pb-2 mb-2 border-bottom">
                <div class="fw-bold text-dark" style="font-size: 16px;"><?= $NAMA_USER; ?></div>
                <div class="text-muted" style="font-size: 13px;"><?= $EMAIL_USER; ?></div>
              </li>
              <li style="font-size: 14px; padding: 6px 0; color: #444;">
                <i class="bi bi-credit-card-2-front me-2 text-primary"></i> <?= $NIK_USER; ?>
              </li>
              <li style="font-size: 14px; padding: 6px 0; color: #444;">
                <i class="bi bi-telephone me-2 text-primary"></i> <?= $TELP_USER; ?>
              </li>
              
              <!-- Tombol Logout -->
              <li class="mt-2 pt-2 border-top">
                <a href="../inc/logout.php" class="text-danger fw-bold d-flex align-items-center" style="padding: 10px 0;">
                  <i class="bi bi-box-arrow-right fs-5 me-2"></i> Logout Keluar
                </a>
              </li>

            </ul>
          </li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

    </div>
  </header>

  <main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section dark-background">
      <img src="assets/img/banner.jpg" alt="" data-aos="fade-in">
      <div class="container text-center" data-aos="fade-up" data-aos-delay="100">
        <div class="row justify-content-center">
          <div class="col-lg-8">
            <h2>Layanan Pengaduan Masyarakat MVP Terpadu</h2>
            <p>Sampaikan aspirasi dan keluhan Anda. Kami memastikan setiap laporan ditindaklanjuti secara cepat, transparan, dan terukur.</p>
            <a href="../page/user/form_pengaduan.php" class="btn-get-started shadow">Buat Laporan Sekarang</a>
          </div>
        </div>
      </div>
    </section>

    <!-- Alur Pengaduan Section -->
    <section id="alur" class="what-we-do section">
      <div class="container section-title" data-aos="fade-up">
        <h2>Bagaimana Cara Melapor?</h2>
        <p>Langkah mudah untuk menyampaikan keluhan Anda melalui aplikasi ini</p>
      </div>

      <div class="container">
        <div class="row gy-4">
          <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
            <div class="why-box">
              <h3>Suara Anda Berharga!</h3>
              <p>Jangan ragu untuk melapor jika Anda menemui fasilitas umum yang rusak, pelayanan publik yang kurang baik, atau gangguan keamanan di lingkungan sekitar Anda.</p>
              <div class="text-center">
                <a href="form_pengaduan.php" class="more-btn"><span>Mulai Lapor</span> <i class="bi bi-chevron-right"></i></a>
              </div>
            </div>
          </div>

          <div class="col-lg-8 d-flex align-items-stretch">
            <div class="row gy-4" data-aos="fade-up" data-aos-delay="200">
              <div class="col-xl-4">
                <div class="icon-box d-flex flex-column justify-content-center align-items-center">
                  <i class="bi bi-pencil-square"></i>
                  <h4>1. Tulis Laporan</h4>
                  <p>Jelaskan keluhan Anda dengan detail dan lampirkan foto bukti kejadian.</p>
                </div>
              </div>
              <div class="col-xl-4" data-aos="fade-up" data-aos-delay="300">
                <div class="icon-box d-flex flex-column justify-content-center align-items-center">
                  <i class="bi bi-search"></i>
                  <h4>2. Proses Verifikasi</h4>
                  <p>Laporan Anda akan ditinjau dan diverifikasi oleh Admin/Petugas kami.</p>
                </div>
              </div>
              <div class="col-xl-4" data-aos="fade-up" data-aos-delay="400">
                <div class="icon-box d-flex flex-column justify-content-center align-items-center">
                  <i class="bi bi-check-circle"></i>
                  <h4>3. Selesai Ditindak</h4>
                  <p>Petugas akan menyelesaikan masalah dan memberikan tanggapan di dashboard Anda.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Kategori Section -->
    <section id="kategori" class="services section light-background">
      <div class="container section-title" data-aos="fade-up">
        <h2>Kategori Laporan</h2>
        <p>Jenis pengaduan yang dapat Anda laporkan melalui sistem kami</p>
      </div>

      <div class="container">
        <div class="row gy-4">
          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
            <div class="service-item position-relative">
              <div class="icon"><i class="bi bi-cone-striped"></i></div>
              <h3>Infrastruktur</h3>
              <p>Laporan mengenai jalan rusak, jembatan putus, fasilitas umum terbengkalai, atau lampu jalan yang mati.</p>
            </div>
          </div>
          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
            <div class="service-item position-relative">
              <div class="icon"><i class="bi bi-people"></i></div>
              <h3>Pelayanan Publik</h3>
              <p>Layanan aparatur daerah yang kurang memuaskan, pungutan liar (pungli), atau antrean yang tidak wajar.</p>
            </div>
          </div>
          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
            <div class="service-item position-relative">
              <div class="icon"><i class="bi bi-shield-lock"></i></div>
              <h3>Keamanan & Ketertiban</h3>
              <p>Gangguan keamanan, balap liar, keributan, atau hal-hal yang mengganggu ketertiban lingkungan.</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact section">
      <div class="container section-title" data-aos="fade-up">
        <h2>Pusat Bantuan</h2>
        <p>Hubungi petugas kami jika Anda mengalami kesulitan dalam menggunakan aplikasi pengaduan</p>
      </div>

      <div class="container position-relative" data-aos="fade-up" data-aos-delay="100">
        <div class="row gy-4">
          <div class="col-lg-12">
            <div class="row gy-4 text-center">
              <div class="col-md-4">
                <div class="info-item d-flex flex-column align-items-center" data-aos="fade-up" data-aos-delay="200">
                  <i class="bi bi-geo-alt fs-2 text-primary mb-3"></i>
                  <div>
                    <h3>Alamat Kantor</h3>
                    <p>Jl. Sulaksana No. 45, Bandung</p>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="info-item d-flex flex-column align-items-center" data-aos="fade-up" data-aos-delay="300">
                  <i class="bi bi-telephone fs-2 text-primary mb-3"></i>
                  <div>
                    <h3>Telepon / WhatsApp</h3>
                    <p>0831-3390-4504</p>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="info-item d-flex flex-column align-items-center" data-aos="fade-up" data-aos-delay="400">
                  <i class="bi bi-envelope fs-2 text-primary mb-3"></i>
                  <div>
                    <h3>Email Instansi</h3>
                    <p>admin@pengaduan.com</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

  </main>

  <footer id="footer" class="footer light-background">
    <div class="container copyright text-center mt-4">
      <p>© <span>Copyright</span> <strong class="px-1 sitename">PENGADUAN MVP</strong> <span>All Rights Reserved</span></p>
      <div class="credits">
        Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
      </div>
    </div>
  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>

</body>
</html>