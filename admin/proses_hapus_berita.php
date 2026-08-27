<?php
$koneksi = mysqli_connect("localhost", "root", "", "ptk_bontang");

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // ambil file lama biar ga penuh sampah lama
    $cari_gambar = mysqli_query($koneksi, "SELECT thumbnail FROM berita WHERE id = '$id'");
    $data_gambar = mysqli_fetch_assoc($cari_gambar);
    $nama_gambar = $data_gambar['thumbnail'];

    // hapus file gambar asli dari folder(kalau ada)
    if (!empty($nama_gambar) && file_exists("../assets/img/berita/" . $nama_gambar)) {
        unlink("../assets/img/berita/" . $nama_gambar);
    }

    //hapus tablw dari database
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