<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Pengaduan</title>

    <!-- plugins:css -->
    <link rel="stylesheet" href="../assets/vendors/feather/feather.css">
    <link rel="stylesheet" href="../assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="../assets/vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/vendors/typicons/typicons.css">
    <link rel="stylesheet" href="../assets/vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="../assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css">
    
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="../assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css">
    <link rel="stylesheet" type="text/css" href="../assets/js/select.dataTables.min.css">
    
    <!-- inject:css -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="shortcut icon" href="../assets/images/favicon.png" />
</head>
<body>
  <div class="row">
    <div class="col-sm-12">
      <div class="home-tab">
        <div class="d-sm-flex align-items-center justify-content-between border-bottom">
          <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
              <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Ringkasan</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#laporan" role="tab" aria-selected="false">Data Laporan</a>
            </li>
          </ul>
          <div>
            <div class="btn-wrapper">
              <a href="#" class="btn btn-primary text-white me-0"><i class="icon-printer"></i> Cetak Laporan</a>
            </div>
          </div>
        </div>
        <div class="tab-content tab-content-basic">
          <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
            
            <!-- Statistik Pengaduan Atas -->
            <div class="row">
              <div class="col-sm-12">
                <div class="statistics-details d-flex align-items-center justify-content-between">
                  <div>
                    <p class="statistics-title">Total Pengaduan</p>
                    <h3 class="rate-percentage">128</h3>
                    <p class="text-primary d-flex"><i class="mdi mdi-file-document"></i><span>Keseluruhan</span></p>
                  </div>
                  <div>
                    <p class="statistics-title">Menunggu Verifikasi (Pending)</p>
                    <h3 class="rate-percentage">15</h3>
                    <p class="text-danger d-flex"><i class="mdi mdi-alert-circle"></i><span>Perlu dicek</span></p>
                  </div>
                  <div>
                    <p class="statistics-title">Sedang Diproses</p>
                    <h3 class="rate-percentage">35</h3>
                    <p class="text-warning d-flex"><i class="mdi mdi-refresh"></i><span>Tindak Lanjut</span></p>
                  </div>
                  <div class="d-none d-md-block">
                    <p class="statistics-title">Selesai (Resolved)</p>
                    <h3 class="rate-percentage">78</h3>
                    <p class="text-success d-flex"><i class="mdi mdi-check-circle"></i><span>Tuntas</span></p>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-lg-8 d-flex flex-column">
                
                
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>