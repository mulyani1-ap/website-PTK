<?php
// 1. Hubungkan ke database
include "config/database.php";

// 2. Ambil kata kunci pencarian dari user (jika ada)
$keyword = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// 3. Query pencarian status NUPTK di tabel `data-gtk`
if (!empty($keyword)) {
    // Mencari berdasarkan nama, nip, atau nuptk
    $query_nuptk = mysqli_query($conn, "SELECT * FROM ptk WHERE nama LIKE '%$keyword%' OR nip LIKE '%$keyword%' OR nuptk LIKE '%$keyword%' ORDER BY nama ASC");
} else {
    // Tampilkan 10 data acak / terbaru sebagai contoh awal
    $query_nuptk = mysqli_query($conn, "SELECT * FROM ptk LIMIT 10");
}

// 4. Ambil juga agenda terkait NUPTK dari tabel agenda (jika ada)
$query_agenda = mysqli_query($conn, "SELECT * FROM agenda WHERE judul LIKE '%nuptk%' OR deskripsi LIKE '%nuptk%' ORDER BY tanggal DESC LIMIT 3");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layanan & Cek NUPTK | PTK Bontang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top shadow mb-5">
      <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">PTK BONTANG</a>
        <div class="navbar-nav ms-auto">
            <a class="nav-link text-white" href="index.php"><i class="bi bi-house-door-fill me-1"></i> Beranda</a>
        </div>
      </div>
    </nav>

    <div class="container mb-5">
        <!-- Banner Head -->
        <div class="p-5 mb-4 bg-white rounded-4 shadow-sm border-start border-success border-5">
            <h1 class="display-6 fw-bold text-dark"><i class="bi bi-card-text text-success me-3"></i>Layanan & Status NUPTK</h1>
            <p class="col-md-10 fs-6 text-muted mb-0">Lakukan pengecekan status penerbitan, validasi berkas, dan riwayat pengajuan Nomor Unik Pendidik dan Tenaga Kependidikan (NUPTK) Anda secara mandiri di bawah ini.</p>
        </div>

        <!-- Fitur Pencarian Status -->
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
            <h5 class="fw-bold mb-3"><i class="bi bi-search me-2 text-primary"></i>Pencarian Cepat Data NUPTK</h5>
            <form action="nuptk.php" method="GET" class="row g-2">
                <div class="col-md-9">
                    <input type="text" name="search" class="form-control" placeholder="Masukkan Nama Lengkap, NIP, atau NUPTK Anda..." value="<?= htmlspecialchars($keyword); ?>" required>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search me-1"></i> Cari Data</button>
                </div>
            </form>
            
            <?php if (!empty($keyword)) { ?>
                <div class="mt-3 small text-muted">
                    Menampilkan hasil pencarian untuk: <strong class="text-primary">"<?= htmlspecialchars($keyword); ?>"</strong>
                    <a href="nuptk.php" class="text-danger ms-2 text-decoration-none"><i class="bi bi-x-circle-fill"></i> Bersihkan</a>
                </div>
            <?php } ?>
        </div>

        <!-- Tabel Hasil Pencarian -->
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-success text-dark">
                        <tr>
                            <th style="width: 8%">No</th>
                            <th>Nama GTK</th>
                            <th>NIP / ID</th>
                            <th>NUPTK</th>
                            <th>Instansi / Sekolah</th>
                            <th class="text-center">Status Keaktifan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        if (mysqli_num_rows($query_nuptk) > 0) {
                            while ($row = mysqli_fetch_assoc($query_nuptk)) {
                        ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td class="fw-bold"><?= htmlspecialchars($row['nama']); ?></td>
                                <td><?= htmlspecialchars($row['nip'] ?? '-'); ?></td>
                                <td class="text-primary fw-mono"><?= htmlspecialchars($row['nuptk'] ?? $row['id'] ?? 'Dalam Proses'); ?></td>
                                <td><?= htmlspecialchars($row['instansi'] ?? $row['sekolah'] ?? '-'); ?></td>
                                <td class="text-center">
                                    <span class="badge bg-success rounded-pill px-3 py-2">Aktif (Dapodik)</span>
                                </td>
                            </tr>
                        <?php 
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center py-4 text-muted'>Tidak ada data GTK yang cocok dengan kata kunci pencarian Anda.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Agenda Terkait NUPTK -->
        <?php if (mysqli_num_rows($query_agenda) > 0) { ?>
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <h5 class="fw-bold mb-4 text-dark"><i class="bi bi-calendar-event text-danger me-2"></i>Agenda & Sosialisasi NUPTK Terdekat</h5>
                <div class="row g-3">
                    <?php while ($agenda = mysqli_fetch_assoc($query_agenda)) { ?>
                        <div class="col-md-4">
                            <div class="p-3 border rounded-3 h-100 bg-light">
                                <span class="badge bg-danger mb-2"><?= date('d M Y', strtotime($agenda['tanggal'])); ?></span>
                                <h6 class="fw-bold text-dark mb-1"><?= htmlspecialchars($agenda['judul']); ?></h6>
                                <p class="text-muted small mb-0"><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($agenda['lokasi']); ?></p>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>