<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}
include "../config/database.php";

if(isset($_POST['simpan'])) {
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $tanggal = $_POST['tanggal'];
    $lokasi = mysqli_real_escape_string($conn, $_POST['lokasi']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);

    // Query insert disesuaikan dengan struktur tabel agenda kamu
    $query = mysqli_query($conn, "INSERT INTO agenda (judul, tanggal, lokasi, deskripsi) VALUES ('$judul', '$tanggal', '$lokasi', '$deskripsi')");

    if($query) {
        header("Location: agenda.php");
        exit;
    } else {
        $error = "Gagal menambahkan data agenda!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Agenda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5 mb-5">
    <div class="card shadow border-0 max-width-600 mx-auto">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Form Tambah Agenda</h4>
        </div>
        <div class="card-body">
            <?php if(isset($error)): ?>
                <div class="alert alert-danger"><?= $error; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Judul Agenda</label>
                    <input type="text" name="judul" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tanggal Pelaksanaan</label>
                    <input type="date" name="tanggal" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Lokasi / Tempat</label>
                    <input type="text" name="lokasi" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Deskripsi Agenda</label>
                    <textarea name="deskripsi" rows="4" class="form-control" required></textarea>
                </div>
                
                <button type="submit" name="simpan" class="btn btn-success">Simpan Agenda</button>
                <a href="agenda.php" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>