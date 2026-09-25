<?php
// panggil file koneksi.php
require_once('koneksi.php');

// membuat query ke / dari database
function query($query)
{
    global $koneksi;
    $result = mysqli_query($koneksi, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

// ==================== FUNCTION DATA TAMU ====================

// function tambah data tamu
function tambah_tamu($data)
{
    global $koneksi;

    $kode        = htmlspecialchars($data["id_tamu"]);
    $tanggal     = date("Y-m-d");
    $nama_tamu   = htmlspecialchars($data["nama_tamu"]);
    $alamat      = htmlspecialchars($data["alamat"]);
    $no_hp       = htmlspecialchars($data["no_hp"]);
    $bertemu     = htmlspecialchars($data["bertemu"]);
    $kepentingan = htmlspecialchars($data["kepentingan"]);

    $gambar = uploadGambar();
    if (!$gambar) {
        return false; // Jika gagal mengunggah gambar, hentikan proses
    }

    $query = "INSERT INTO buku_tamu VALUES ('$kode','$tanggal','$nama_tamu','$alamat','$no_hp','$bertemu','$kepentingan','$gambar')";

    mysqli_query($koneksi, $query);

    return mysqli_affected_rows($koneksi);
}

// function ubah data tamu (Sesuai Petunjuk Modul)
function ubah_tamu($data)
{
    global $koneksi;

    $id          = htmlspecialchars($data["id_tamu"]);
    $nama_tamu   = htmlspecialchars($data["nama_tamu"]);
    $alamat      = htmlspecialchars($data["alamat"]);
    $no_hp       = htmlspecialchars($data["no_hp"]);
    $bertemu     = htmlspecialchars($data["bertemu"]);
    $kepentingan = htmlspecialchars($data["kepentingan"]);
    $gambarLama  = htmlspecialchars($data["gambarLama"]);

    // cek apakah user pilih gambar baru atau tidak
    if ($_FILES['gambar']['error'] === 4) {
        $gambar = $gambarLama;
    } else {
        $gambar = uploadGambar();
    }

    $query = "UPDATE buku_tamu SET
                nama_tamu   = '$nama_tamu',
                alamat      = '$alamat',
                no_hp       = '$no_hp',
                bertemu     = '$bertemu',
                kepentingan = '$kepentingan',
                gambar      = '$gambar'
              WHERE id_tamu = '$id'";

    mysqli_query($koneksi, $query);

    return mysqli_affected_rows($koneksi);
}

// function hapus data tamu
function hapus_tamu($id)
{
    global $koneksi;

    $query = "DELETE FROM buku_tamu WHERE id_tamu = '$id'";

    mysqli_query($koneksi, $query);

    return mysqli_affected_rows($koneksi);
}

// ==================== FUNCTION UPLOAD GAMBAR ====================

function uploadGambar()
{
    // ambil data file gambar dari variable $_FILES
    $namaFile   = $_FILES['gambar']['name'];
    $ukuranFile = $_FILES['gambar']['size'];
    $error      = $_FILES['gambar']['error'];
    $tmpName    = $_FILES['gambar']['tmp_name'];

    // cek apakah tidak ada gambar yang diunggah
    if ($error === 4) {
        echo "<script>
                alert('Pilih gambar terlebih dahulu!');
              </script>";
        return false;
    }

    // cek apakah yang diunggah adalah gambar
    $ekstensiGambarValid = ['jpg', 'jpeg', 'png'];
    $ekstensiGambar = explode('.', $namaFile);
    $ekstensiGambar = strtolower(end($ekstensiGambar));
    if (!in_array($ekstensiGambar, $ekstensiGambarValid)) {
        echo "<script>
                alert('File yang diunggah harus gambar!');
              </script>";
        return false;
    }

    // cek jika ukurannya terlalu besar (maksimal 1MB)
    if ($ukuranFile > 1000000) {
        echo "<script>
                alert('Ukuran gambar terlalu besar!');
              </script>";
        return false;
    }

    // jika lolos pengecekan, gambar akan diunggah
    // generate nama gambar baru dengan uniqid()
    $namaFileBaru = uniqid();
    $namaFileBaru .= '.';
    $namaFileBaru .= $ekstensiGambar;

    move_uploaded_file($tmpName, 'assets/upload_gambar/' . $namaFileBaru);

    return $namaFileBaru;
}

// ==================== FUNCTION DATA USER ====================

// Function Tambah User
function tambah_user($data)
{
    global $koneksi;

    $id_user   = htmlspecialchars($data['id_user']);
    $username  = htmlspecialchars($data['username']);
    $password  = trim($data['password']);
    $user_role = htmlspecialchars($data['user_role']);

    // Validasi jika ada field yang kosong
    if (empty($username) || empty($password) || empty($user_role)) {
        return false;
    }

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $query = "INSERT INTO users (id_user, username, password, user_role) 
              VALUES ('$id_user', '$username', '$password_hash', '$user_role')";

    mysqli_query($koneksi, $query);

    return mysqli_affected_rows($koneksi);
}

// Function Ubah User
function ubah_user($data)
{
    global $koneksi;

    $id_user   = htmlspecialchars($data['id_user']);
    $username  = htmlspecialchars($data['username']);
    $user_role = htmlspecialchars($data['user_role']);

    $query = "UPDATE users SET 
                username = '$username',
                user_role = '$user_role'
              WHERE id_user = '$id_user'";

    mysqli_query($koneksi, $query);

    return mysqli_affected_rows($koneksi);
}

// Function Hapus User
function hapus_user($id)
{
    global $koneksi;

    $query = "DELETE FROM users WHERE id_user = '$id'";

    mysqli_query($koneksi, $query);

    return mysqli_affected_rows($koneksi);
}

// Function Ganti Password User
function ganti_password($data)
{
    global $koneksi;

    $kode     = htmlspecialchars($data["id_user"]);
    $password = trim($data["password"]);

    // 1. Validasi jika input password kosong
    if (empty($password)) {
        echo "<script>
                alert('Password tidak boleh kosong!');
              </script>";
        return false;
    }

    // 2. Ambil password lama dari database berdasarkan id_user
    $result = mysqli_query($koneksi, "SELECT password FROM users WHERE id_user = '$kode'");
    $user   = mysqli_fetch_assoc($result);

    if ($user) {
        $password_db = $user['password'];

        // 3. Cek apakah password baru sama dengan password lama
        if (password_verify($password, $password_db)) {
            echo "<script>
                    alert('Gagal! Password baru tidak boleh sama dengan password lama.');
                  </script>";
            return false;
        }
    }

    // 4. Hash password baru jika validasi lolos
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $query = "UPDATE users SET
                password = '$password_hash'
              WHERE id_user = '$kode'";

    mysqli_query($koneksi, $query);

    return mysqli_affected_rows($koneksi);
}
