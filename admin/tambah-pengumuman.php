<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}
include "../config/database.php";

if(isset($_POST['simpan'])){
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $isi = mysqli_real_escape_string($conn, $_POST['isi']);

    $query = mysqli_query($conn, "INSERT INTO pengumuman (judul, isi) VALUES ('$judul', '$isi')");

    if($query){
        echo "<script>alert('Pengumuman berhasil ditambahkan!'); window.location='pengumuman.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan pengumuman: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Pengumuman | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light py-5">
<div class="container" style="max-width: 650px;">
    <div class="card border-0 shadow-sm p-4 rounded-4 bg-white">
        <h3 class="fw-bold mb-4 text-dark"><i class="bi bi-megaphone-fill text-primary me-2"></i>Tambah Pengumuman</h3>
        
        <form action="" method="POST">
            <div class="mb-3">
                <label class="form-label fw-semibold">Judul Pengumuman</label>
                <input type="text" name="judul" class="form-control" placeholder="Tulis judul pengumuman..." required>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Isi Pengumuman</label>
                <textarea name="isi" class="form-control" rows="6" placeholder="Tulis detail pengumuman di sini..." required></textarea>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" name="simpan" class="btn btn-primary px-4"><i class="bi bi-save me-2"></i>Simpan</button>
                <a href="pengumuman.php" class="btn btn-light px-4">Kembali</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>