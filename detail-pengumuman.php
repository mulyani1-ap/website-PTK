<?php
include "config/database.php";

// ngambil ID dari URL (misal: ?id=2)
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    
    // ambil data pengumuman yang sesuai ID
    $query = mysqli_query($conn, "SELECT * FROM pengumuman WHERE id = '$id'");
    $pengumuman = mysqli_fetch_assoc($query);
    
    // kalau ID tidak ditemukan di database, balik ke halaman utama
    if (!$pengumuman) {
        header("Location: index.php");
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}
?>

<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= htmlspecialchars($pengumuman['judul']); ?> | Disdikbud Kota Bontang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="assets/css/style.css" />
  </head>
  <body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top shadow-sm">
      <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">PTK BONTANG</a>
        <a href="index.php" class="btn btn-outline-light btn-sm"><i class="bi bi-arrow-left"></i> Kembali ke Beranda</a>
      </div>
    </nav>

    <div class="container py-5">
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <div class="card border-0 shadow-sm rounded-4 bg-white p-4 p-md-5">
            
            <div class="mb-3">
              <span class="text-muted small d-inline-flex align-items-center bg-light px-3 py-1 rounded-3 border">
                <i class="bi bi-calendar3 text-primary me-2"></i>
                <?= date('d F Y', strtotime($pengumuman['created_at'])); ?>
              </span>
            </div>

            <h1 class="fw-bold text-dark mb-4 lh-base text-capitalize"><?= htmlspecialchars($pengumuman['judul']); ?></h1>
            <hr class="text-muted mb-4">

            <div class="text-secondary lh-lg" style="font-size: 1.05rem; white-space: pre-line;">
              <?= nl2br(htmlspecialchars($pengumuman['isi'])); ?>
            </div>

          </div>
        </div>
      </div>
    </div>

    <footer class="bg-white border-top py-4 mt-5">
      <div class="container text-center text-muted small">
        © 2026 Bidang Pembinaan Tenaga Kependidikan Kota Bontang
      </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>