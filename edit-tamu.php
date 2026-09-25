<?php
require_once('function.php');
include_once('templates/header.php');

// Pengecekan user role bukan operator maka tidak boleh mengakses halaman
if ($_SESSION['role'] != 'operator') {
    echo "<script>alert('Anda tidak memiliki akses');</script>";
    echo "<script>window.location.href='index.php';</script>";
    exit;
}

// Ambil ID dari URL
$id = $_GET['id'];

// Query data tamu berdasarkan ID
$tamu = query("SELECT * FROM buku_tamu WHERE id_tamu = '$id'")[0];

// Cek apakah tombol simpan/ubah sudah ditekan
if (isset($_POST['simpan'])) {
    if (ubah_tamu($_POST) > 0) {
        echo "<script>
                alert('Data berhasil diubah!');
                window.location.href='buku-tamu.php';
              </script>";
    } else {
        echo "<script>
                alert('Data gagal diubah atau tidak ada perubahan!');
                window.location.href='buku-tamu.php';
              </script>";
    }
}
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Ubah Data Tamu</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Ubah Tamu</h6>
        </div>
        <div class="card-body">
            <!-- PENTING: Wajib tambahkan enctype="multipart/form-data" -->
            <form method="post" action="" enctype="multipart/form-data">
                <!-- Input hidden untuk ID dan Gambar Lama -->
                <input type="hidden" name="id_tamu" value="<?= $tamu['id_tamu']; ?>">
                <input type="hidden" name="gambarLama" value="<?= $tamu['gambar']; ?>">

                <div class="form-group row">
                    <label for="nama_tamu" class="col-sm-2 col-form-label">Nama Tamu</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="nama_tamu" name="nama_tamu" value="<?= $tamu['nama_tamu']; ?>" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" id="alamat" name="alamat" required><?= $tamu['alamat']; ?></textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="no_hp" class="col-sm-2 col-form-label">No. Telepon</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="no_hp" name="no_hp" value="<?= $tamu['no_hp']; ?>" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="bertemu" class="col-sm-2 col-form-label">Bertemu Dg.</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="bertemu" name="bertemu" value="<?= $tamu['bertemu']; ?>" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="kepentingan" class="col-sm-2 col-form-label">Kepentingan</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="kepentingan" name="kepentingan" value="<?= $tamu['kepentingan']; ?>" required>
                    </div>
                </div>

                <!-- Tampilan Gambar Saat Ini & Input Upload Gambar Baru -->
                <div class="form-group row">
                    <label for="gambar" class="col-sm-2 col-form-label">Foto Tamu</label>
                    <div class="col-sm-10">
                        <div class="mb-2">
                            <?php if (!empty($tamu['gambar']) && file_exists('assets/upload_gambar/' . $tamu['gambar'])) : ?>
                                <img src="assets/upload_gambar/<?= $tamu['gambar']; ?>" width="100" class="img-thumbnail">
                            <?php else : ?>
                                <span class="badge badge-secondary">Tidak ada foto</span>
                            <?php endif; ?>
                        </div>
                        <div class="custom-file">
                            <!-- Tidak pakai required agar user bisa memilih untuk tidak mengganti foto -->
                            <input type="file" class="custom-file-input" id="gambar" name="gambar">
                            <label class="custom-file-label" for="gambar">Pilih gambar baru jika ingin mengganti...</label>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-10 offset-sm-2">
                        <a href="buku-tamu.php" class="btn btn-secondary">Kembali</a>
                        <button type="submit" name="simpan" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

<script>
    // Script agar nama file baru tampil di custom input bootstrap
    document.querySelector('.custom-file-input').addEventListener('change', function(e) {
        var fileName = document.getElementById("gambar").files[0].name;
        var nextSibling = e.target.nextElementSibling;
        nextSibling.innerText = fileName;
    });
</script>

<?php
include_once('templates/footer.php');
?>