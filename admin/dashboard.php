<?php
include "../config/database.php"; 

$query_notif_pensiun = mysqli_query($conn, "
    SELECT nama, jabatan, sekolah_asal, tanggal_lahir, 
    DATE_ADD(tanggal_lahir, INTERVAL 60 YEAR) AS tanggal_pensiun 
    FROM `ptk` 
    WHERE `tanggal_lahir` IS NOT NULL AND `tanggal_lahir` != '0000-00-00'
    AND DATEDIFF(DATE_ADD(tanggal_lahir, INTERVAL 60 YEAR), CURDATE()) BETWEEN 1 AND 30
    ORDER BY tanggal_pensiun ASC
");

$page = isset($_GET['page']) ? htmlspecialchars($_GET['page']) : 'home';
$alert_sukses = "";
$alert_gagal = "";

if (isset($_GET['action']) && $_GET['action'] == 'mark_read' && isset($_GET['id'])) {
    header('Content-Type: application/json');
    $id_pesan = intval($_GET['id']);
    $update = mysqli_query($conn, "UPDATE pesan_masuk SET is_read = 1 WHERE id = '$id_pesan'");
    if ($update) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
    exit;
}

// Handler Hapus Pesan Masuk
if (isset($_GET['action']) && $_GET['action'] == 'hapus' && isset($_GET['id'])) {
    $id_pesan = intval($_GET['id']);
    try {
        $hapus = mysqli_query($conn, "DELETE FROM pesan_masuk WHERE id = '$id_pesan'");
        if ($hapus) {
            $alert_sukses = "Pesan berhasil dihapus dari database.";
        } else {
            $alert_gagal = "Gagal menghapus pesan. Silakan coba lagi.";
        }
    } catch (Exception $e) {
        $alert_gagal = "Error: " . $e->getMessage();
    }
}

$count_gtk = 0;
$count_berita = 0;
$count_agenda = 0;
$count_pesan = 0;


try {
    $q_ptk = mysqli_query($conn, "SELECT COUNT(*) as total FROM `ptk`");
    if ($q_ptk) $count_gtk = mysqli_fetch_assoc($q_ptk)['total'];
} catch (Exception $e) {}

try {
    $q_berita = mysqli_query($conn, "SELECT COUNT(*) as total FROM berita");
    if ($q_berita) $count_berita = mysqli_fetch_assoc($q_berita)['total'];
} catch (Exception $e) {}

try {
    $q_agenda = mysqli_query($conn, "SELECT COUNT(*) as total FROM agenda");
    if ($q_agenda) $count_agenda = mysqli_fetch_assoc($q_agenda)['total'];
} catch (Exception $e) {}
 
try {
    // Jalankan auto-migration: Cek & tambah kolom is_read jika belum ada
    $check_col = mysqli_query($conn, "SHOW COLUMNS FROM `pesan_masuk` LIKE 'is_read'");
    if ($check_col && mysqli_num_rows($check_col) == 0) {
        mysqli_query($conn, "ALTER TABLE `pesan_masuk` ADD COLUMN `is_read` TINYINT(1) DEFAULT 0 AFTER `pesan`");
    }

    $q_pesan = mysqli_query($conn, "SELECT COUNT(*) as total FROM pesan_masuk WHERE is_read = 0");
    if ($q_pesan) $count_pesan = mysqli_fetch_assoc($q_pesan)['total'];
} catch (Exception $e) {}

$ambil_pesan = false;
try {
    $ambil_pesan = mysqli_query($conn, "SELECT * FROM pesan_masuk ORDER BY created_at DESC");
} catch (Exception $e) {}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Dashboard Admin | PTK Bontang</title>
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
        .stat-card {
            border: none;
            border-radius: 16px;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        }
        .unread-row {
            background-color: #fffbeb !important;
            font-weight: 600;
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

    <!-- Sidebar Menu Admin-->
    <div class="sidebar d-flex flex-column p-0 shadow">
        <div class="p-4 border-bottom border-secondary text-center">
            <h5 class="fw-bold text-warning mb-0"><i class="bi bi-shield-lock-fill me-2"></i>ADMIN PANEL</h5>
            <small class="text-white-50">PTK Bontang</small>
        </div>
        
        <ul class="nav nav-pills flex-column mt-3 flex-grow-1">
            <li class="nav-item">
                <a href="dashboard.php?page=home" class="nav-link <?= ($page == 'home') ? 'active' : ''; ?>">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard Utama
                </a>
            </li>
            <li class="nav-item">
                <a href="berita.php" class="nav-link">
                    <i class="bi bi-newspaper me-2"></i> Kelola Berita
                </a>
            </li>
            <li class="nav-item">
                <a href="agenda.php" class="nav-link">
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
                <a href="dashboard.php?page=pesan" class="nav-link <?= ($page == 'pesan') ? 'active' : ''; ?>">
                    <i class="bi bi-envelope-paper me-2"></i> Kotak Pesan
                    <?php if ($count_pesan > 0) { ?>
                        <span class="badge bg-danger rounded-pill float-end small badge-pesan-sidebar"><?= $count_pesan; ?></span>
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
        <!-- Topbar -->
        <div class="d-lg-none d-flex justify-content-between align-items-center mb-4 bg-white p-3 rounded-3 shadow-sm">
            <button class="btn btn-primary" id="sidebarCollapse">
                <i class="bi bi-list"></i> Menu Panel
            </button>
            <span class="fw-bold text-primary">ADMIN PANEL</span>
        </div>

        <?php if ($query_notif_pensiun && mysqli_num_rows($query_notif_pensiun) > 0): ?>
    <?php while ($pensiun = mysqli_fetch_assoc($query_notif_pensiun)) : ?>
        <div class="alert alert-warning alert-dismissible fade show shadow-sm rounded-3 mb-3 border-start border-warning border-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill text-warning fs-4 me-3"></i>
                <div>
                    <strong>Peringatan Pensiun!</strong> 
                    <span class="badge bg-secondary ms-1"><?= htmlspecialchars($pensiun['jabatan']); ?></span> 
                    <strong><?= htmlspecialchars($pensiun['nama']); ?></strong> 
                    (<?= htmlspecialchars($pensiun['sekolah_asal']); ?>) akan memasuki masa pensiun pada 
                    <strong><?= date('d-m-Y', strtotime($pensiun['tanggal_pensiun'])); ?></strong> 
                    (tinggal 1 bulan lagi).
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endwhile; ?>
<?php endif; ?>

        <!-- Banner Notifikasi Status Tindakan -->
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

        <?php if ($page == 'home') : ?>
            <!--BERANDA STATISTIK UTAMA -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-dark mb-0">Selamat Datang, Admin!</h2>
                <span class="text-muted small"><i class="bi bi-calendar-check me-1"></i> Hari ini: <?= date('d M Y'); ?></span>
            </div>

            <!-- Grid Infografis Data Real-time -->
            <div class="row g-4 mb-5">
                <div class="col-md-3">
                    <div class="card stat-card bg-white p-4 h-100 shadow-sm border-start border-primary border-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted small fw-bold text-uppercase mb-1">Total Guru & GTK</h6>
                                <h3 class="fw-bold text-dark mb-0"><?= number_format($count_gtk); ?></h3>
                            </div>
                            <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-4 fs-3">
                                <i class="bi bi-person-badge"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card bg-white p-4 h-100 shadow-sm border-start border-success border-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted small fw-bold text-uppercase mb-1">Berita Publish</h6>
                                <h3 class="fw-bold text-dark mb-0"><?= number_format($count_berita); ?></h3>
                            </div>
                            <div class="bg-success bg-opacity-10 text-success p-3 rounded-4 fs-3">
                                <i class="bi bi-newspaper"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card bg-white p-4 h-100 shadow-sm border-start border-warning border-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted small fw-bold text-uppercase mb-1">Agenda Kegiatan</h6>
                                <h3 class="fw-bold text-dark mb-0"><?= number_format($count_agenda); ?></h3>
                            </div>
                            <div class="bg-warning bg-opacity-10 text-warning p-3 rounded-4 fs-3">
                                <i class="bi bi-calendar-event"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card bg-white p-4 h-100 shadow-sm border-start border-danger border-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted small fw-bold text-uppercase mb-1">Pesan Masuk</h6>
                                <h3 class="fw-bold text-dark mb-0"><?= number_format($count_pesan); ?></h3>
                            </div>
                            <div class="bg-danger bg-opacity-10 text-danger p-3 rounded-4 fs-3">
                                <i class="bi bi-envelope-paper"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ringkasan Inbox Pesan Masuk Terbaru -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-chat-left-dots text-primary me-2"></i>Pesan Masuk Terbaru</h5>
                    <a href="dashboard.php?page=pesan" class="btn btn-sm btn-outline-primary rounded-3 px-3">Lihat Semua Pesan</a>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Pengirim</th>
                                <th>Subjek</th>
                                <th>Tanggal Masuk</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $pesan_limit = false;
                            try { $pesan_limit = mysqli_query($conn, "SELECT * FROM pesan_masuk ORDER BY created_at DESC LIMIT 3"); } catch (Exception $e) {}
                            if ($pesan_limit && mysqli_num_rows($pesan_limit) > 0) {
                                while ($p = mysqli_fetch_assoc($pesan_limit)) {
                                    $is_bold = ($p['is_read'] == 0) ? 'fw-bold table-warning' : '';
                            ?>
                                <tr class="<?= $is_bold; ?>">
                                    <td class="fw-semibold text-dark"><?= htmlspecialchars($p['nama']); ?></td>
                                    <td class="text-secondary"><?= htmlspecialchars($p['subjek']); ?></td>
                                    <td class="text-muted small"><?= date('d M Y, H:i', strtotime($p['created_at'])); ?> WITA</td>
                                </tr>
                            <?php 
                                }
                            } else {
                                echo "<tr><td colspan='3' class='text-center py-4 text-muted'>Belum ada pesan masuk di kotak masuk.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

        <?php elseif ($page == 'pesan') : ?>
            <!--KOTAK PESAN MASUK -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold text-dark mb-1">Kotak Pesan Masuk</h2>
                    <p class="text-muted small mb-0">Berikut adalah daftar aduan, keluhan, pertanyaan, dan masukan dari GTK Kota Bontang.</p>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-dark text-white">
                            <tr>
                                <th style="width: 5%">No</th>
                                <th style="width: 20%">Pengirim</th>
                                <th style="width: 20%">Kontak / Email</th>
                                <th style="width: 20%">Subjek</th>
                                <th style="width: 25%">Isi Pesan</th>
                                <th style="width: 10%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            if ($ambil_pesan && mysqli_num_rows($ambil_pesan) > 0) {
                                while ($pesan = mysqli_fetch_assoc($ambil_pesan)) {
                                    $is_unread = ($pesan['is_read'] == 0);
                                    $row_class = $is_unread ? 'unread-row' : '';
                                    $envelope_icon = $is_unread ? 'bi-envelope-fill text-danger' : 'bi-envelope-open text-muted';
                                    $bold_text = $is_unread ? 'fw-bold text-dark' : 'text-secondary';
                            ?>
                                <tr class="<?= $row_class; ?>" id="row-pesan-<?= $pesan['id']; ?>">
                                    <td><?= $no++; ?></td>
                                    <td>
                                        <span class="<?= $bold_text; ?> d-block"><i class="bi <?= $envelope_icon; ?> me-1.5" id="icon-envelope-<?= $pesan['id']; ?>"></i><?= htmlspecialchars($pesan['nama']); ?></span>
                                        <small class="text-muted" style="font-size:11px;">
                                            <i class="bi bi-clock me-1"></i><?= date('d M Y, H:i', strtotime($pesan['created_at'])); ?> WITA
                                        </small>
                                    </td>
                                    <td>
                                        <a href="mailto:<?= htmlspecialchars($pesan['email']); ?>" class="text-decoration-none text-primary small">
                                            <i class="bi bi-envelope me-1"></i><?= htmlspecialchars($pesan['email']); ?>
                                        </a>
                                    </td>
                                    <td class="<?= $bold_text; ?>"><?= htmlspecialchars($pesan['subjek']); ?></td>
                                    <td>
                                        <p class="mb-0 text-muted small text-truncate" style="max-width: 250px;">
                                            <?= htmlspecialchars($pesan['pesan']); ?>
                                        </p>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <!-- Tombol Lihat Detail Pesan -->
                                            <button type="button" class="btn btn-sm btn-outline-primary rounded-3" 
                                                    onclick="bukaDetailPesan(<?= $pesan['id']; ?>, '<?= addslashes(htmlspecialchars($pesan['nama'])); ?>', '<?= addslashes(htmlspecialchars($pesan['email'])); ?>', '<?= addslashes(htmlspecialchars($pesan['subjek'])); ?>', '<?= addslashes(nl2br(htmlspecialchars($pesan['pesan']))); ?>', '<?= date('d M Y, H:i', strtotime($pesan['created_at'])); ?> WITA', <?= $pesan['is_read']; ?>)" 
                                                    title="Baca Detail">
                                                <i class="bi bi-eye-fill"></i>
                                            </button>
                                            <!-- Tombol Hapus Pesan -->
                                            <button type="button" class="btn btn-sm btn-outline-danger rounded-3" data-bs-toggle="modal" data-bs-target="#modalHapus<?= $pesan['id']; ?>" title="Hapus Permanen">
                                                <i class="bi bi-trash3-fill"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal Hapus Pesan -->
                                <div class="modal fade" id="modalHapus<?= $pesan['id']; ?>" tabindex="-1" aria-hidden="true">
                                  <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 rounded-4 shadow">
                                      <div class="modal-header border-0 pb-0">
                                        <h5 class="modal-title fw-bold text-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i>Konfirmasi Hapus</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                      </div>
                                      <div class="modal-body py-3">
                                        Apakah Anda yakin ingin menghapus pesan dari <strong><?= htmlspecialchars($pesan['nama']); ?></strong> secara permanen? Tindakan ini tidak dapat dikembalikan.
                                      </div>
                                      <div class="modal-footer border-0 pt-0">
                                        <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Batalkan</button>
                                        <a href="dashboard.php?page=pesan&action=hapus&id=<?= $pesan['id']; ?>" class="btn btn-danger rounded-3 px-3">Ya, Hapus Data</a>
                                      </div>
                                    </div>
                                  </div>
                                </div>

                            <?php 
                                }
                            } else {
                                echo "<tr><td colspan='6' class='text-center py-5 text-muted'><i class='bi bi-chat-left-dots fs-1 d-block mb-2'></i>Belum ada pesan masuk di kotak masuk Anda.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Bagian untuk Menampilkan Notifikasi Pensiun -->
<?php if (isset($query_notif_pensiun) && mysqli_num_rows($query_notif_pensiun) > 0): ?>
    <?php while ($pensiun = mysqli_fetch_assoc($query_notif_pensiun)) : ?>
        <div class="alert alert-warning alert-dismissible fade show shadow-sm rounded-4 mb-4 border-start border-warning border-4 bg-white" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill text-warning fs-3 me-3"></i>
                <div>
                    <strong class="text-dark">Peringatan Batas Usia Pensiun!</strong> 
                    <span class="badge bg-secondary ms-1"><?= htmlspecialchars($pensiun['jabatan']); ?></span> 
                    <strong class="text-dark"><?= htmlspecialchars($pensiun['nama']); ?></strong> 
                    (<?= htmlspecialchars($pensiun['sekolah_asal']); ?>) akan memasuki masa pensiun pada 
                    <strong class="text-danger"><?= date('d-m-Y', strtotime($pensiun['tanggal_pensiun'])); ?></strong> 
                    (tinggal 1 bulan lagi).
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endwhile; ?>
<?php endif; ?>

    <!-- Bootstrap Modal Detail Pesan Masuk -->
    <div class="modal fade" id="modalDetailPesan" tabindex="-1" aria-labelledby="modalDetailLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
          <div class="modal-header border-0 bg-primary text-white rounded-top-4 py-3">
            <h5 class="modal-title fw-bold" id="modalDetailLabel"><i class="bi bi-envelope-open-fill me-2"></i>Detail Pesan</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body p-4">
            <div class="mb-3">
                <label class="text-muted small fw-bold d-block">Nama Pengirim</label>
                <span class="fs-5 fw-semibold text-dark" id="det-nama">-</span>
            </div>
            <div class="row mb-3">
                <div class="col-6">
                    <label class="text-muted small fw-bold d-block">Email</label>
                    <a href="#" class="text-primary fw-medium text-decoration-none" id="det-email">-</a>
                </div>
                <div class="col-6">
                    <label class="text-muted small fw-bold d-block">Tanggal Kirim</label>
                    <span class="text-secondary small" id="det-tanggal">-</span>
                </div>
            </div>
            <div class="mb-3 border-top pt-3">
                <label class="text-muted small fw-bold d-block">Subjek</label>
                <span class="fw-bold text-dark fs-6" id="det-subjek">-</span>
            </div>
            <div class="p-3 bg-light rounded-3 border">
                <label class="text-muted small fw-bold d-block mb-1">Isi Pesan:</label>
                <p class="text-dark small mb-0 text-wrap lh-base" id="det-pesan" style="white-space: pre-line;">-</p>
            </div>
          </div>
          <div class="modal-footer border-0 pt-0 pb-3 d-flex justify-content-between">
            <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Tutup</button>
            <a href="#" class="btn btn-primary rounded-3 px-3" id="det-btn-reply">
                <i class="bi bi-reply-fill me-1"></i> Balas ke Pengirim
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap Bundle JS -->
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

        // Fungsi interaktif membuka detail pesan dan menandainya sebagai sudah dibaca secara asinkron 
        function bukaDetailPesan(id, nama, email, subjek, pesan, tanggal, is_read) {
            document.getElementById('det-nama').innerText = nama;
            document.getElementById('det-email').innerText = email;
            document.getElementById('det-email').href = "mailto:" + email;
            document.getElementById('det-tanggal').innerText = tanggal;
            document.getElementById('det-subjek').innerText = subjek;
            document.getElementById('det-pesan').innerHTML = pesan;
            document.getElementById('det-btn-reply').href = "mailto:" + email + "?subject=Re: " + encodeURIComponent(subjek);

            const modal = new bootstrap.Modal(document.getElementById('modalDetailPesan'));
            modal.show();

            // Jika statusnya belum dibaca (is_read == 0)
            if (is_read === 0) {
                fetch('dashboard.php?action=mark_read&id=' + id)
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            const row = document.getElementById('row-pesan-' + id);
                            if (row) {
                                row.classList.remove('unread-row');
                                row.querySelectorAll('.fw-bold').forEach(el => el.classList.remove('fw-bold'));
                            }
                            const icon = document.getElementById('icon-envelope-' + id);
                            if (icon) {
                                icon.classList.remove('bi-envelope-fill', 'text-danger');
                                icon.classList.add('bi-envelope-open', 'text-muted');
                            }
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }
        }

        // Ketika modal ditutup, reload halaman agar badge di sidebar PHP terupdate
        const detailModalEl = document.getElementById('modalDetailPesan');
        if (detailModalEl) {
            detailModalEl.addEventListener('hidden.bs.modal', function () {
                location.reload();
            });
        }
    </script>
</body>
</html>