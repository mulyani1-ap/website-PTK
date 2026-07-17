<?php
// Menghubungkan ke database dengan naik 1 folder ke folder induk
include "../config/database.php";

$alert_sukses = "";
$alert_gagal = "";

// 1. Logika Hapus Agenda
if (isset($_GET['action']) && $_GET['action'] == 'hapus' && isset($_GET['id'])) {
    $id_agenda = intval($_GET['id']);
    try {
        $hapus = mysqli_query($conn, "DELETE FROM agenda WHERE id = '$id_agenda'");
        if ($hapus) {
            $alert_sukses = "Agenda kegiatan berhasil dihapus dari database.";
        } else {
            $alert_gagal = "Gagal menghapus agenda. Silakan coba lagi.";
        }
    } catch (Exception $e) {
        $alert_gagal = "Error: " . $e->getMessage();
    }
}

// 2. Ambil total pesan masuk untuk badge notifikasi sidebar (Hanya yang belum dibaca)
$count_pesan = 0;
try {
    // Auto-migration: Cek & tambah kolom is_read jika belum ada
    $check_col = mysqli_query($conn, "SHOW COLUMNS FROM `pesan_masuk` LIKE 'is_read'");
    if ($check_col && mysqli_num_rows($check_col) == 0) {
        mysqli_query($conn, "ALTER TABLE `pesan_masuk` ADD COLUMN `is_read` TINYINT(1) DEFAULT 0 AFTER `pesan`");
    }

    $q_pesan = mysqli_query($conn, "SELECT COUNT(*) as total FROM pesan_masuk WHERE is_read = 0");
    if ($q_pesan) $count_pesan = mysqli_fetch_assoc($q_pesan)['total'];
} catch (Exception $e) {}

// 3. Ambil semua data agenda dari database
$ambil_agenda = false;
try {
    $ambil_agenda = mysqli_query($conn, "SELECT * FROM agenda ORDER BY tanggal DESC");
} catch (Exception $e) {}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Agenda | PTK Bontang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        .sidebar {
            min-height: 100vh;
            background-color: #0d1b2a;
            color: #ffffff;
            width: 260px;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
            transition: all 0.3s;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.7);
            font-weight: 500;
            padding: 12px 20px;
            border-radius: 8px;
            margin: 4px 12px;
            transition: all 0.2s;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: #ffffff;
            background-color: rgba(255,255,255,0.1);
        }
        .sidebar .nav-link.active {
            background-color: #0d6efd !important;
        }
        .main-content {
            margin-left: 260px;
            padding: 40px;
            min-height: 100vh;
            transition: all 0.3s;
        }
        .data-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        }
        @media (max-width: 991.98px) {
            .sidebar {
                margin-left: -260px;
                position: absolute;
            }
            .sidebar.active {
                margin-left: 0;
            }
            .main-content {
                margin-left: 0;
                padding: 20px;
            }
            .main-content.active {
                margin-left: 260px;
            }
        }
    </style>
