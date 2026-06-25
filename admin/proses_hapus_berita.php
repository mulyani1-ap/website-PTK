<?php
// Koneksi database (sesuaikan nama file koneksi atau db kamu)
$koneksi = mysqli_connect("localhost", "root", "", "ptk_bontang");

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // 1. Ambil nama file gambar lama dulu biar folder projek gak penuh sampah gambar
    $cari_gambar = mysqli_query($koneksi, "SELECT thumbnail FROM berita WHERE id = '$id'");
    $data_gambar = mysqli_fetch_assoc($cari_gambar);
    $nama_gambar = $data_gambar['thumbnail'];

    // Hapus file gambar asli dari folder jika ada
    if (!empty($nama_gambar) && file_exists("../assets/img/berita/" . $nama_gambar)) {
        unlink("../assets/img/berita/" . $nama_gambar);
    }

    // 2. Hapus data dari tabel database
    $hapus = mysqli_query($koneksi, "DELETE FROM berita WHERE id = '$id'");

    if ($hapus) {
        echo "<script>
                alert('Berita berhasil dihapus!');
                window.location='berita.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menghapus berita.');
                window.location='berita.php';
              </script>";
    }
} else {
    header("Location: berita.php");
}
?>