<?php
require_once(__DIR__ . '/../includes/init.php');
cek_login($role = array(1)); // Validasi login untuk role tertentu

$ada_error = false; // Inisialisasi variabel untuk error handling
$result = ''; // Inisialisasi variabel hasil

// Ambil ID alternatif dari parameter URL
$id_alternatif = isset($_GET['id']) ? trim($_GET['id']) : '';

if (!$id_alternatif) {
    // Jika ID alternatif kosong atau tidak valid
    $ada_error = 'ID alternatif tidak ditemukan. Penghapusan tidak dapat dilakukan.';
} else {
    // Periksa apakah data alternatif ada di database
    $query = mysqli_query($koneksi, "SELECT * FROM alternatif WHERE id_alternatif = '$id_alternatif'");
    $cek = mysqli_num_rows($query);

    if ($cek <= 0) {
        // Jika data tidak ditemukan di database
        $ada_error = 'Data alternatif tidak ditemukan.';
    } else {
        // Hapus data dari tabel alternatif
        $delete_alternatif = mysqli_query($koneksi, "DELETE FROM alternatif WHERE id_alternatif = '$id_alternatif'");
        
        // Hapus data terkait di tabel penilaian
        $delete_penilaian = mysqli_query($koneksi, "DELETE FROM penilaian WHERE id_alternatif = '$id_alternatif'");

        if ($delete_alternatif) {
            // Jika penghapusan berhasil, redirect ke halaman daftar alternatif
            redirect_to('list-alternatif.php?status=sukses-hapus');
        } else {
            // Jika penghapusan gagal
            $ada_error = 'Terjadi kesalahan saat menghapus data. Silakan coba lagi.';
        }
    }
}
?>

<?php
$page = "Alternatif";
require_once('../template/header.php');
?>

<!-- Tampilkan pesan error jika ada -->
<?php if ($ada_error): ?>
    <div class="alert alert-danger"><?= $ada_error ?></div>
<?php endif; ?>

<?php
require_once('../template/footer.php');
?>
