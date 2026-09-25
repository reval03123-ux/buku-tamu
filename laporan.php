<?php
require_once 'function.php';
include 'templates/header.php';

// Logika filter tanggal dan pembuatan link export
if (isset($_POST['tampilkan'])) {
    $p_awal = $_POST['p_awal'];
    $p_akhir = $_POST['p_akhir'];

    // Ambil data berdasarkan periode yang dipilih
    $buku_tamu = query("SELECT * FROM buku_tamu WHERE tanggal BETWEEN '$p_awal' AND '$p_akhir' ORDER BY tanggal DESC");

    // Link export dengan parameter tanggal
    $link = "export-laporan.php?p_awal=$p_awal&p_akhir=$p_akhir";
} else {
    // Tampilkan semua data jika tombol filter belum diklik
    $buku_tamu = query("SELECT * FROM buku_tamu ORDER BY tanggal DESC");

    // Link export default (tanpa filter)
    $link = "export-laporan.php";
}
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Laporan Tamu</h1>

    <!-- Form Filter Periode (Sejajar ke samping & tanpa ikon) -->
    <div class="row mx-auto d-flex justify-content-center">
        <div class="col-xl-8 col-md-10 mb-4">
            <div class="card border-left-primary shadow h-100 py-3">
                <div class="card-body">
                    <form method="post" action="">
                        <div class="form-row align-items-center justify-content-center">

                            <div class="col-auto">
                                <label class="font-weight-bold text-primary text-uppercase mb-0">Periode</label>
                            </div>

                            <div class="col-auto">
                                <input type="date" class="form-control" id="p_awal" name="p_awal" value="<?= isset($_POST['p_awal']) ? $_POST['p_awal'] : ''; ?>" required>
                            </div>

                            <div class="col-auto">
                                <span class="font-weight-bold text-primary">s.d</span>
                            </div>

                            <div class="col-auto">
                                <input type="date" class="form-control" id="p_akhir" name="p_akhir" value="<?= isset($_POST['p_akhir']) ? $_POST['p_akhir'] : ''; ?>" required>
                            </div>

                            <div class="col-auto">
                                <button type="submit" name="tampilkan" class="btn btn-primary">Tampilkan</button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Tombol Export Laporan -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <a href="<?= isset($_POST['tampilkan']) ? $link : 'export-laporan.php'; ?>" target="_blank" class="btn btn-success btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-file-excel"></i>
                </span>
                <span class="text">Export Laporan</span>
            </a>
            <?php if (isset($_POST['tampilkan'])) : ?>
                <a href="laporan.php" class="btn btn-secondary btn-sm">Reset Filter</a>
            <?php endif; ?>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Nama Tamu</th>
                            <th>Alamat</th>
                            <th>No. Telp/HP</th>
                            <th>Bertemu Dengan</th>
                            <th>Kepentingan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        if (!empty($buku_tamu)) :
                            foreach ($buku_tamu as $tamu) :
                        ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $tamu['tanggal']; ?></td>
                                    <td><?= $tamu['nama_tamu']; ?></td>
                                    <td><?= $tamu['alamat']; ?></td>
                                    <td><?= $tamu['no_hp']; ?></td>
                                    <td><?= $tamu['bertemu']; ?></td>
                                    <td><?= $tamu['kepentingan']; ?></td>
                                    <td>
                                        <a class="btn btn-success btn-sm" href="edit-tamu.php?id=<?= $tamu['id_tamu']; ?>">Ubah</a>
                                        <a onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')" class="btn btn-danger btn-sm" href="hapus-tamu.php?id=<?= $tamu['id_tamu']; ?>">Hapus</a>
                                    </td>
                                </tr>
                            <?php
                            endforeach;
                        else :
                            ?>
                            <tr>
                                <td colspan="8" class="text-center">Data tamu tidak ditemukan pada periode ini.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

<?php include 'templates/footer.php'; ?>