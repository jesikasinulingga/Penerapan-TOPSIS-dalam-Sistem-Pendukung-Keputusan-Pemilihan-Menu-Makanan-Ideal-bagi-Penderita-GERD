<?php
require_once(__DIR__ . '/../includes/init.php');
cek_login($role = array(1));
$page = "Sub Kriteria";
require_once('../template/header.php');

if (isset($_POST['tambah'])) :
    $id_kriteria = $_POST['id_kriteria'];
    $nama = $_POST['nama'];
    $min_value = $_POST['min_value'];
    $max_value = $_POST['max_value'];
    $nilai = $_POST['nilai'];

    if (!$id_kriteria) {
        $errors[] = 'ID kriteria tidak boleh kosong.';
    }
    if (!$nama) {
        $errors[] = 'Nama sub kriteria tidak boleh kosong.';
    }
    if (!$min_value || !$max_value) {
        $errors[] = 'Range nilai (min dan max) tidak boleh kosong.';
    }
    if (!$nilai) {
        $errors[] = 'Nilai sub kriteria tidak boleh kosong.';
    }

    if (empty($errors)) :
        $simpan = mysqli_query($koneksi, "INSERT INTO sub_kriteria (id_sub_kriteria, id_kriteria, sub_kriteria, min_value, max_value, nilai) 
        VALUES ('', '$id_kriteria', '$nama', '$min_value', '$max_value', '$nilai')");

        if ($simpan) {
            $sts[] = 'Data berhasil disimpan.';
        } else {
            $sts[] = 'Data gagal disimpan.';
        }
    endif;
endif;

if (isset($_POST['edit'])) :
    $id_sub_kriteria = $_POST['id_sub_kriteria'];
    $id_kriteria = $_POST['id_kriteria'];
    $nama = $_POST['nama'];
    $min_value = $_POST['min_value'];
    $max_value = $_POST['max_value'];
    $nilai = $_POST['nilai'];

    if (!$id_kriteria) {
        $errors[] = 'ID kriteria tidak boleh kosong.';
    }
    if (!$nama) {
        $errors[] = 'Nama sub kriteria tidak boleh kosong.';
    }
    if (!$min_value || !$max_value) {
        $errors[] = 'Range nilai (min dan max) tidak boleh kosong.';
    }
    if (!$nilai) {
        $errors[] = 'Nilai sub kriteria tidak boleh kosong.';
    }

    if (empty($errors)) :
        $update = mysqli_query($koneksi, "UPDATE sub_kriteria 
        SET sub_kriteria = '$nama', min_value = '$min_value', max_value = '$max_value', nilai = '$nilai' 
        WHERE id_kriteria = '$id_kriteria' AND id_sub_kriteria = '$id_sub_kriteria'");

        if ($update) {
            $sts[] = 'Data berhasil diupdate.';
        } else {
            $sts[] = 'Data gagal diupdate.';
        }
    endif;
endif;
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="fa-regular fa-lemon"></i> Data Sub Kriteria</h1>
</div>

<?php if (!empty($errors)) : ?>
    <div class="alert alert-info">
        <?php foreach ($errors as $error) : ?>
            <?php echo $error; ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if (!empty($sts)) : ?>
    <div class="alert alert-info">
        <?php foreach ($sts as $st) : ?>
            <?php echo $st; ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php
$query = mysqli_query($koneksi, "SELECT * FROM kriteria WHERE ada_pilihan='1' ORDER BY id_kriteria ASC");
$cek = mysqli_num_rows($query);

if ($cek <= 0) {
?>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold"><i class="fa fa-table"></i> Daftar Data Sub Kriteria</h6>
        </div>
        <div class="card-body">
            <div class="alert alert-info">Tidak ada data kriteria dengan pilihan sub kriteria.</div>
        </div>
    </div>
<?php
} else {
    while ($data = mysqli_fetch_array($query)) {
?>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold"><i class="fa fa-table"></i> <?= $data['kriteria'] . " (" . $data['kode_kriteria'] . ")" ?></h6>
                    <a href="#tambah<?= $data['id_kriteria']; ?>" data-toggle="modal" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Tambah Data</a>
                </div>
            </div>

            <div class="modal fade" id="tambah<?= $data['id_kriteria']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="myModalLabel"><i class="fa fa-plus"></i> Tambah Sub Kriteria</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        </div>
                        <form action="" method="post">
                            <div class="modal-body">
                                <input type="hidden" name="id_kriteria" value="<?= $data['id_kriteria']; ?>">
                                <div class="form-group">
                                    <label class="font-weight-bold">Nama Sub Kriteria</label>
                                    <input type="text" name="nama" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label class="font-weight-bold">Min Value</label>
                                    <input type="number" name="min_value" class="form-control" step="0.01" required>
                                </div>
                                <div class="form-group">
                                    <label class="font-weight-bold">Max Value</label>
                                    <input type="number" name="max_value" class="form-control" step="0.01" required>
                                </div>
                                <div class="form-group">
                                    <label class="font-weight-bold">Nilai</label>
                                    <input type="number" name="nilai" class="form-control" step="0.01" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-warning" data-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
                                <button type="submit" name="tambah" class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead class="bg-primary text-white">
                            <tr align="center">
                                <th width="5%">No</th>
                                <th>Nama Sub Kriteria</th>
                                <th>Range</th>
                                <th>Nilai</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $sub_query = mysqli_query($koneksi, "SELECT * FROM sub_kriteria WHERE id_kriteria = '{$data['id_kriteria']}' ORDER BY nilai DESC");
                            while ($sub = mysqli_fetch_array($sub_query)) {
                                $range = "{$sub['min_value']} - {$sub['max_value']}";
                            ?>
                                <tr align="center">
                                    <td><?= $no++; ?></td>
                                    <td><?= $sub['sub_kriteria']; ?></td>
                                    <td><?= $range; ?></td>
                                    <td><?= $sub['nilai']; ?></td>
                                    <td>
                                        <a href="#edit<?= $sub['id_sub_kriteria']; ?>" data-toggle="modal" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>
                                        <a href="hapus-sub-kriteria.php?id=<?= $sub['id_sub_kriteria']; ?>" onclick="return confirm('Hapus sub kriteria ini?')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>

                                <!-- Modal Edit -->
                                <div class="modal fade" id="edit<?= $sub['id_sub_kriteria']; ?>" tabindex="-1" role="dialog" aria-labelledby="editLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editLabel"><i class="fa fa-edit"></i> Edit Sub Kriteria</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            </div>
                                            <form action="" method="post">
                                                <input type="hidden" name="id_sub_kriteria" value="<?= $sub['id_sub_kriteria']; ?>">
                                                <input type="hidden" name="id_kriteria" value="<?= $sub['id_kriteria']; ?>">
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label class="font-weight-bold">Nama Sub Kriteria</label>
                                                        <input type="text" name="nama" class="form-control" value="<?= $sub['sub_kriteria']; ?>" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="font-weight-bold">Min Value</label>
                                                        <input type="number" name="min_value" class="form-control" value="<?= $sub['min_value']; ?>" step="0.01" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="font-weight-bold">Max Value</label>
                                                        <input type="number" name="max_value" class="form-control" value="<?= $sub['max_value']; ?>" step="0.01" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="font-weight-bold">Nilai</label>
                                                        <input type="number" name="nilai" class="form-control" value="<?= $sub['nilai']; ?>" step="0.01" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-warning" data-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
                                                    <button type="submit" name="edit" class="btn btn-success"><i class="fa fa-save"></i> Update</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
<?php
    }
}
require_once('../template/footer.php');
?>
