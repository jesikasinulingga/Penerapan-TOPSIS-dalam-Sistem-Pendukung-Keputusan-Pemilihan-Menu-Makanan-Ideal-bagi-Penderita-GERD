<?php
if (session_status() === PHP_SESSION_NONE) { 
    session_start(); // Pastikan sesi hanya dimulai sekali
}
ob_start(); // Buffer output untuk mencegah error header
require_once(__DIR__ . '/../includes/init.php');
cek_login($role = array(1));
?>

<?php
$page = "Permintaan";
require_once('../template/header.php');
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="fa-solid fa-inbox"></i> Data Permintaan</h1>
</div>

<?php
$status = isset($_GET['status']) ? $_GET['status'] : '';
$msg = '';
switch ($status):
    case 'sukses-approve':
        $msg = 'Permintaan berhasil disetujui dan ditambahkan ke daftar alternatif.';
        break;
    case 'sukses-reject':
        $msg = 'Permintaan berhasil ditolak.';
        break;
    case 'error':
        $msg = 'Terjadi kesalahan saat memproses data.';
        break;
endswitch;

if ($msg) :
    echo '<div class="alert alert-info">' . $msg . '</div>';
endif;

// Proses POST untuk Setujui atau Tolak Permintaan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $menuId = (int)$_POST['menu_id'];
    $action = $_POST['action'];

    if ($menuId <= 0) {
        header("Location: list-permintaan.php?status=error");
        exit();
    }

    if ($action === 'approve') {
        // Tambahkan menu ke tabel `alternatif`
        $query = "INSERT INTO alternatif (alternatif) 
                  SELECT menu FROM permintaan_menu WHERE id = $menuId";
        if (!mysqli_query($koneksi, $query)) {
            header("Location: list-permintaan.php?status=error");
            exit();
        }

        // Perbarui status menjadi 'approved'
        $updateQuery = "UPDATE permintaan_menu SET status = 'approved' WHERE id = $menuId";
        if (!mysqli_query($koneksi, $updateQuery)) {
            header("Location: list-permintaan.php?status=error");
            exit();
        }

        header("Location: list-permintaan.php?status=sukses-approve");
        exit();
    } elseif ($action === 'rejected') {
        // Perbarui status menjadi 'rejected'
        $updateQuery = "UPDATE permintaan_menu SET status = 'rejected' WHERE id = $menuId";
        if (!mysqli_query($koneksi, $updateQuery)) {
            header("Location: list-permintaan.php?status=error");
            exit();
        }

        header("Location: list-permintaan.php?status=sukses-reject");
        exit();
    }
}

// Ambil semua permintaan dari tabel `permintaan_menu`
$query = "SELECT id, menu, tanggal, status FROM permintaan_menu ORDER BY tanggal DESC";
$result = mysqli_query($koneksi, $query);
?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold"><i class="fa fa-table"></i> Daftar Data Permintaan</h6>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead class="bg-primary text-white">
                    <tr align="center">
                        <th width="5%">No</th>
                        <th width="20%">Nama Menu</th>
                        <th width="20%">Tanggal Permintaan</th>
                        <th width="10%">Status</th>
                        <th width="20%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($result)) :
                        $statusIcon = '';
                        $statusText = '';
                        $statusClass = '';
                        if ($row['status'] === 'pending') {
                            $statusIcon = '<i class="fas fa-hourglass-half text-warning"></i>';
                            $statusText = 'Pending';
                            $statusClass = 'text-warning';
                        } elseif ($row['status'] === 'approved') {
                            $statusIcon = '<i class="fas fa-check-circle text-success"></i>';
                            $statusText = 'Approved';
                            $statusClass = 'text-success';
                        } elseif ($row['status'] === 'rejected') {
                            $statusIcon = '<i class="fas fa-times-circle text-danger"></i>';
                            $statusText = 'Rejected';
                            $statusClass = 'text-danger';
                        }
                    ?>
                        <tr align="center">
                            <td><?= $no++; ?></td>
                            <td align="left"><?= htmlspecialchars($row['menu']); ?></td>
                            <td><?= htmlspecialchars($row['tanggal']); ?></td>
                            <td class="<?= $statusClass; ?>">
                                <?= $statusIcon; ?> <?= $statusText; ?>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <?php if ($row['status'] === 'pending') : ?>
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="menu_id" value="<?= $row['id']; ?>">
                                            <button type="submit" name="action" value="approve" class="btn btn-success btn-sm">
                                                <i class="fas fa-check"></i> Setujui
                                            </button>
                                        </form>
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="menu_id" value="<?= $row['id']; ?>">
                                            <button type="submit" name="action" value="rejected" class="btn btn-danger btn-sm">
                                                <i class="fas fa-times"></i> Tolak
                                            </button>
                                        </form>
                                    <?php else : ?>
                                        <button class="btn btn-secondary btn-sm" disabled>
                                            <i class="fas fa-lock"></i> Tidak Tersedia
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
require_once('../template/footer.php');
ob_end_flush(); // Kirim semua output setelah selesai
?>
