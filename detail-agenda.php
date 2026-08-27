<?php
include "config/database.php";

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    $query = mysqli_query($conn, "SELECT * FROM agenda WHERE id = '$id'");
    $agenda = mysqli_fetch_assoc($query);

    // Jika data tidak ditemukan, balikkan ke index
    if (!$agenda) {
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
    <title><?= $agenda['judul']; ?> | PTK Bontang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="assets/css/style.css" />
  </head>
  <body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
      <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">PTK BONTANG</a>
        <a href="index.php" class="btn btn-outline-light btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
      </div>
    </nav>

    <div class="container my-5">
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
            
            <span class="badge bg-warning text-dark px-3 py-2 mb-3 align-self-start">Agenda Kegiatan</span>
            <h1 class="fw-bold text-dark mb-4"><?= $agenda['judul']; ?></h1>
            
            <div class="row g-3 mb-4 p-3 bg-light rounded-3">
              <div class="col-sm-6">
                <div class="d-flex align-items-center">
                  <i class="bi bi-calendar-event text-primary fs-3 me-3"></i>
                  <div>
                    <small class="text-muted d-block">Tanggal Pelaksanaan</small>
                    <strong class="text-dark"><?= date('d F Y', strtotime($agenda['tanggal'])); ?></strong>
                  </div>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="d-flex align-items-center">
                  <i class="bi bi-geo-alt text-danger fs-3 me-3"></i>
                  <div>
                    <small class="text-muted d-block">Lokasi Tempat</small>
                    <strong class="text-dark"><?= $agenda['lokasi']; ?></strong>
                  </div>
                </div>
              </div>
            </div>

            <h5 class="fw-bold text-secondary mb-3">Deskripsi Kegiatan:</h5>
            <div class="agenda-desc text-secondary" style="line-height: 1.8; white-space: pre-line;">
              <?= $agenda['deskripsi']; ?>
            </div>

            <hr class="my-4">
            <div class="text-end">
                <a href="index.php" class="btn btn-secondary">Kembali ke Beranda</a>
            </div>

          </div>
        </div>
      </div>
    </div>

  </body>
</html>