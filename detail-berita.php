<?php
include "config/database.php";

// ngambil parameter id berita
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

//ngambil berita utama
$query = mysqli_query($conn, "SELECT * FROM berita WHERE id='$id'");
$berita = mysqli_fetch_assoc($query);

if (!$berita) {
    echo "<div class='container mt-5'><div class='alert alert-danger'>Berita tidak ditemukan!</div></div>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($berita['judul']); ?> | PTK Bontang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .news-thumbnail {
            max-height: 450px;
            object-fit: cover;
            width: 100%;
        }
        /* menyeragamkan ukuran dan aspek ratio gambar dan vid */
        .media-item img, .media-item video {
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transition: transform 0.2s ease;
            width: 100%;
            height: 100%;
            aspect-ratio: 4/3; 
            object-fit: cover;
        }
        .media-item img:hover, .media-item video:hover {
            transform: scale(1.02);
        }
    </style>
</head>
<body class="bg-light">

    <div class="container py-5">
        <a href="index.php" class="btn btn-outline-secondary mb-4 rounded-3">
            <i class="bi bi-arrow-left"></i> Kembali ke Beranda
        </a>

        <h1 class="fw-bold text-dark mb-2"><?= htmlspecialchars($berita['judul']); ?></h1>
        
        <p class="text-muted small mb-4">
            <i class="bi bi-calendar3 me-1"></i> <?= date('d M Y', strtotime($berita['created_at'])); ?>
        </p>

        <!-- cover/thumbnail  -->
        <?php if (!empty($berita['thumbnail'])) { ?>
            <img src="uploads/berita/<?= htmlspecialchars($berita['thumbnail']); ?>" class="news-thumbnail rounded shadow-sm mb-4" alt="Thumbnail">
        <?php } ?>

        <div class="bg-white p-4 rounded-4 shadow-sm mb-5" style="font-size:18px; line-height:1.8; text-align: justify;">
            <?= nl2br(htmlspecialchars($berita['isi'])); ?>
        </div>

        <div class="bg-white p-4 rounded-4 shadow-sm">
            <h5 class="fw-bold text-dark mb-4"><i class="bi bi-images text-primary me-2"></i>Dokumentasi Terkait:</h5>
            <div class="row g-3">
                <?php
                $id_berita = $berita['id'];
                $ambil_media = mysqli_query($conn, "SELECT * FROM berita_media WHERE berita_id = '$id_berita'");
                
                if (mysqli_num_rows($ambil_media) > 0) {
                    while ($media = mysqli_fetch_assoc($ambil_media)) {
                        $file_path = $media['file_path'];
                        
                        // ngambil ekstensi berkas di ujung nama file secara aman
                        $ext = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
                        
                        // daftar ekstensi gambar dan video
                        $ekstensi_gambar = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
                        $ekstensi_video = ['mp4', 'webm', 'ogg', 'mov', '3gp'];

                        // pengecekan berdasarkan tipe DB atau ekstensi berkas
                        $is_image = ($media['tipe'] == 'image' || in_array($ext, $ekstensi_gambar));
                        $is_video = ($media['tipe'] == 'video' || in_array($ext, $ekstensi_video));

                        if ($is_image) { 
                        ?>
                            <!-- tampilan jika berkas berupa gambar -->
                            <div class="col-md-4 col-sm-6 media-item">
                                <a href="uploads/berita/<?= htmlspecialchars($file_path); ?>" target="_blank">
                                    <img src="uploads/berita/<?= htmlspecialchars($file_path); ?>" class="img-fluid" alt="Dokumentasi Berita">
                                </a>
                            </div>
                        <?php 
                        } elseif ($is_video) { 
                        ?>
                            <!-- tampilan jika berkas berupa vid -->
                            <div class="col-md-4 col-sm-6 media-item">
                                <video src="uploads/berita/<?= htmlspecialchars($file_path); ?>" controls></video>
                            </div>
                        <?php 
                        } else { 
                        ?>
                            <!-- tampilan jika berkas berupa dokumen lain (PDF, Word, dll) -->
                            <div class="col-md-4 col-sm-6">
                                <div class="alert alert-secondary d-flex align-items-center rounded-3 p-3 mb-0" role="alert" style="height: 100%;">
                                    <i class="bi bi-file-earmark-arrow-down fs-3 me-2 text-primary"></i>
                                    <div class="overflow-hidden">
                                        <span class="d-block text-truncate fw-semibold mb-1"><?= htmlspecialchars($file_path); ?></span>
                                        <a href="uploads/berita/<?= htmlspecialchars($file_path); ?>" class="btn btn-sm btn-primary py-1 px-2" download>Download File</a>
                                    </div>
                                </div>
                            </div>
                        <?php 
                        } 
                    } 
                } else {
                    echo "<div class='col-12'><p class='text-muted small italic mb-0'>Tidak ada dokumentasi tambahan untuk berita ini.</p></div>";
                }
                ?>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>