</head>
<body>

    <div class="sidebar d-flex flex-column p-0 shadow">
        <div class="p-4 border-bottom border-secondary text-center">
            <h5 class="fw-bold text-warning mb-0"><i class="bi bi-shield-lock-fill me-2"></i>ADMIN PANEL</h5>
            <small class="text-white-50">PTK Bontang</small>
        </div>
        
        <ul class="nav nav-pills flex-column mt-3 flex-grow-1">
            <li class="nav-item">
                <a href="dashboard.php?page=home" class="nav-link">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard Utama
                </a>
            </li>
            <li class="nav-item">
                <a href="berita.php" class="nav-link">
                    <i class="bi bi-newspaper me-2"></i> Kelola Berita
                </a>
            </li>
            <li class="nav-item">
                <a href="agenda.php" class="nav-link active">
                    <i class="bi bi-calendar-event me-2"></i> Kelola Agenda
                </a>
            </li>
            <li class="nav-item">
                <a href="pengumuman.php" class="nav-link">
                    <i class="bi bi-megaphone me-2"></i> Kelola Pengumuman
                </a>
            </li>
            <li class="nav-item">
                <a href="ptk.php" class="nav-link">
                    <i class="bi bi-people me-2"></i> Kelola Data GTK
                </a>
            </li>
            <li class="nav-item">
                <a href="dashboard.php?page=pesan" class="nav-link">
                    <i class="bi bi-envelope-paper me-2"></i> Kotak Pesan
                    <?php if ($count_pesan > 0) { ?>
                        <span class="badge bg-danger rounded-pill float-end small"><?= $count_pesan; ?></span>
                    <?php } ?>
                </a>
            </li>
        </ul>
        
        <div class="p-3 border-top border-secondary mt-auto">
            <a href="../index.php" class="btn btn-outline-light w-100 btn-sm rounded-3">
                <i class="bi bi-house-door me-1"></i> Lihat Beranda Utama
            </a>
            <a href="logout.php" class="btn btn-danger w-100 btn-sm rounded-3 mt-2">
                <i class="bi bi-box-arrow-right me-1"></i> Keluar / Logout
            </a>
        </div>
    </div>

    <div class="main-content">
        <!-- Topbar Mobile Toggle -->
        <div class="d-lg-none d-flex justify-content-between align-items-center mb-4 bg-white p-3 rounded-3 shadow-sm">
            <button class="btn btn-primary" id="sidebarCollapse">
                <i class="bi bi-list"></i> Menu Panel
            </button>
            <span class="fw-bold text-primary">ADMIN PANEL</span>
        </div>

        <!-- Banner Notifikasi -->
        <?php if (!empty($alert_sukses)) : ?>
            <div class="alert alert-success alert-dismissible fade show rounded-3 small shadow-sm mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> <?= $alert_sukses; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (!empty($alert_gagal)) : ?>
            <div class="alert alert-danger alert-dismissible fade show rounded-3 small shadow-sm mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= $alert_gagal; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <div>
                <h2 class="fw-bold text-dark mb-1">Manajemen Agenda Kegiatan</h2>
                <p class="text-muted small mb-0">Kelola sosialisasi, pelatihan, rapat kerja, dan berbagai kegiatan pembinaan GTK.</p>
            </div>
            <a href="tambah-agenda.php" class="btn btn-primary rounded-3 px-3 shadow-sm">
                <i class="bi bi-plus-circle me-1"></i> Tambah Agenda
            </a>
        </div>

        <div class="card data-card p-4 bg-white">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 5%">No</th>
                            <th style="width: 30%">Nama Kegiatan</th>
                            <th style="width: 20%">Tanggal Pelaksanaan</th>
                            <th style="width: 30%">Lokasi Tempat</th>
                            <th style="width: 15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        if ($ambil_agenda && mysqli_num_rows($ambil_agenda) > 0) {
                            while ($row = mysqli_fetch_assoc($ambil_agenda)) {
                        ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td>
                                    <span class="fw-semibold text-dark d-block"><?= htmlspecialchars($row['judul']); ?></span>
                                    <small class="text-muted text-truncate d-block" style="max-width: 300px; font-size:11px;">
                                        <?= htmlspecialchars($row['deskripsi'] ?? $row['keterangan'] ?? '-'); ?>
                                    </small>
                                </td>
                                <td class="text-secondary fw-semibold">
                                    <i class="bi bi-calendar-check me-1.5 text-primary"></i>
                                    <?= date('d M Y', strtotime($row['tanggal'])); ?>
                                </td>
                                <td class="text-muted">
                                    <i class="bi bi-geo-alt-fill me-1 text-danger"></i>
                                    <?= htmlspecialchars($row['lokasi'] ?? '-'); ?>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <!-- Tombol Edit -->
                                        <a href="edit_agenda.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-outline-warning rounded-3" title="Edit Agenda">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <!-- Tombol Pemicu Modal Hapus -->
                                        <button type="button" class="btn btn-sm btn-outline-danger rounded-3" data-bs-toggle="modal" data-bs-target="#modalHapusAgenda<?= $row['id']; ?>" title="Hapus Permanen">
                                            <i class="bi bi-trash3-fill"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Bootstrap Modal Konfirmasi Hapus Agenda -->
                            <div class="modal fade" id="modalHapusAgenda<?= $row['id']; ?>" tabindex="-1" aria-hidden="true">
                              <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 rounded-4 shadow">
                                  <div class="modal-header border-0 pb-0">
                                    <h5 class="modal-title fw-bold text-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i>Konfirmasi Hapus</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                  </div>
                                  <div class="modal-body py-3 text-start">
                                    Apakah Anda yakin ingin menghapus agenda <strong>"<?= htmlspecialchars($row['judul']); ?>"</strong> dari database secara permanen?
                                  </div>
                                  <div class="modal-footer border-0 pt-0">
                                    <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Batalkan</button>
                                    <a href="agenda.php?action=hapus&id=<?= $row['id']; ?>" class="btn btn-danger rounded-3 px-3">Ya, Hapus</a>
                                  </div>
                                </div>
                              </div>
                            </div>

                        <?php 
                            }
                        } else {
                            echo "<tr><td colspan='5' class='text-center py-5 text-muted'><i class='bi bi-calendar-event fs-1 d-block mb-2'></i>Belum ada agenda kegiatan yang terdaftar.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Fitur klik toggle sidebar untuk perangkat seluler/mobile
        const sidebarCollapse = document.getElementById('sidebarCollapse');
        if(sidebarCollapse) {
            sidebarCollapse.addEventListener('click', function () {
                document.querySelector('.sidebar').classList.toggle('active');
                document.querySelector('.main-content').classList.toggle('active');
            });
        }
    </script>
</body>
</html>