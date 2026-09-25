<?php
require_once('function.php');
include_once('templates/header.php');

// Pengecekan role operator
if ($_SESSION['role'] != 'operator') {
    echo "<script>alert('anda tidak memiliki akses');</script>";
    echo "<script>window.location.href='index.php';</script>";
    exit;
}

// Ambil ID dari URL
$id = $_GET['id'];
$tamu = query("SELECT * FROM buku_tamu WHERE id_tamu = '$id'")[0];

// Proses update data
if (isset($_POST['ubah'])) {
    if (ubah_tamu($_POST) > 0) {
        echo "<script>
                alert('Data berhasil diubah!');
                window.location.href='buku-tamu.php';
              </script>";
    } else {
        echo "<script>
                alert('Data gagal diubah!');
              </script>";
    }
}
?>

<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Ubah Data Tamu</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Ubah Data Tamu</h6>
        </div>
        <div class="card-body">
            <form method="post" action="" enctype="multipart/form-data">
                <input type="hidden" name="id_tamu" value="<?= $tamu['id_tamu']; ?>">
                <input type="hidden" name="gambarLama" value="<?= $tamu['gambar']; ?>">

                <div class="form-group row">
                    <label for="nama_tamu" class="col-sm-3 col-form-label">Nama Tamu</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="nama_tamu" name="nama_tamu" value="<?= $tamu['nama_tamu']; ?>" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="alamat" class="col-sm-3 col-form-label">Alamat</label>
                    <div class="col-sm-8">
                        <textarea class="form-control" id="alamat" name="alamat" required><?= $tamu['alamat']; ?></textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="no_hp" class="col-sm-3 col-form-label">No. Telepon</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="no_hp" name="no_hp" value="<?= $tamu['no_hp']; ?>" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="bertemu" class="col-sm-3 col-form-label">Bertemu dg.</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="bertemu" name="bertemu" value="<?= $tamu['bertemu']; ?>" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="kepentingan" class="col-sm-3 col-form-label">Kepentingan</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="kepentingan" name="kepentingan" value="<?= $tamu['kepentingan']; ?>" required>
                    </div>
                </div>

                <!-- Bagian Foto Tamu (Layout Ke Bawah) -->
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Foto Saat Ini</label>
                    <div class="col-sm-8">
                        <div class="mb-2">
                            <?php if (!empty($tamu['gambar']) && file_exists('assets/upload_gambar/' . $tamu['gambar'])) : ?>
                                <img src="assets/upload_gambar/<?= $tamu['gambar']; ?>" id="imgPreview" class="img-thumbnail" style="max-height: 180px;">
                            <?php else : ?>
                                <span class="badge badge-secondary p-2">Belum ada foto</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="gambar" class="col-sm-3 col-form-label">Ubah Foto</label>
                    <div class="col-sm-8">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="gambar" name="gambar" onchange="previewImage()">
                            <label class="custom-file-label" for="gambar">Pilih file baru jika ingin mengganti...</label>
                        </div>
                        <small class="form-text text-muted">Abaikan jika tidak ingin mengubah foto.</small>
                    </div>
                </div>

                <div class="form-group row mt-4">
                    <div class="col-sm-8 offset-sm-3">
                        <a href="buku-tamu.php" class="btn btn-secondary">Batal</a>
                        <button type="submit" name="ubah" class="btn btn-success">Simpan Perubahan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    function previewImage() {
        const gambar = document.querySelector('#gambar');
        const imgPreview = document.querySelector('#imgPreview');
        const label = document.querySelector('.custom-file-label');

        if (gambar.files && gambar.files[0]) {
            label.textContent = gambar.files[0].name;

            const fileGambar = new FileReader();
            fileGambar.readAsDataURL(gambar.files[0]);

            fileGambar.onload = function(e) {
                if (imgPreview) {
                    imgPreview.src = e.target.result;
                }
            }
        }
    }
</script>

<?php
include_once('templates/footer.php');
?>