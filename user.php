<?php
require_once('function.php');
include_once('templates/header.php');
// pengecekan user role bukan admin maka tidak boleh mengakses halaman
if ($_SESSION['role'] != 'admin') {
    echo "<script>alert('anda tidak memiliki akses');</script>";
    echo "<script>window.location.href='index.php';</script>";
    exit;
}

// AUTO GENERATE KODE USER
$query = mysqli_query($koneksi, "SELECT max(id_user) as kodeTerbesar FROM users");
$data = mysqli_fetch_array($query);
$kodeuser = $data['kodeTerbesar'];

$urutan = (int) substr($kodeuser, 3, 2);
$urutan++;

$huruf = "usr";
$kodeuser = $huruf . sprintf("%02s", $urutan);
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Data User</h1>

    <?php
    // Proses Tambah User jika tombol Simpan dipencet
    if (isset($_POST['simpan'])) {
        if (tambah_user($_POST) > 0) {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    Data berhasil disimpan!
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                  </div>';
            echo "<script>window.location.href='user.php';</script>";
        } else {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Data gagal disimpan!
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                  </div>';
        }
    } else if (isset($_POST['ganti_password'])) {
        // Menggunakan >= 0 agar tetap dianggap sukses meski password baru sama dengan password lama
        if (ganti_password($_POST) >= 0) {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    Password berhasil diubah!
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                  </div>';
            echo "<script>
                    setTimeout(function() {
                        window.location.href = 'user.php';
                    }, 1500);
                  </script>";
        } else {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Gagal mengubah password!
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                  </div>';
        }
    }
    ?>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <button type="button" class="btn btn-primary btn-icon-split" data-toggle="modal" data-target="#tambahModal">
                <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="text">Tambah User</span>
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Username</th>
                            <th>User Role</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $users = query("SELECT * FROM users");
                        foreach ($users as $user) : ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= htmlspecialchars($user['username']); ?></td>
                                <td><?= htmlspecialchars($user['user_role']); ?></td>
                                <td>
                                    <button type="button" class="btn btn-info btn-icon-split" data-toggle="modal" data-target="#gantiPassword" data-id="<?= $user['id_user'] ?>">
                                        <span class="text">Ganti Password</span>
                                    </button>
                                    <a class="btn btn-success btn-sm" href="edit-user.php?id=<?= $user['id_user']; ?>">Ubah</a>
                                    <a onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')" class="btn btn-danger btn-sm" href="hapus-user.php?id=<?= $user['id_user']; ?>">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

<!-- Modal Tambah Data User -->
<div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahModalLabel">Tambah Data User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- FORM DIMULAI DI SINI -->
            <form method="post" action="">
                <div class="modal-body">
                    <input type="hidden" name="id_user" id="id_user" value="<?= $kodeuser; ?>" />

                    <div class="form-group row">
                        <label for="username" class="col-sm-3 col-form-label">Username</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="username" name="username" required autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="password" class="col-sm-3 col-form-label">Password</label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="user_role" class="col-sm-3 col-form-label">User Role</label>
                        <div class="col-sm-9">
                            <select class="form-control" id="user_role" name="user_role" required>
                                <option value="admin">Administrator</option>
                                <option value="operator">Operator</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- TOMBOL KONFIRMASI (BATAL & SIMPAN) ADA DI SINI -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
                </div>
            </form>
            <!-- FORM SELESAI DI SINI -->

        </div>
    </div>
</div>

<!-- Modal Ganti Password -->
<div class="modal fade" id="gantiPassword" tabindex="-1" aria-labelledby="gantiPasswordLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="gantiPasswordLabel">Ganti Password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="">
                <div class="modal-body">

                    <input type="hidden" name="id_user" id="id_user">
                    <div class="form-group row">
                        <label for="password" class="col-sm-4 col-form-label">Password Baru</label>
                        <div class="col-sm-7">
                            <input type="password" class="form-control" id="password" name="password">
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                    <button type="submit" name="ganti_password" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
include_once('templates/footer.php');
?>