<?php
// Ambil ID pengaduan dari URL
$id_pengaduan = $_GET['id'] ?? '';

// Ambil data pengaduan beserta data masyarakat
$query = "SELECT pengaduan.*, tbl_masyarakat.nama, tbl_masyarakat.nik, tbl_masyarakat.telepon 
          FROM pengaduan 
          JOIN tbl_masyarakat ON pengaduan.id_masyarakat = tbl_masyarakat.id_masyarakat 
          WHERE pengaduan.id_pengaduan = '$id_pengaduan'";
$result = tampil($query);

if (empty($result)) {
    echo "<script>alert('Data pengaduan tidak ditemukan!'); window.location.href='index.php?pages=pengaduan';</script>";
    exit;
}

$data = $result[0];

// Ambil ID admin yang sedang login
$EMAIL = $_SESSION['email'];
$id_admin = "";
$q_admin = tampil("SELECT tbl_admin.id_admin FROM tbl_admin 
                   JOIN tbl_users ON tbl_admin.id_users = tbl_users.id_users 
                   WHERE tbl_users.email = '$EMAIL'");
foreach ($q_admin as $adm) {
    $id_admin = $adm['id_admin'];
}

// Proses ketika tombol Kirim Tanggapan diklik
if (isset($_POST['kirim_tanggapan'])) {
    $isi_tanggapan = mysqli_real_escape_string($CONN, $_POST['isi_tanggapan']);
    $status_baru = $_POST['status'];
    $tgl_tanggapan = date('Y-m-d H:i:s');

    // Cek apakah sudah ada tanggapan sebelumnya
    $cek_tanggapan = tampil("SELECT * FROM tanggapan WHERE id_pengaduan = '$id_pengaduan'");

    if (empty($cek_tanggapan)) {
        // Insert tanggapan baru
        $query_tanggapan = "INSERT INTO tanggapan (id_pengaduan, id_admin, tgl_tanggapan, isi_tanggapan) 
                            VALUES ('$id_pengaduan', '$id_admin', '$tgl_tanggapan', '$isi_tanggapan')";
    } else {
        // Update tanggapan jika admin ingin mengubahnya
        $query_tanggapan = "UPDATE tanggapan SET id_admin = '$id_admin', tgl_tanggapan = '$tgl_tanggapan', isi_tanggapan = '$isi_tanggapan' 
                            WHERE id_pengaduan = '$id_pengaduan'";
    }

    // Update status di tabel pengaduan
    $query_status = "UPDATE pengaduan SET status = '$status_baru' WHERE id_pengaduan = '$id_pengaduan'";

    if (mysqli_query($CONN, $query_tanggapan) && mysqli_query($CONN, $query_status)) {
        echo "<script>
            alert('Tanggapan berhasil dikirim dan status diperbarui!');
            window.location.href='index.php?pages=detail_pengaduan&id=$id_pengaduan';
        </script>";
    } else {
        echo "<script>alert('Gagal menyimpan tanggapan!');</script>";
    }
}

// Ambil data tanggapan jika sudah ada
$tanggapan_lama = tampil("SELECT tanggapan.*, tbl_admin.nama_admin FROM tanggapan 
                          LEFT JOIN tbl_admin ON tanggapan.id_admin = tbl_admin.id_admin 
                          WHERE tanggapan.id_pengaduan = '$id_pengaduan'");
$isi_tanggapan_eks = "";
$nama_admin_eks = "";
if (!empty($tanggapan_lama)) {
    $isi_tanggapan_eks = $tanggapan_lama[0]['isi_tanggapan'];
    $nama_admin_eks = $tanggapan_lama[0]['nama_admin'];
}
?>

<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card card-rounded">
      <div class="card-body">
        
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
          <h4 class="card-title card-title-dash mb-0">Detail & Tanggapan Pengaduan</h4>
          <a href="index.php?pages=pengaduan" class="btn btn-secondary btn-sm text-white">
            <i class="mdi mdi-arrow-left"></i> Kembali
          </a>
        </div>

        <div class="row">
          <!-- Informasi Pengaduan -->
          <div class="col-md-6">
            <h5 class="fw-bold mb-3 text-primary">Informasi Pelapor</h5>
            <table class="table table-borderless">
              <tr>
                <td style="width: 140px;" class="fw-bold">Nama Pelapor</td>
                <td>: <?= $data['nama']; ?></td>
              </tr>
              <tr>
                <td class="fw-bold">NIK</td>
                <td>: <?= $data['nik']; ?></td>
              </tr>
              <tr>
                <td class="fw-bold">No. Telepon</td>
                <td>: <?= $data['telepon']; ?></td>
              </tr>
              <tr>
                <td class="fw-bold">Tanggal Masuk</td>
                <td>: <?= $data['tgl_pengaduan']; ?></td>
              </tr>
              <tr>
                <td class="fw-bold">Status Saat Ini</td>
                <td>: 
                  <?php 
                  if ($data['status'] == 'pending') echo '<span class="badge badge-opacity-danger">Pending</span>';
                  elseif ($data['status'] == 'proses') echo '<span class="badge badge-opacity-warning">Diproses</span>';
                  elseif ($data['status'] == 'selesai') echo '<span class="badge badge-opacity-success">Selesai</span>';
                  ?>
                </td>
              </tr>
            </table>

            <h5 class="fw-bold mt-4 mb-2 text-primary">Isi Laporan</h5>
            <div class="p-3 bg-light rounded border mb-3">
              <p class="mb-0"><?= nl2br($data['isi_laporan']); ?></p>
            </div>
          </div>

          <!-- Bukti Foto & Form Tanggapan -->
          <div class="col-md-6">
            <h5 class="fw-bold mb-3 text-primary">Bukti Foto</h5>
            <div class="mb-4 text-center bg-light p-2 rounded border">
              <?php if (!empty($data['foto'])) : ?>
                <a href="../../photo_pengaduan/<?= $data['foto']; ?>" target="_blank">
                  <img src="../../photo_pengaduan/<?= $data['foto']; ?>" alt="Bukti Foto" class="img-fluid rounded" style="max-height: 250px; object-fit: contain;" />
                </a>
                <p class="small text-muted mt-2 mb-0">Klik foto untuk memperbesar</p>
              <?php else : ?>
                <span class="text-muted">Tidak ada foto dilampirkan.</span>
              <?php endif; ?>
            </div>

            <!-- Form Beri / Ubah Tanggapan -->
            <h5 class="fw-bold mb-3 text-primary">Form Tanggapan Admin</h5>
            <form action="" method="POST">
              <div class="mb-3">
                <label class="form-label fw-bold">Ubah Status Laporan</label>
                <select name="status" class="form-select border px-2 py-1" required>
                  <option value="pending" <?= ($data['status'] == 'pending') ? 'selected' : ''; ?>>Pending (Menunggu)</option>
                  <option value="proses" <?= ($data['status'] == 'proses') ? 'selected' : ''; ?>>Diproses (Tindak Lanjut)</option>
                  <option value="selesai" <?= ($data['status'] == 'selesai') ? 'selected' : ''; ?>>Selesai (Tuntas)</option>
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label fw-bold">Tulis Tanggapan / Solusi</label>
                <textarea name="isi_tanggapan" class="form-control" rows="4" placeholder="Berikan tanggapan, penjelasan, atau solusi atas laporan warga..." required><?= $isi_tanggapan_eks; ?></textarea>
              </div>

              <button type="submit" name="kirim_tanggapan" class="btn btn-primary text-white w-100 py-2">
                <i class="mdi mdi-send"></i> Kirim Tanggapan & Perbarui Status
              </button>
            </form>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>