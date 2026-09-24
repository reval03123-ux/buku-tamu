<?php
require_once('function.php');

// Ambil id dari URL
$id_user = $_GET['id'];

// Ambil data user berdasarkan id_user dari tabel users
$data = query("SELECT * FROM users WHERE id_user = '$id_user'")[0];

// Jika tombol simpan dipencet
if (isset($_POST['simpan'])) {
    if (ubah_user($_POST) >= 0) {
        echo "<script>
                alert('Data user berhasil diubah!');
                document.location.href = 'user.php';
              </script>";
    } else {
        echo "<script>
                alert('Data user gagal diubah!');
                document.location.href = 'user.php';
              </script>";
    }
}

include_once('templates/header.php');
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Ubah Data User</h1>

    <!-- Konten Edit Data User -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Edit User</h6>
        </div>
        <div class="card-body">
            <form method="post" action="">
                <!-- Input Hidden ID User -->
                <input type="hidden" name="id_user" id="id_user" value="<?= $data['id_user']; ?>">

                <!-- Field Username -->
                <div class="form-group row">
                    <label for="username" class="col-sm-3 col-form-label">Username</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="username" name="username" value="<?= $data['username']; ?>" required autocomplete="off">
                    </div>
                </div>

                <!-- Field User Role -->
                <div class="form-group row">
                    <label for="user_role" class="col-sm-3 col-form-label">User Role</label>
                    <div class="col-sm-8">
                        <select class="form-control" id="user_role" name="user_role" required>
                            <option value="admin" <?= ($data['user_role'] == 'admin') ? 'selected' : ''; ?>>Administrator</option>
                            <option value="operator" <?= ($data['user_role'] == 'operator') ? 'selected' : ''; ?>>Operator</option>
                        </select>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="form-group row">
                    <div class="col-sm-11 d-flex justify-content-end">
                        <a class="btn btn-danger mr-2" href="user.php">
                            <i class="fas fa-chevron-left mr-1"></i> Kembali
                        </a>
                        <button type="submit" name="simpan" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Simpan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

<?php
include_once('templates/footer.php');
?>