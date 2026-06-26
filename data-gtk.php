<?php
// 1. Hubungkan ke database kamu (sesuaikan path foldernya jika berbeda)
include "config/database.php";

// 2. Query hitung otomatis jumlah personnel berdasarkan jabatan/kategori
$query_guru = mysqli_query($conn, "SELECT COUNT(*) AS total FROM ptk WHERE jabatan='Guru'");
$data_guru = mysqli_fetch_assoc($query_guru)['total'] ?? 0;

$query_kepsek = mysqli_query($conn, "SELECT COUNT(*) AS total FROM ptk WHERE jabatan='Kepala Sekolah'");
$data_kepsek = mysqli_fetch_assoc($query_kepsek)['total'] ?? 0;

$query_pengawas = mysqli_query($conn, "SELECT COUNT(*) AS total FROM ptk WHERE jabatan='Pengawas'");
$data_pengawas = mysqli_fetch_assoc($query_pengawas)['total'] ?? 0;

$query_admin = mysqli_query($conn, "SELECT COUNT(*) AS total FROM ptk WHERE jabatan='Tenaga Administrasi'");
$data_admin = mysqli_fetch_assoc($query_admin)['total'] ?? 0;
?>

<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Data GTK | Disdikbud Kota Bontang</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="assets/css/style.css" />

    <style>
      .topbar-logo {
        height: 40px;
        width: auto;
      }
      /* Efek hover agar pengguna tahu card bisa diklik */
      .card-clickable {
        cursor: pointer;
        transition: transform 0.2s, box-shadow 0.2s;
      }
      .card-clickable:hover {
        transform: translateY(-5px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
      }
    </style>
  </head>

  <body class="bg-light">
    <div class="topbar py-2 text-white small" style="background-color: #002d62">
      <div class="container">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
          <div class="d-flex align-items-center gap-3">
            <div class="d-flex align-items-center gap-2">
              <img src="assets/images/logo-bontang.png" alt="Logo Bontang" class="topbar-logo" />
              <img src="assets/images/logo-tutwuri.png" alt="Logo Pendidikan" class="topbar-logo" />
            </div>
            <span class="fw-bold">DINAS PENDIDIKAN DAN KEBUDAYAAN KOTA BONTANG</span>
          </div>
          <div><span>Bidang Pembinaan Tenaga Kependidikan</span></div>
        </div>
      </div>
    </div>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top shadow">
      <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">PTK BONTANG</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="menu">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a class="nav-link" href="index.php">Beranda</a></li>
            <li class="nav-item"><a class="nav-link" href="profil.html">Profil</a></li>
            <li class="nav-item"><a class="nav-link" href="struktur.html">Struktur</a></li>
            <li class="nav-item"><a class="nav-link active" href="data-gtk.php">Data GTK</a></li>
            <li class="nav-item"><a class="nav-link" href="kontak.html">Kontak</a></li>
          </ul>
        </div>
      </div>
    </nav>

    <header class="py-5 bg-white shadow-sm mb-5 text-center">
      <div class="container">
        <h1 class="fw-bold text-dark mb-2">Data GTK</h1>
        <p class="text-muted mb-0">Data Tenaga Kependidikan Kota Bontang</p>
        <hr class="mx-auto bg-primary" style="width: 50px; height: 3px" />
      </div>
    </header>

    <section class="container mb-5">
      <div class="row g-4 text-center">
        
        <div class="col-6 col-lg-3">
          <div class="card border-0 shadow-sm rounded-4 p-3 bg-white card-clickable" 
               data-bs-toggle="modal" data-bs-target="#modalDetail" data-kategori="Guru">
            <div class="text-primary mb-2"><i class="bi bi-person-video3 fs-2"></i></div>
            <h3 class="fw-bold text-dark mb-1"><?= number_format($data_guru, 0, ',', '.'); ?></h3>
            <span class="text-muted small fw-medium">Total Guru</span>
          </div>
        </div>

        <div class="col-6 col-lg-3">
          <div class="card border-0 shadow-sm rounded-4 p-3 bg-white card-clickable" 
               data-bs-toggle="modal" data-bs-target="#modalDetail" data-kategori="Kepala Sekolah">
            <div class="text-success mb-2"><i class="bi bi-person-workspace fs-2"></i></div>
            <h3 class="fw-bold text-dark mb-1"><?= number_format($data_kepsek, 0, ',', '.'); ?></h3>
            <span class="text-muted small fw-medium">Kepala Sekolah</span>
          </div>
        </div>

        <div class="col-6 col-lg-3">
          <div class="card border-0 shadow-sm rounded-4 p-3 bg-white card-clickable" 
               data-bs-toggle="modal" data-bs-target="#modalDetail" data-kategori="Pengawas">
            <div class="text-warning mb-2"><i class="bi bi-shield-check fs-2"></i></div>
            <h3 class="fw-bold text-dark mb-1"><?= number_format($data_pengawas, 0, ',', '.'); ?></h3>
            <span class="text-muted small fw-medium">Pengawas</span>
          </div>
        </div>

        <div class="col-6 col-lg-3">
          <div class="card border-0 shadow-sm rounded-4 p-3 bg-white card-clickable" 
               data-bs-toggle="modal" data-bs-target="#modalDetail" data-kategori="Tenaga Administrasi">
            <div class="text-purple mb-2" style="color: #6f42c1"><i class="bi bi-file-earmark-text fs-2"></i></div>
            <h3 class="fw-bold text-dark mb-1"><?= number_format($data_admin, 0, ',', '.'); ?></h3>
            <span class="text-muted small fw-medium">Tenaga Administrasi</span>
          </div>
        </div>

      </div>
    </section>

    <section class="container mb-5">
      <div class="row g-4">
        <div class="col-lg-6">
          <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
            <h5 class="fw-bold text-dark mb-4"><i class="bi bi-table text-primary me-2"></i>Data Statistik GTK</h5>
            <div class="table-responsive">
              <table class="table table-striped table-hover align-middle mb-0">
                <thead class="table-primary text-dark">
                  <tr>
                    <th style="width: 10%">No</th>
                    <th>Kategori</th>
                    <th class="text-end" style="width: 30%">Jumlah</th>
                  </tr>
                </thead>
                <tbody>
                  <tr><td>1</td><td>Guru</td><td class="text-end fw-bold"><?= number_format($data_guru, 0, ',', '.'); ?></td></tr>
                  <tr><td>2</td><td>Kepala Sekolah</td><td class="text-end fw-bold"><?= number_format($data_kepsek, 0, ',', '.'); ?></td></tr>
                  <tr><td>3</td><td>Pengawas</td><td class="text-end fw-bold"><?= number_format($data_pengawas, 0, ',', '.'); ?></td></tr>
                  <tr><td>4</td><td>Tenaga Administrasi</td><td class="text-end fw-bold"><?= number_format($data_admin, 0, ',', '.'); ?></td></tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
            <h5 class="fw-bold text-dark mb-3"><i class="bi bi-bar-chart-line text-success me-2"></i>Grafik Statistik</h5>
            <div class="p-2 border rounded-3 bg-light">
              <canvas id="grafikGtk" style="width: 100%; max-height: 280px"></canvas>
            </div>
          </div>
        </div>
      </div>
    </section>

    <div class="modal fade" id="modalDetail" tabindex="-1" aria-labelledby="modalDetailLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow rounded-4">
          <div class="modal-header bg-primary text-white border-0 py-3">
            <h5 class="modal-title fw-bold" id="modalDetailLabel">Detail Data</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body p-4">
            <div class="table-responsive">
              <table class="table table-bordered table-hover align-middle mb-0">
                <thead class="table-light">
                  <tr>
                    <th style="width: 8%">No</th>
                    <th>Nama Personnel</th>
                    <th>NIP / ID</th>
                    <th>Instansi / Sekolah</th>
                  </tr>
                </thead>
                <tbody id="tempat-data-detail">
                  <tr>
                    <td colspan="4" class="text-center text-muted py-4">Memuat data...</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <footer class="bg-dark text-white pt-5 pb-3 mt-5">
      <div class="container">
        <div class="text-center text-secondary small">© 2026 Bidang Pembinaan Tenaga Kependidikan Kota Bontang</div>
      </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
      // JAVASCRIPT UNTUK MENANGKAP KLIK PADA CARD DAN AMBIL DATA VIA AJAX
      const modalDetail = document.getElementById('modalDetail');
      if (modalDetail) {
        modalDetail.addEventListener('show.bs.modal', event => {
          const card = event.relatedTarget; 
          const kategori = card.getAttribute('data-kategori'); 
          
          // Update judul modal sesuai kategori card yang diklik
          const modalTitle = modalDetail.querySelector('.modal-title');
          modalTitle.textContent = 'Detail Data Personnel - ' + kategori;

          const tabelBody = document.getElementById('tempat-data-detail');
          tabelBody.innerHTML = '<tr><td colspan="4" class="text-center text-muted py-4"><div class="spinner-border spinner-border-sm text-primary me-2"></div> Memuat data...</td></tr>';

          // Mengambil data secara asynchronous dari file backend terpisah (get-detail.php)
          fetch('get-detail.php?kategori=' + encodeURIComponent(kategori))
            .then(response => response.text())
            .then(html => {
              tabelBody.innerHTML = html;
            })
            .catch(err => {
              tabelBody.innerHTML = '<tr><td colspan="4" class="text-center text-danger py-4">Gagal memuat data. silakan coba lagi.</td></tr>';
            });
        });
      }

      // Script Chart.js bawaan Anda
      const ctx = document.getElementById('grafikGtk').getContext('2d');
      new Chart(ctx, {
        type: 'bar',
        data: {
          labels: ['Guru', 'Kepala Sekolah', 'Pengawas', 'Administrasi'],
          datasets: [{
            label: 'Jumlah Personnel',
            data: [<?= $data_guru; ?>, <?= $data_kepsek; ?>, <?= $data_pengawas; ?>, <?= $data_admin; ?>],
            backgroundColor: ['rgba(13, 110, 253, 0.6)', 'rgba(25, 135, 84, 0.6)', 'rgba(255, 193, 7, 0.6)', 'rgba(111, 66, 193, 0.6)'],
            borderColor: ['#0d6efd', '#198754', '#ffc107', '#6f42c1'],
            borderWidth: 1.5,
            borderRadius: 6
          }]
        },
        options: {
          responsive: true,
          plugins: { legend: { display: true, position: 'top' } },
          scales: {
            y: { beginAtZero: true, grid: { color: 'rgba(0, 0, 0, 0.05)' } },
            x: { grid: { display: false } }
          }
        }
      });
    </script>
  </body>
</html>