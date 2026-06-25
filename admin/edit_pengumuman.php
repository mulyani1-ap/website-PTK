<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}
include "../config/database.php";

$id = mysqli_real_escape_string($conn, $_GET['id']);
$query_lama = mysqli_query($conn, "SELECT * FROM pengumuman WHERE id = '$id'");
$data = mysqli_fetch_assoc($query_lama);

if(isset($_POST['update'])){
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $isi = mysqli_real_escape_string($conn, $_POST['isi']);

    $update = mysqli_query($conn, "UPDATE pengumuman SET judul='$judul', isi='$isi' WHERE id='$id'");

    if($update){
        echo "<script>alert('Pengumuman berhasil diupdate!'); window.location='pengumuman.php';</script>";
    } else {
        echo "<script>alert('Gagal mengupdate pengumuman.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Pengumuman | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light py-5">
<div class="container" style="max-width: 650px;">
    <div class="card border-0 shadow-sm p-4 rounded-4 bg-white">
        <h3 class="fw-bold mb-4 text-dark"><i class="bi bi-pencil-square text-warning me-2"></i>Edit Pengumuman</h3>
        
        <form action="" method="POST">
            <div class="mb-3">
                <label class="form-label fw-semibold">Judul Pengumuman</label>
                <input type="text" name="judul" class="form-control" value="<?= htmlspecialchars($data['judul']) ?>" required>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Isi Pengumuman</label>
                <textarea name="isi" class="form-control" rows="6" required><?= htmlspecialchars($data['isi']) ?></textarea>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" name="update" class="btn btn-warning text-dark fw-semibold px-4"><i class="bi bi-save me-2"></i>Simpan Perubahan</button>
                <a href="pengumuman.php" class="btn btn-light px-4">Batal</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>