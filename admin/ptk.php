<?php
session_start();
include "../config/database.php"; 

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}

// Proses Hapus Data jika tombol hapus diklik
if(isset($_GET['hapus'])){
    $id = $_GET['hapus'];
    $delete = mysqli_query($conn, "DELETE FROM ptk WHERE id='$id'");
    if($delete){
        header("Location: ptk.php");
        exit;
    }
}

// Ambil semua data ptk dari database
$query = mysqli_query($conn, "SELECT * FROM ptk ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manajemen Data PTK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body{ background:#f5f7fb; }
        .sidebar{ width:260px; min-height:100vh; background:#0f4c81; color:white; position:fixed; }
        .sidebar h3{ padding:25px; }
        .sidebar a{ color:white; text-decoration:none; display:block; padding:15px 25px; }
        .sidebar a:hover{ background:rgba(255,255,255,.1); }
        .main{ margin-left:260px; padding:40px; }
        .card-box{ background:white; border-radius:20px; padding:25px; box-shadow:0 10px 25px rgba(0,0,0,.06); }
    </style>
</head>
<body>

<div class="sidebar">
    <h3>PTK Bontang</h3>
    <a href="dashboard.php">Dashboard</a>
    <a href="ptk.php" class="bg-white bg-opacity-10 fw-bold">Data PTK</a>
    <a href="berita.php">Berita</a>
    <a href="agenda.php">Agenda</a>
    <a href="pengumuman.php">Pengumuman</a>
    <a href="logout.php">Logout</a>
</div>

<div class="main">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manajemen Data PTK</h2>
        <a href="form_ptk.php" class="btn btn-primary rounded-pill">
            <i class="bi bi-plus-lg me-2"></i>Tambah Data GTK
        </a>
    </div>

    <div class="card-box">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Personnel</th>
                        <th>NIP</th>
                        <th>Jabatan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    while($row = mysqli_fetch_assoc($query)) : 
                    ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td class="fw-bold"><?= htmlspecialchars($row['nama']); ?></td>
                        <td><?= $row['nip'] ? htmlspecialchars($row['nip']) : '-'; ?></td>
                        <td>
                            <span class="badge bg-info text-dark"><?= $row['jabatan']; ?></span>
                        </td>
                        <td>
                            <a href="form_ptk.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-warning me-1">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <a href="ptk.php?hapus=<?= $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    <?php if(mysqli_num_rows($query) == 0) : ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">Belum ada data personnel.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>