<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}
include "../config/database.php";

// Ambil data agenda dari database
$query = mysqli_query($conn, "SELECT * FROM agenda ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Agenda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Daftar Agenda</h2>
        <a href="tambah-agenda.php" class="btn btn-primary">+ Tambah Agenda</a>
    </div>

    <div class="card shadow border-0 p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Judul Agenda</th>
                        <th>Tanggal</th>
                        <th>Lokasi</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    if(mysqli_num_rows($query) > 0) {
                        while($row = mysqli_fetch_assoc($query)) { ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><strong><?= $row['judul']; ?></strong></td> <td><?= $row['tanggal']; ?></td>
                            <td><?= $row['lokasi']; ?></td> <td><?= substr($row['deskripsi'], 0, 50); ?>...</td> <td>
                                <a href="edit-agenda.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                <a href="hapus-agenda.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus agenda ini?')">Hapus</a>
                            </td>
                        </tr>
                    <?php } 
                    } else { ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada data agenda.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            <a href="dashboard.php" class="btn btn-secondary">Kembali ke Dashboard</a>
        </div>
    </div>
</div>
</body>
</html>