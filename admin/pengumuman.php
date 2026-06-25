<?php
session_start();

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}

include "../config/database.php";

// Ambil data pengumuman dari database
// Catatan: Pastikan kamu sudah punya tabel bernama 'pengumuman' di phpMyAdmin
$data = mysqli_query(
    $conn,
    "SELECT * FROM pengumuman ORDER BY id DESC"
);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Pengumuman | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

<div class="container mt-5">

    <div class="d-flex justify-content-between mb-4">
        <h2>Kelola Pengumuman</h2>
        <a href="tambah-pengumuman.php" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Tambah Pengumuman
        </a>
    </div>

    <table class="table table-bordered table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th style="width: 5%;">No</th>
                <th>Judul Pengumuman</th>
                <th style="width: 40%;">Isi Singkat</th>
                <th style="width: 20%;">Tanggal Dibuat</th>
                <th class="text-center" style="width: 15%;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if(mysqli_num_rows($data) > 0):
                $no = 1;
                while($row = mysqli_fetch_assoc($data)):
            ?>
            <tr>
                <td><?= $no++ ?></td>
                <td class="fw-semibold"><?= htmlspecialchars($row['judul']) ?></td>
                <td><?= htmlspecialchars($row['isi']) ?></td>
                <td><?= $row['created_at'] ?></td>
                <td class="text-center">
                    <div class="btn-group btn-group-sm">
                        <a href="edit_pengumuman.php?id=<?= $row['id'] ?>" class="btn btn-outline-warning" title="Edit">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                      <a href="proses_hapus_pengumuman.php?id=<?= $row['id']; ?>">delete</a>
                            <i class="bi bi-trash"></i>
                        </a>
                    </div>
                </td>
            </tr>
            <?php 
                endwhile;
            else:
            ?>
            <tr>
                <td colspan="5" class="text-center text-muted py-4">Belum ada data pengumuman.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

</div>
</body>
</html>