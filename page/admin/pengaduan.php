<?php
// Ambil data pengaduan dengan JOIN berdasarkan id_masyarakat
$query_pengaduan = "SELECT pengaduan.*, tbl_masyarakat.nama, tbl_masyarakat.nik 
                    FROM pengaduan 
                    JOIN tbl_masyarakat ON pengaduan.id_masyarakat = tbl_masyarakat.id_masyarakat 
                    ORDER BY pengaduan.id_pengaduan DESC";
$data_pengaduan = tampil($query_pengaduan);
?>

<!DOCTYPE html>
<html lang="id">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Data Pengaduan - Admin</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="../../assets/vendors/feather/feather.css">
    <link rel="stylesheet" href="../../assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../../assets/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="../../assets/vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../../assets/vendors/typicons/typicons.css">
    <link rel="stylesheet" href="../../assets/vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="../../assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="../../assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css">
    <!-- inject:css -->
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="shortcut icon" href="../../assets/images/favicon.png" />
  </head>
  <body>
    <div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card card-rounded">
          <div class="card-body">
            <div class="d-sm-flex justify-content-between align-items-start mb-3">
              <div>
                <h4 class="card-title card-title-dash">Kelola Data Pengaduan</h4>
                <p class="card-subtitle card-subtitle-dash">Daftar seluruh laporan yang masuk dari masyarakat</p>
              </div>
            </div>

            <div class="table-responsive">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Pelapor (NIK / Nama)</th>
                    <th>Isi Laporan</th>
                    <th>Bukti Foto</th>
                    <th>Status</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                  $no = 1;
                  // Cek apakah data pengaduan ada
                  if (!empty($data_pengaduan)) :
                      foreach ($data_pengaduan as $row) :
                  ?>
                    <tr>
                      <td><?= $no++; ?></td>
                      <td><?= $row['tgl_pengaduan']; ?></td>
                      <td>
                        <strong><?= $row['nama']; ?></strong><br>
                        <small class="text-muted">NIK: <?= $row['nik']; ?></small>
                      </td>
                      <td>
                        <span style="display: inline-block; max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            <?= $row['isi_laporan']; ?>
                        </span>
                      </td>
                      <td class="py-1">
                        <?php if (!empty($row['foto'])) : ?>
                          <a href="../photo_pengaduan/<?= $row['foto']; ?>" target="_blank">
                            <img src="../photo_pengaduan/<?= $row['foto']; ?>" alt="Bukti Foto" style="width: 50px; height: 50px; border-radius: 8px; object-fit: cover;" />
                          </a>
                        <?php else : ?>
                          <span class="badge badge-opacity-secondary">Tidak ada foto</span>
                        <?php endif; ?>
                      </td>
                      <td>
                        <?php 
                        $status = $row['status'];
                        if ($status == '0') {
                            echo '<div class="badge badge-opacity-danger">Pending</div>';
                        } elseif ($status == 'proses') {
                            echo '<div class="badge badge-opacity-warning">Diproses</div>';
                        } elseif ($status == 'selesai') {
                            echo '<div class="badge badge-opacity-success">Selesai</div>';
                        } else {
                            echo '<div class="badge badge-opacity-secondary">'.$status.'</div>';
                        }
                        ?>
                      </td>
                      <td>
                       <a href="index.php?pages=detail_pengaduan&id=<?= $row['id_pengaduan']; ?>" class="btn btn-sm btn-primary text-white">
  <i class="mdi mdi-eye"></i> Detail / Tanggapi
</a>
                      </td>
                    </tr>
                  <?php 
                      endforeach; 
                  else : 
                  ?>
                    <tr>
                      <td colspan="7" class="text-center text-muted py-4">Belum ada data pengaduan yang masuk.</td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>

          </div>
        </div>
      </div>
    </div>

    <!-- plugins:js -->
    <script src="../../assets/vendors/js/vendor.bundle.base.js"></script>
    <script src="../../assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <!-- inject:js -->
    <script src="../../assets/js/off-canvas.js"></script>
    <script src="../../assets/js/template.js"></script>
    <script src="../../assets/js/settings.js"></script>
    <script src="../../assets/js/hoverable-collapse.js"></script>
    <script src="../../assets/js/todolist.js"></script>
  </body>
</html>