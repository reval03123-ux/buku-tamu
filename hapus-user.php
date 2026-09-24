<?php
require_once('function.php');

// Cek apakah ada ID yang dikirim lewat URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Panggil fungsi hapus_user dari function.php
    if (hapus_user($id) > 0) {
        echo "<script>
                alert('Data user berhasil dihapus!');
                document.location.href = 'user.php';
              </script>";
    } else {
        echo "<script>
                alert('Data user gagal dihapus!');
                document.location.href = 'user.php';
              </script>";
    }
} else {
    // Jika mencoba akses langsung tanpa ID, kembalikan ke user.php
    header("Location: user.php");
    exit;
}
?>