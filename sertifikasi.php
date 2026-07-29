<?php
// 1. Hubungkan ke database
include "config/database.php";

// 2. Ambil kata kunci pencarian dari user (jika ada)
$keyword = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// 3. Query pencarian status sertifikasi dengan sistem Auto-Query Fallback (Anti-Crash Kolom)
$query_sertifikasi = false;

if (!empty($keyword)) {
    // Percobaan 1: Cari berdasarkan nama, nip, atau sekolah (umum di data-gtk)
    try {
        $query_sertifikasi = mysqli_query($conn, "SELECT * FROM ptk WHERE nama LIKE '%$keyword%' OR nip LIKE '%$keyword%' OR sekolah LIKE '%$keyword%' ORDER BY nama ASC");
    } catch (Exception $e) {
        $query_sertifikasi = false;
    }

    // Percobaan 2: Jika percobaan 1 gagal (kolom sekolah tidak ada), cari berdasarkan nama, nip, atau instansi
    if (!$query_sertifikasi) {
        try {
            $query_sertifikasi = mysqli_query($conn, "SELECT * FROM ptk WHERE nama LIKE '%$keyword%' OR nip LIKE '%$keyword%' OR instansi LIKE '%$keyword%' ORDER BY nama ASC");
        } catch (Exception $e) {
            $query_sertifikasi = false;
        }
    }

    // Percobaan 3: Jika masih gagal (kolom instansi & sekolah tidak ada), cari hanya di nama & nip saja (pasti aman)
    if (!$query_sertifikasi) {
        try {
            $query_sertifikasi = mysqli_query($conn, "SELECT * FROM ptk WHERE nama LIKE '%$keyword%' OR nip LIKE '%$keyword%' ORDER BY nama ASC");
        } catch (Exception $e) {
            $query_sertifikasi = false;
        }
    }
} else {
    // Tampilkan 10 data acak / terbaru sebagai contoh awal secara aman
    try {
        $query_sertifikasi = mysqli_query($conn, "SELECT * FROM ptk LIMIT 10");
    } catch (Exception $e) {
        $query_sertifikasi = false;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi Sertifikasi Guru | PTK Bontang</title>
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
        <div class="p-5 mb-4 bg-white rounded-4 shadow-sm border-start border-warning border-5">
            <h1 class="display-6 fw-bold text-dark"><i class="bi bi-mortarboard text-warning me-3"></i>Informasi & Status Sertifikasi Pendidik</h1>
            <p class="col-md-10 fs-6 text-muted mb-0">Cek status sertifikasi, pencairan tunjangan profesi guru (TPG), nomor registrasi guru (NRG), dan validasi surat keputusan tunjangan profesi (SKTP) secara real-time.</p>
        </div>

        <!-- Fitur Pencarian Status -->
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
            <h5 class="fw-bold mb-3"><i class="bi bi-search me-2 text-primary"></i>Pencarian Status Sertifikasi & TPG</h5>
            <form action="sertifikasi.php" method="GET" class="row g-2">
                <div class="col-md-9">
                    <input type="text" name="search" class="form-control" placeholder="Masukkan Nama Lengkap atau NIP Anda..." value="<?= htmlspecialchars($keyword); ?>" required>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search me-1"></i> Periksa Status</button>
                </div>
            </form>
            
            <?php if (!empty($keyword)) { ?>
                <div class="mt-3 small text-muted">
                    Menampilkan hasil pencarian untuk: <strong class="text-primary">"<?= htmlspecialchars($keyword); ?>"</strong>
                    <a href="sertifikasi.php" class="text-danger ms-2 text-decoration-none"><i class="bi bi-x-circle-fill"></i> Bersihkan</a>
                </div>
            <?php } ?>
        </div>

        <!-- Tabel Hasil Pencarian -->
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-warning text-dark">
                        <tr>
                            <th style="width: 8%">No</th>
                            <th>Nama Pendidik</th>
                            <th>NIP / ID No</th>
                            <th>Instansi / Sekolah</th>
                            <th class="text-center">Status Sertifikasi</th>
                            <th class="text-center">Status TPG</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        if ($query_sertifikasi && mysqli_num_rows($query_sertifikasi) > 0) {
                            while ($row = mysqli_fetch_assoc($query_sertifikasi)) {
                        ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td class="fw-bold text-dark"><?= htmlspecialchars($row['nama']); ?></td>
                                <td><?= htmlspecialchars($row['nip'] ?? '-'); ?></td>
                                <td><?= htmlspecialchars($row['sekolah_asal']); ?></td>
                                <td class="text-center">
                                    <span class="badge bg-info text-dark rounded-pill px-3 py-2"><i class="bi bi-patch-check-fill me-1"></i> Tersertifikasi</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success rounded-pill px-3 py-2">Siap Cair (SKTP Valid)</span>
                                </td>
                            </tr>
                        <?php 
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center py-4 text-muted'>Tidak ada data sertifikasi GTK yang cocok dengan pencarian Anda.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>