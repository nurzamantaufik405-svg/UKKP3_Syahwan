<?php
//+=+=========================================+=+
// Aplikasi Pengaduan Masyarakat
// File ini menampung koneksi dan semua fungsi.
//+=+=========================================+=+

date_default_timezone_set('Asia/Jakarta');

//+=+=========================================+=+
// 1. KONEKSI DATABASE (Dari file sebelumnya)
//+=+=========================================+=+
$SERVERNAME = "localhost";
$USERNAME   = "root";
$PASSWORD   = "";
$DATABASE   = "db_apk_pengaduan"; // Disesuaikan dengan nama DB baru

// Contoh penulisan yang benar (tanpa spasi pada nama database)
$CONN = mysqli_connect("localhost", "root", "", "db_apk_pengaduan");

if (!$CONN) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

//+=+=========================================+=+
// 2. FUNGSI TAMPIL DATA (Didaur ulang)
//+=+=========================================+=+
function tampil($DATA)
{
    global $CONN;
    $QUERY = mysqli_query($CONN, $DATA) or die('Error: ' . mysqli_error($CONN));
    $data = []; 
    
    while ($ROW = mysqli_fetch_assoc($QUERY)) {
        $data[] = $ROW; 
    }
    return $data; 
}

//+=+=========================================+=+
// 3. FUNGSI UPLOAD FILE (Didaur ulang untuk foto bukti)
//+=+=========================================+=+
function upload_files($FILE_INPUT, $IDENTIFIER, $TARGET_FOLDER = '', $OPTION = [])
{
    $DEFAULT = [
        'max_size' => 50 * 1024 * 1024,
        'allowed_types' => ['jpg', 'png', 'gif', 'bmp', 'jpeg'],
        'create_folder' => true,
        'prefix' => ''
    ];

    $OPTIONS = array_merge($DEFAULT, $OPTION);

    if (!isset($FILE_INPUT['error']) || !isset($FILE_INPUT['tmp_name'])) {
        return ['success' => false, 'message' => 'File yang diupload tidak valid.'];
    }

    $ERRORS     = $FILE_INPUT['error'];
    $TMPNAME    = $FILE_INPUT['tmp_name'];
    $NAMAASLI   = $FILE_INPUT['name'];
    $UKURANFILE = $FILE_INPUT['size'];

    if ($ERRORS === UPLOAD_ERR_NO_FILE) {
        return ['success' => false, 'message' => 'Tidak ada file yang diupload.'];
    }

    $EKSTENSIFILE = strtolower(pathinfo($NAMAASLI, PATHINFO_EXTENSION));

    if (!in_array($EKSTENSIFILE, $OPTIONS['allowed_types'])) {
        return ['success' => false, 'message' => "Ekstensi file tidak diizinkan."];
    }

    if ($UKURANFILE > $OPTIONS['max_size']) {
        return ['success' => false, 'message' => "Ukuran file melebihi batas maksimal."];
    }

    $PREFIX       = $OPTIONS['prefix'] ? $OPTIONS['prefix'] . '_' : '';
    $UNIQUE_ID    = uniqid();
    $TIMESTAMP    = date('Ymd_His');
    $NAMAFILEBARU = $PREFIX . $IDENTIFIER . '_' . $TIMESTAMP . '_' . $UNIQUE_ID . '.' . $EKSTENSIFILE;

    $TARGET_FOLDER_BERSIH = rtrim($TARGET_FOLDER, '/\\');
    if (!file_exists($TARGET_FOLDER_BERSIH)) {
        mkdir($TARGET_FOLDER_BERSIH, 0777, true);
    }

    $FILE_PATH = $TARGET_FOLDER_BERSIH . DIRECTORY_SEPARATOR . $NAMAFILEBARU;

    if (move_uploaded_file($TMPNAME, $FILE_PATH)) {
        return [
            'success' => true,
            'Filename_tersimpan' => $NAMAFILEBARU
        ];
    } else {
        return ['success' => false, 'message' => 'Gagal memindahkan file.'];
    }
}

