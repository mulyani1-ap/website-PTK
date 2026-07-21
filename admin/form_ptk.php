<?php
session_start();

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}

include "../config/database.php";

$table_used = 'ptk'; // Dipatok langsung ke tabel aslimu

$id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : '';
$nama = "";
$nip = "";
$jabatan = "";
$sekolah_asal = "";
$alert_gagal = "";
$alert_sukses = "";

// 1. Ambil data lama jika mode EDIT
if (!empty($id)) {
    $q_fetch = mysqli_query($conn, "SELECT * FROM `$table_used` WHERE id = '$id'");
    if ($q_fetch && mysqli_num_rows($q_fetch) > 0) {
        $data_edit = mysqli_fetch_assoc($q_fetch);
        $nama = $data_edit['nama'] ?? '';
        $nip = $data_edit['nip'] ?? '';
        $jabatan = $data_edit['jabatan'] ?? '';
        $sekolah_asal = $data_edit['sekolah_asal'] ?? '';
    }
}

// 2. Proses Simpan / Update Data (POST Handler)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_post = mysqli_real_escape_string($conn, $_POST['id']);
    $nama_post = mysqli_real_escape_string($conn, $_POST['nama']);
    $nip_post = mysqli_real_escape_string($conn, $_POST['nip']);
    $jabatan_post = mysqli_real_escape_string($conn, $_POST['jabatan']);
    $sekolah_post = mysqli_real_escape_string($conn, $_POST['sekolah_asal']);

    if (!empty($id_post)) {
        // Mode UPDATE: Dipatok langsung menggunakan nama kolom sekolah_asal asli Anda
        $sql = "UPDATE `$table_used` SET 
                `nama` = '$nama_post', 
                `nip` = '$nip_post', 
                `jabatan` = '$jabatan_post', 
                `sekolah_asal` = '$sekolah_post' 
                WHERE id = '$id_post'";
    } else {
        // Mode INSERT: Dipatok langsung menggunakan kolom sekolah_asal asli Anda
        $sql = "INSERT INTO `$table_used` (`nama`, `nip`, `jabatan`, `sekolah_asal`) 
                VALUES ('$nama_post', '$nip_post', '$jabatan_post', '$sekolah_post')";
    }

    if (mysqli_query($conn, $sql)) {
        header("Location: ptk.php?msg=sukses");
        exit;
    } else {
        $alert_gagal = "Gagal menyimpan data ke database: " . mysqli_error($conn);
    }
}

// 3. Ambil total pesan masuk untuk badge notifikasi sidebar
$count_pesan = 0;
try {
    $q_pesan = mysqli_query($conn, "SELECT COUNT(*) as total FROM pesan_masuk WHERE is_read = 0");
    if ($q_pesan) $count_pesan = mysqli_fetch_assoc($q_pesan)['total'];
} catch (Exception $e) {}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= !empty($id) ? 'Edit' : 'Tambah'; ?> Data GTK | PTK Bontang</title>
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
        .form-card {
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

    <!-- Sidebar Menu Admin Gelap Premium -->
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
                <a href="ptk.php" class="nav-link active">
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

    <!-- Konten Utama Halaman Form -->
    <div class="main-content">
        <!-- Topbar Mobile Toggle -->
        <div class="d-lg-none d-flex justify-content-between align-items-center mb-4 bg-white p-3 rounded-3 shadow-sm">
            <button class="btn btn-primary" id="sidebarCollapse">
                <i class="bi bi-list"></i> Menu Panel
            </button>
            <span class="fw-bold text-primary">ADMIN PANEL</span>
        </div>

        <!-- Banner Notifikasi Gagal Simpan -->
        <?php if (!empty($alert_gagal)) : ?>
            <div class="alert alert-danger alert-dismissible fade show rounded-3 small shadow-sm mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= $alert_gagal; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="mb-4">
            <h2 class="fw-bold text-dark mb-1"><?= !empty($id) ? 'Edit' : 'Tambah'; ?> Data Personnel GTK</h2>
            <p class="text-muted small">Isi rincian formulir di bawah ini untuk menyimpan informasi terbaru guru dan tenaga kependidikan ke database.</p>
        </div>

        <!-- Card Box Formulir Input -->
        <div class="card form-card p-4 bg-white">
            <form action="form_ptk.php<?= !empty($id) ? '?id=' . $id : ''; ?>" method="POST">
                <div class="row g-3">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($id); ?>">

                    <!-- Input Nama Lengkap -->
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-secondary">Nama Lengkap Personnel</label>
                        <input type="text" name="nama" class="form-control rounded-3" placeholder="Contoh: Siti Aminah, S.Pd" value="<?= htmlspecialchars($nama); ?>" required>
                    </div>

                    <!-- Input NIP -->
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-secondary">NIP / ID Pegawai</label>
                        <input type="text" name="nip" class="form-control rounded-3" placeholder="Masukkan 18 digit NIP atau tulis '-' jika non-PNS" value="<?= htmlspecialchars($nip); ?>">
                    </div>

                    <!-- Input Jabatan -->
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-secondary">Jabatan / Peran</label>
                        <select name="jabatan" class="form-select rounded-3" required>
                            <option value="">-- Pilih Jabatan --</option>
                            <option value="Guru" <?= $jabatan == 'Guru' ? 'selected' : ''; ?>>Guru</option>
                            <option value="Kepala Sekolah" <?= $jabatan == 'Kepala Sekolah' ? 'selected' : ''; ?>>Kepala Sekolah</option>
                            <option value="Pengawas" <?= $jabatan == 'Pengawas' ? 'selected' : ''; ?>>Pengawas</option>
                            <option value="Tenaga Administrasi" <?= $jabatan == 'Tenaga Administrasi' ? 'selected' : ''; ?>>Tenaga Administrasi</option>
                        </select>
                    </div>

                    <!-- Input Nama Sekolah/Instansi -->
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-secondary">Nama Sekolah / Instansi (Sekolah Asal)</label>
                        <input type="text" name="sekolah_asal" class="form-control rounded-3" placeholder="Contoh: SDN 001 Bontang Utara" value="<?= htmlspecialchars($sekolah_asal); ?>" required>
                    </div>

                    <!-- Tombol Aksi Simpan -->
                    <div class="col-12 mt-4 pt-3 border-top d-flex gap-2">
                        <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm fw-semibold">
                            <i class="bi bi-save me-1"></i> <?= !empty($id) ? 'Update Data' : 'Simpan Data'; ?>
                        </button>
                        <a href="ptk.php" class="btn btn-light rounded-pill px-4 fw-semibold">Batal</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
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