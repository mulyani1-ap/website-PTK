<?php
$koneksi = mysqli_connect("localhost", "root", "", "ptk_bontang");

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

//ambil data dari tabel pengumuman untuk ditampilkan ke pengunjung
$query = mysqli_query($koneksi, "SELECT * FROM pengumuman ORDER BY id DESC"); 
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pusat Unduhan Berkas | PTK Bontang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .card-hover-effect {
            transition: all 0.3s ease;
        }
        .card-hover-effect:hover {
            transform: translateY(-2px);
        }
    </style>
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top shadow mb-5">
      <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">PTK BONTANG</a>
        <div class="navbar-nav ms-auto">
            <a class="nav-link text-white" href="index.php"><i class="bi bi-house-door-fill me-1"></i> Beranda</a>
        </div>
      </div>
    </nav>

    <div class="container mb-5">
        <div class="p-5 mb-4 bg-white rounded-4 shadow-sm border-start border-danger border-5">
            <div class="container-fluid py-2">
                <h1 class="display-6 fw-bold text-dark"><i class="bi bi-download text-danger me-3"></i>Pusat Unduhan Berkas</h1>
                <p class="col-md-10 fs-6 text-muted mb-0">Silakan cari dan unduh format berkas administrasi, surat edaran, atau dokumen resmi Bidang Pembinaan Tenaga Kependidikan di bawah ini.</p>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white card-hover-effect">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-danger text-white">
                        <tr>
                            <th style="width: 8%;" class="py-3 px-3">No</th>
                            <th class="py-3">Nama Berkas / Dokumen</th>
                            <th class="py-3">Keterangan</th>
                            <th class="text-center py-3" style="width: 20%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        if (mysqli_num_rows($query) > 0) {
                            while ($data = mysqli_fetch_assoc($query)) {
                        ?>
                            <tr>
                                <td class="px-3 fw-bold text-muted"><?php echo $no++; ?></td>
                                <td>
                                    <div class="fw-semibold text-dark"><?php echo htmlspecialchars($data['judul']); ?></div>
                                    <small class="text-muted"><i class="bi bi-calendar-event me-1"></i> Tersedia</small>
                                </td>
                                <td>
                                    <span class="text-muted small"><?php echo htmlspecialchars($data['isi'] ?? 'Tidak ada keterangan tambahan.'); ?></span>
                                </td>
                                <td class="text-center">
                                    <a href="uploads/<?php echo $data['file'] ?? ''; ?>" class="btn btn-danger btn-sm rounded-3 px-3 shadow-sm" download>
                                        <i class="bi bi-cloud-arrow-down-fill me-1"></i> Download File
                                    </a>
                                </td>
                            </tr>
                        <?php 
                            }
                        } else {
                        ?>
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <i class="bi bi-folder-x fs-2 d-block mb-2"></i>Belum ada berkas dokumen yang tersedia untuk diunduh.
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>              </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>