<?php
@$page = $_GET['pages'];

switch ($page) {
    case 'pengaduan':
        include __DIR__ . '/../page/admin/pengaduan.php';
        break;
        
    case 'detail_pengaduan':
        include __DIR__ . '/../page/admin/detail_pengaduan.php';
        break;
        
    case 'masyarakat':
        include __DIR__ . '/../page/admin/masyarakat.php';
        break;
        
    case 'laporan':
        include __DIR__ . '/../page/admin/laporan.php';
        break;

    default:
        include __DIR__ . '/../page/master/dashboard.php';
        break;
}
?>