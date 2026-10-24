<?php 
session_start();
require_once '../../inc/function.php'; 

// 1. Kunci Halaman: Wajib login dan harus role Masyarakat (2)
if (!isset($_SESSION["email"]) || $_SESSION['role'] != 2) {
    header("location: ../../inc/login.php");
    exit;
}

// 2. Ambil data masyarakat yang sedang login
$EMAIL = $_SESSION['email'];
$id_masyarakat = "";
$nama_user = "";
$nik_user = "";

$sql_m = tampil("SELECT tbl_masyarakat.* FROM tbl_masyarakat 
                 LEFT JOIN tbl_users ON tbl_masyarakat.id_users = tbl_users.id_users 
                 WHERE tbl_users.email = '$EMAIL'");

foreach ($sql_m as $m) {
    $id_masyarakat = $m['id_masyarakat'];
    $nama_user = $m['nama'];
    $nik_user = $m['nik'];
}

// 3. Proses ketika tombol submit diklik
if (isset($_POST['kirim_pengaduan'])) {
    $isi_laporan = mysqli_real_escape_string($CONN, $_POST['isi_laporan']);
    $tgl_pengaduan = date('Y-m-d H:i:s');
    $status = 'pending'; // Status awal laporan

    // Validasi & Upload Foto
    $nama_file = $_FILES['foto']['name'];
    $ukuran_file = $_FILES['foto']['size'];
    $error = $_FILES['foto']['error'];
    $tmp_name = $_FILES['foto']['tmp_name'];

    if ($error === 4) {
        echo "<script>alert('Harap lampirkan foto bukti pengaduan!');</script>";
    } else {
        $ekstensi_valid = ['jpg', 'jpeg', 'png', 'webp'];
        $ekstensi_file = explode('.', $nama_file);
        $ekstensi_file = strtolower(end($ekstensi_file));

        if (!in_array($ekstensi_file, $ekstensi_valid)) {
            echo "<script>alert('Format file foto tidak valid! Gunakan JPG, JPEG, PNG, atau WEBP.');</script>";
        } else {
            // Tentukan folder tujuan
            $target_dir = "../../photo_pengaduan/";

            // BUAT FOLDER OTOMATIS JIKA BELUM ADA
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            // Buat nama file unik agar tidak bentrok
            $nama_file_baru = uniqid() . '.' . $ekstensi_file;
            
            // Simpan ke folder photo_pengaduan
            if (move_uploaded_file($tmp_name, $target_dir . $nama_file_baru)) {
                
                $query = "INSERT INTO pengaduan (id_masyarakat, tgl_pengaduan, isi_laporan, foto, status) 
                          VALUES ('$id_masyarakat', '$tgl_pengaduan', '$isi_laporan', '$nama_file_baru', '$status')";
                
                if (mysqli_query($CONN, $query)) {
                    echo "<script>
                        alert('Pengaduan berhasil dikirim! Petugas akan segera meninjau laporan Anda.');
                        window.location.href = '../../dashboard_user/index.php';
                    </script>";
                } else {
                    echo "<script>alert('Gagal menyimpan pengaduan ke database!');</script>";
                }
            } else {
                echo "<script>alert('Gagal mengunggah foto bukti!');</script>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Form Pengaduan - Masyarakat</title>

  <!-- Vendor CSS Files -->
  <link href="../../dashboard_user/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../../dashboard_user/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../../dashboard_user/assets/css/main.css" rel="stylesheet">
</head>

<body class="index-page bg-light">

  <header id="header" class="header d-flex align-items-center sticky-top bg-white shadow-sm">
    <div class="container-fluid position-relative d-flex align-items-center justify-content-between">
      <a href="../../dashboard_user/index.php" class="logo d-flex align-items-center me-auto me-xl-0">
        <h1 class="sitename">PENGADUAN MVP</h1>
      </a>
      <a href="../../dashboard_user/index.php" class="btn btn-outline-secondary rounded-pill px-4">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda
      </a>
    </div>
  </header>

  <main class="main my-5">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-4 p-md-5">
              
              <div class="text-center mb-4">
                <h3 class="fw-bold text-dark">Formulir Pengaduan Masyarakat</h3>
                <p class="text-muted">Sampaikan laporan atau keluhan Anda secara jelas dan lengkap</p>
              </div>

              <form action="" method="POST" enctype="multipart/form-data">
                
                <div class="mb-3">
                  <label class="form-label fw-bold text-secondary">Nama Pelapor</label>
                  <input type="text" class="form-control bg-light" value="<?= $nama_user; ?>" readonly>
                </div>

                <div class="mb-3">
                  <label class="form-label fw-bold text-secondary">NIK</label>
                  <input type="text" class="form-control bg-light" value="<?= $nik_user; ?>" readonly>
                </div>

                <div class="mb-3">
                  <label for="isi_laporan" class="form-label fw-bold text-secondary">Isi Laporan / Keluhan <span class="text-danger">*</span></label>
                  <textarea class="form-control" id="isi_laporan" name="isi_laporan" rows="5" placeholder="Jelaskan kronologi, lokasi kejadian, atau detail keluhan Anda di sini..." required></textarea>
                </div>

                <div class="mb-4">
                  <label for="foto" class="form-label fw-bold text-secondary">Bukti Foto <span class="text-danger">*</span></label>
                  <input type="file" class="form-control" id="foto" name="foto" accept=".jpg, .jpeg, .png, .webp" required>
                  <div class="form-text">Unggah foto bukti kondisi di lapangan (Format: JPG, JPEG, PNG, WEBP).</div>
                </div>

                <div class="d-grid gap-2">
                  <button type="submit" name="kirim_pengaduan" class="btn btn-primary py-3 fw-bold rounded-pill shadow-sm">
                    <i class="bi bi-send-fill me-2"></i> Kirim Pengaduan Sekarang
                  </button>
                </div>

              </form>

            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <script src="../../dashboard_user/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>