<?php
session_start();

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}

include "../config/database.php";

// Tetap pakai variabel asli kamu: $data
$data = mysqli_query(
    $conn,
    "SELECT * FROM berita ORDER BY id DESC"
);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Berita | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tambahan Bootstrap Icons untuk ikon tombol edit & hapus -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

<div class="container mt-5">

    <div class="d-flex justify-content-between mb-4">
        <h2>Kelola Berita</h2>
        <a href="tambah-berita.php" class="btn btn-primary">Tambah Berita</a>
    </div>

    <!-- Kita pakai class table-hover dan align-middle biar tampilan row-nya rapi di tengah -->
    <table class="table table-bordered table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 15%;">Thumbnail</th>
                <th>Judul</th>
                <th style="width: 10%;">Status</th>
                <th style="width: 20%;">Tanggal</th>
                <!-- Tambah kolom baru untuk wadah tombol -->
                <th class="text-center" style="width: 15%;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            // Looping memakai variabel $row bawaan kodemu
            while($row = mysqli_fetch_assoc($data)):
            ?>
            <tr>
                <td><?= $no++ ?></td>
                <td>
                    <!-- Cek jika ada file thumbnail, kalau kosong kasih gambar default/placeholder -->
                    <?php if(!empty($row['thumbnail'])): ?>
                        <img src="../uploads/berita/<?= $row['thumbnail'] ?>" width="100" class="rounded shadow-sm">
                    <?php else: ?>
                        <span class="text-muted small">No Image</span>
                    <?php endif; ?>
                </td>
                <td class="fw-semibold"><?= htmlspecialchars($row['judul']) ?></td>
                <td>
                    <!-- Variasi badge warna berdasarkan status biar makin keren -->
                    <span class="badge <?= $row['status'] == 'publish' ? 'bg-success' : 'bg-secondary' ?>">
                        <?= $row['status'] ?>
                    </span>
                </td>
                <td><?= $row['created_at'] ?></td>
                
                <!-- ====== TOMBOL EDIT DAN HAPUS ====== -->
                <td class="text-center">
                    <div class="btn-group btn-group-sm">
                        <!-- Kirim ID berita lewat URL parameter -->
                        <a href="edit_berita.php?id=<?= $row['id'] ?>" class="btn btn-outline-warning" title="Edit Berita">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <a href="proses_hapus_berita.php?id=<?= $row['id'] ?>" class="btn btn-outline-danger" title="Hapus Berita" onclick="return confirm('Apakah kamu yakin ingin menghapus berita ini?')">
                            <i class="bi bi-trash"></i>
                        </a>
                    </div>
                </td>
                <!-- =================================== -->
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

</div>

</body>
</html>