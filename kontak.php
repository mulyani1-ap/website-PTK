<?php
// Hubungkan ke database asli kamu
include "config/database.php";

$pesan_sukses = "";
$pesan_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari formulir secara aman
    $nama = isset($_POST['nama']) ? htmlspecialchars(trim($_POST['nama'])) : '';
    $email = isset($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : '';
    $subjek = isset($_POST['subjek']) ? htmlspecialchars(trim($_POST['subjek'])) : '';
    $isi_pesan = isset($_POST['pesan']) ? htmlspecialchars(trim($_POST['pesan'])) : '';

    if (!empty($nama) && !empty($isi_pesan)) {
        // Masukkan data pesan ke tabel database 'pesan_masuk'
        $simpan = mysqli_query($conn, "INSERT INTO pesan_masuk (nama, email, subjek, pesan) VALUES ('$nama', '$email', '$subjek', '$isi_pesan')");
        
        if ($simpan) {
            $pesan_sukses = "Terima kasih, <strong>$nama</strong>! Pesan Anda mengenai '$subjek' telah berhasil dikirim ke Admin PTK Bontang.";
        } else {
            $pesan_error = "Gagal mengirim pesan ke database. Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hubungi Kami | PTK Bontang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .icon-box {
            width: 50px;
            height: 50px;
            background-color: #e7f1ff;
            color: #0d6efd;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            font-size: 24px;
        }
    </style>
</head>
<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top shadow mb-5">
      <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">PTK BONTANG</a>
        <div class="navbar-nav ms-auto">
            <a class="nav-link text-white mb-0" href="index.php"><i class="bi bi-house-door-fill me-1"></i> Beranda</a>
        </div>
      </div>
    </nav>

    <div class="container mb-5">
        <!-- Header Banner -->
        <div class="p-5 mb-4 bg-white rounded-4 shadow-sm border-start border-primary border-5">
            <h1 class="display-6 fw-bold text-dark"><i class="bi bi-envelope-at text-primary me-3"></i>Hubungi Layanan PTK</h1>
            <p class="col-md-10 fs-6 text-muted mb-0">Memiliki pertanyaan seputar NUPTK, Sertifikasi, atau kendala data GTK Dapodik di wilayah Kota Bontang? Sampaikan aspirasi atau pertanyaan Anda langsung kepada kami.</p>
        </div>

        <div class="row g-4">
            <!-- Kolom Kiri: Informasi Kontak Fisik -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white">
                    <h5 class="fw-bold text-dark mb-4">Informasi Hubung</h5>
                    
                    <!-- Item Alamat -->
                    <div class="d-flex align-items-start mb-4">
                        <div class="icon-box me-3 flex-shrink-0">
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Alamat Kantor</h6>
                            <p class="text-muted small mb-0">Jl. Bessai Berinta, Bontang Lestari, Bontang Selatan<br>Kalimantan Timur, Indonesia.</p>
                        </div>
                    </div>

                    <!-- Item Email -->
                    <div class="d-flex align-items-start mb-4">
                        <div class="icon-box me-3 flex-shrink-0">
                            <i class="bi bi-envelope-open-fill"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Surel Resmi</h6>
                            <p class="text-muted small mb-0">disdikbud.bontangkota.go.id<br>@disdikbud.bontang (Instagram)</p>
                        </div>
                    </div>

                    <!-- Item Jam Kerja -->
                    <div class="d-flex align-items-start">
                        <div class="icon-box me-3 flex-shrink-0">
                            <i class="bi bi-clock-fill"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Jam Operasional</h6>
                            <p class="text-muted small mb-0">Senin - Kamis: 07:30 - 16:00 WITA<br>Jumat: 07:30 - 11:00 WITA</p>
                        </div>
                    </div>

                    <hr class="my-4 text-muted">

                    <!-- Integrasi Tombol Cepat WhatsApp -->
                    <h6 class="fw-bold text-dark mb-2">Layanan Respons Cepat:</h6>
                    <a href="https://wa.me/6282195130786" target="_blank" class="btn btn-success rounded-3 w-100 py-2 fw-bold">
                        <i class="bi bi-whatsapp me-2"></i> Hubungi via WhatsApp Chat 
                    </a>
                </div>
            </div>

            <!-- Kolom Kanan: Formulir Pesan -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-chat-left-text text-primary me-2"></i>Kirimkan Kotak Pesan</h5>
                    
                    <!-- Alert Notifikasi Sukses -->
                    <?php if (!empty($pesan_sukses)) : ?>
                        <div class="alert alert-success alert-dismissible fade show rounded-3 small mb-3" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i> <?= $pesan_sukses; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Alert Notifikasi Error -->
                    <?php if (!empty($pesan_error)) : ?>
                        <div class="alert alert-danger alert-dismissible fade show rounded-3 small mb-3" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= $pesan_error; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form action="kontak.php" method="POST">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Nama Lengkap Pengirim</label>
                                <input type="text" name="nama" class="form-control rounded-3" placeholder="Contoh: Siti Aminah" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Email Pengirim</label>
                                <input type="email" name="email" class="form-control rounded-3" placeholder="siti@gmail.com" required>
                            </div>
                            <div class="col-lg-12">
                                <label class="form-label small fw-bold">Subjek Informasi</label>
                                <input type="text" name="subjek" class="form-control rounded-3" placeholder="Contoh: Pengajuan NUPTK Baru" required>
                            </div>
                            <div class="col-lg-12">
                                <label class="form-label small fw-bold">Isi Pesan / Keluhan</label>
                                <textarea name="pesan" class="form-control rounded-3" rows="5" placeholder="Tuliskan detail pertanyaan atau kendala data Anda di sini..." required></textarea>
                            </div>
                            <div class="col-lg-12 mt-4">
                                <button type="submit" class="btn btn-primary rounded-3 px-4 py-2 w-100 fw-bold shadow-sm">
                                    <i class="bi bi-send-fill me-2"></i> Kirim Masukan Anda
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>