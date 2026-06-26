<?php
session_start();

// Taruh koneksi databasemu di sini supaya bisa menghitung data
include "../config/database.php"; 

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}

// 1. Hitung total data berita
$query_berita = mysqli_query($conn, "SELECT id FROM berita");
$total_berita = mysqli_num_rows($query_berita);

// 2. Hitung total data agenda
$query_agenda = mysqli_query($conn, "SELECT id FROM agenda");
$total_agenda = mysqli_num_rows($query_agenda);

// 3. Hitung total data pengumuman
$query_pengumuman = mysqli_query($conn, "SELECT id FROM pengumuman");
$total_pengumuman = mysqli_num_rows($query_pengumuman);
?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Dashboard Admin PTK</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    background:#f5f7fb;
}

.sidebar{
    width:260px;
    min-height:100vh;
    background:#0f4c81;
    color:white;
    position:fixed;
}

.sidebar h3{
    padding:25px;
}

.sidebar a{
    color:white;
    text-decoration:none;
    display:block;
    padding:15px 25px;
}

.sidebar a:hover{
    background:rgba(255,255,255,.1);
}

.main{
    margin-left:260px;
    padding:40px;
}

.card-box{
    background:white;
    border-radius:20px;
    padding:25px;
    box-shadow:0 10px 25px rgba(0,0,0,.06);
}

</style>

</head>

<body>

<div class="sidebar">

    <h3>PTK Bontang</h3>

    <a href="dashboard.php">Dashboard</a>

    <a href="ptk.php">Data PTK</a>

    <a href="berita.php">Berita</a>

    <a href="agenda.php">Agenda</a>

    <a href="pengumuman.php">Pengumuman</a>

    <a href="logout.php">Logout</a>

</div>

<div class="main">

    <h2>
        Selamat Datang,
        <?php echo isset($_SESSION['admin']['nama']) ? $_SESSION['admin']['nama']: 'administrator'; ?>
    </h2>

    <p>
        Sistem Informasi Bidang Pembinaan Tenaga Kependidikan
    </p>

    <div class="row mt-4">

        <div class="col-md-4 mb-4">
            <div class="card-box">
                <h3><?= $total_berita; ?></h3>
                <p class="text-muted mb-0">Total Berita</p>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card-box">
                <h3><?= $total_agenda; ?></h3>
                <p class="text-muted mb-0">Total Agenda</p>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card-box">
                <h3><?= $total_pengumuman; ?></h3>
                <p class="text-muted mb-0">Total Pengumuman</p>
            </div>
        </div>

    </div>

</div>

</body>
</html>