//+=+=========================================+=+
// 4. FUNGSI REGISTRASI MASYARAKAT (Adaptasi konsep transaksi dari file lama)
//+=+=========================================+=+
function registrasi_masyarakat($DATA) {
    global $CONN;

    // Bersihkan input
    $NIK      = htmlspecialchars(trim($DATA["nik"]));
    $NAMA     = htmlspecialchars(trim($DATA["nama"]));
    $TELEPON  = htmlspecialchars(trim($DATA["telepon"]));
    $EMAIL    = strtolower(htmlspecialchars(trim($DATA["email"])));
    $PASSWORD = mysqli_real_escape_string($CONN, $DATA["password"]);

    // Cek apakah email sudah ada di tbl_users
    $CEK_EMAIL = mysqli_query($CONN, "SELECT email FROM tbl_users WHERE email = '$EMAIL'");
    if (mysqli_fetch_assoc($CEK_EMAIL)) {
        echo "<script>alert('Email sudah terdaftar!');</script>";
        return false;
    }

    // Hash Password
    $PASSWORD_HASH = password_hash($PASSWORD, PASSWORD_DEFAULT);
    
    // Mulai Transaksi (Mencegah data menggantung kalau satu tabel gagal insert)
    mysqli_begin_transaction($CONN);

    try {
        // 1. Insert ke tabel akun (Role 2 = Masyarakat)
        $SQL_USER = "INSERT INTO tbl_users (email, password, role) VALUES ('$EMAIL', '$PASSWORD_HASH', 2)";
        if (!mysqli_query($CONN, $SQL_USER)) {
            throw new Exception("Gagal membuat akun.");
        }

        // Ambil ID User yang baru saja terbuat
        $ID_USER_BARU = mysqli_insert_id($CONN);

        // 2. Insert ke tabel profil masyarakat
        $SQL_MASYARAKAT = "INSERT INTO tbl_masyarakat (id_users, nik, nama, telepon) 
                           VALUES ('$ID_USER_BARU', '$NIK', '$NAMA', '$TELEPON')";
        if (!mysqli_query($CONN, $SQL_MASYARAKAT)) {
            throw new Exception("Gagal menyimpan data profil masyarakat.");
        }

        // Jika dua-duanya sukses, simpan permanen
        mysqli_commit($CONN);
        return true;

    } catch (Exception $e) {
        mysqli_rollback($CONN);
        echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
        return false;
    }
}

//+=+=========================================+=+
// 5. FUNGSI TAMBAH PENGADUAN (Untuk Masyarakat)
//+=+=========================================+=+
function tambah_pengaduan($DATA, $FILES, $ID_MASYARAKAT) {
    global $CONN;

    $ISI_LAPORAN = htmlspecialchars(trim($DATA["isi_laporan"]));
    $TGL_SEKARANG = date('Y-m-d H:i:s');
    $FOTO_BUKTI = "";

    // Jika masyarakat mengupload foto bukti
    if (isset($FILES['foto']) && $FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {
        $UPLOAD_RESULT = upload_files($FILES['foto'], 'LAPOR', 'uploads/pengaduan/', ['prefix' => 'IMG']);
        
        if ($UPLOAD_RESULT['success']) {
            $FOTO_BUKTI = $UPLOAD_RESULT['Filename_tersimpan'];
        } else {
            echo "<script>alert('Upload Foto Gagal: " . $UPLOAD_RESULT['message'] . "');</script>";
            return false;
        }
    }

    $QUERY = "INSERT INTO pengaduan (id_masyarakat, tgl_pengaduan, isi_laporan, foto, status) 
              VALUES ('$ID_MASYARAKAT', '$TGL_SEKARANG', '$ISI_LAPORAN', '$FOTO_BUKTI', 'pending')";

    mysqli_query($CONN, $QUERY);
    return mysqli_affected_rows($CONN);
}

//+=+=========================================+=+
// 6. FUNGSI BERI TANGGAPAN (Untuk Admin)
//+=+=========================================+=+
function beri_tanggapan($DATA, $ID_ADMIN) {
    global $CONN;

    $ID_PENGADUAN  = htmlspecialchars(trim($DATA["id_pengaduan"]));
    $ISI_TANGGAPAN = htmlspecialchars(trim($DATA["isi_tanggapan"]));
    $TGL_SEKARANG  = date('Y-m-d H:i:s');
    
    // Status baru (misal: otomatis menjadi 'selesai' setelah ditanggapi)
    $STATUS_BARU = "selesai"; 

    mysqli_begin_transaction($CONN);

    try {
        // 1. Insert balasan ke tabel tanggapan
        $SQL_TANGGAPAN = "INSERT INTO tanggapan (id_pengaduan, id_admin, tgl_tanggapan, isi_tanggapan) 
                          VALUES ('$ID_PENGADUAN', '$ID_ADMIN', '$TGL_SEKARANG', '$ISI_TANGGAPAN')";
        if (!mysqli_query($CONN, $SQL_TANGGAPAN)) {
            throw new Exception("Gagal menyimpan tanggapan.");
        }

        // 2. Update status laporan di tabel pengaduan
        $SQL_UPDATE_STATUS = "UPDATE pengaduan SET status = '$STATUS_BARU' WHERE id_pengaduan = '$ID_PENGADUAN'";
        if (!mysqli_query($CONN, $SQL_UPDATE_STATUS)) {
            throw new Exception("Gagal update status laporan.");
        }

        mysqli_commit($CONN);
        return true;

    } catch (Exception $e) {
        mysqli_rollback($CONN);
        echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
        return false;
    }
}
?>