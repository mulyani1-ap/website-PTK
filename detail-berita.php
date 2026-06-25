<?php

include "config/database.php";

$id = $_GET['id'];

$query = mysqli_query(
    $conn,
    "SELECT * FROM berita WHERE id='$id'"
);

$berita = mysqli_fetch_assoc($query);

?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title><?= $berita['judul']; ?></title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container py-5">

<a href="index.php" class="btn btn-secondary mb-4">
← Kembali
</a>

<h1 class="mb-3">
<?= $berita['judul']; ?>
</h1>

<img
src="uploads/berita/<?= $berita['thumbnail']; ?>"
class="img-fluid rounded mb-4">

<p class="text-muted">
<?= $berita['created_at']; ?>
</p>

<div style="font-size:18px;line-height:1.8;">
<?= nl2br($berita['isi']); ?>
</div>

<div class="konten-berita mb-4">
    <?= $berita['isi']; // Sesuaikan dengan variabel isi berita di halaman detailmu ?>
</div>

<div class="row g-3 mt-4">
    <h5 class="fw-bold mb-3">Dokumentasi Terkait:</h5>
    <?php
    $id_berita = $berita['id']; // Sesuaikan dengan nama variabel ID beritamu
    $ambil_media = mysqli_query($conn, "SELECT * FROM berita_media WHERE berita_id = '$id_berita'");
    
    while ($media = mysqli_fetch_assoc($ambil_media)) {
        if ($media['tipe'] == 'image') { ?>
            <div class="col-md-4 col-sm-6">
                <img src="uploads/berita/<?= $media['file_path']; ?>" class="img-fluid rounded shadow-sm" alt="Dokumentasi Berita">
            </div>
        <?php } else { ?>
            <div class="col-md-6">
                <video src="uploads/berita/<?= $media['file_path']; ?>" controls class="w-100 rounded shadow-sm"></video>
            </div>
    <?php } 
    } ?>
</div>

</div>

</body>
</html>