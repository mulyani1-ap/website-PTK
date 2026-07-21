<?php
include "config/database.php";

$jenjang = isset($_GET['jenjang']) ? mysqli_real_escape_string($conn, $_GET['jenjang']) : '';
$status  = isset($_GET['status']) ? mysqli_real_escape_string($conn, $_GET['status']) : '';

$filter_sql = "";
if (!empty($jenjang)) {
    $filter_sql .= " AND `sekolah_asal` LIKE '%$jenjang%'";
}

// Aturan Cerdas: Mendeteksi sekolah Negeri berdasarkan kata kunci penciri umum
$negeri_keywords = "(`sekolah_asal` LIKE '%Negeri%' 
                    OR `sekolah_asal` LIKE '%SDN%' 
                    OR `sekolah_asal` LIKE '%SMPN%' 
                    OR `sekolah_asal` LIKE '%TKN%' 
                    OR `sekolah_asal` LIKE '% N %' 
                    OR `sekolah_asal` LIKE '% N.%')";

if (!empty($status)) {
    if ($status == 'Negeri') {
        // Jika pilih Negeri, cari yang mengandung kata kunci Negeri
        $filter_sql .= " AND $negeri_keywords";
    } elseif ($status == 'Swasta') {
        // Jika pilih Swasta, cari sekolah yang TIDAK mengandung kata kunci Negeri
        $filter_sql .= " AND NOT $negeri_keywords";
    }
}

$query_guru = mysqli_query($conn, "SELECT COUNT(*) AS total FROM `ptk` WHERE `jabatan`='Guru' $filter_sql");
$data_guru = mysqli_fetch_assoc($query_guru)['total'] ?? 0;

$query_kepsek = mysqli_query($conn, "SELECT COUNT(*) AS total FROM `ptk` WHERE `jabatan`='Kepala Sekolah' $filter_sql");
$data_kepsek = mysqli_fetch_assoc($query_kepsek)['total'] ?? 0;

$query_pengawas = mysqli_query($conn, "SELECT COUNT(*) AS total FROM `ptk` WHERE `jabatan`='Pengawas' $filter_sql");
$data_pengawas = mysqli_fetch_assoc($query_pengawas)['total'] ?? 0;

$query_admin = mysqli_query($conn, "SELECT COUNT(*) AS total FROM `ptk` WHERE `jabatan`='Tenaga Administrasi' $filter_sql");
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

    <style>
      .topbar-logo { height: 40px; width: auto; }
      .card-clickable { cursor: pointer; transition: transform 0.2s, box-shadow 0.2s; }
      .card-clickable:hover { transform: translateY(-5px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; }
    </style>
  </head>

  <body class="bg-light">
    <!-- -->
    <div class="topbar py-2 text-white small" style="background-color: #002d62">
      <div class="container">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
          <div class="d-flex align-items-center gap-3">
            <div class="d-flex align-items-center gap-2">
              <img src="assets/images/logo-bontang.png" alt="Logo Bontang" class="topbar-logo" onerror="this.style.display='none'"/>
              <img src="assets/images/logo-tutwuri.png" alt="Logo Pendidikan" class="topbar-logo" onerror="this.style.display='none'"/>
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
            <li class="nav-item"><a class="nav-link active" href="data_gtk.php">Data GTK</a></li>
            <li class="nav-item"><a class="nav-link" href="kontak.php">Kontak</a></li>
          </ul>
        </div>
      </div>
    </nav>

    <header class="py-4 bg-white shadow-sm mb-4 text-center">
      <div class="container">
        <h1 class="fw-bold text-dark mb-1">Pencarian & Statistik GTK</h1>
        <p class="text-muted mb-0">Kelola dan telusuri infografis Tenaga Kependidikan Kota Bontang</p>
      </div>
    </header>

    <main class="container mb-5">
        <!-- -->
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
            <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-search text-primary me-2"></i>Papan Pencarian Berdasarkan Wilayah Kerja</h5>
            <form action="data_gtk.php" method="GET" class="row g-3">
                <div class="col-md-5">
                    <label class="form-label small fw-bold text-secondary">Jenjang Sekolah</label>
                    <select name="jenjang" class="form-select rounded-3">
                        <option value="">-- Semua Jenjang --</option>
                        <option value="TK" <?= $jenjang == 'TK' ? 'selected' : ''; ?>>TK / PAUD</option>
                        <option value="SD" <?= $jenjang == 'SD' ? 'selected' : ''; ?>>SD / MI</option>
                        <option value="SMP" <?= $jenjang == 'SMP' ? 'selected' : ''; ?>>SMP / MTs</option>
                    </select>
                </div>
                <div class="col-md-5">
                    <label class="form-label small fw-bold text-secondary">Status Instansi</label>
                    <select name="status" class="form-select rounded-3">
                        <option value="">-- Semua Status --</option>
                        <option value="Negeri" <?= $status == 'Negeri' ? 'selected' : ''; ?>>Negeri</option>
                        <option value="Swasta" <?= $status == 'Swasta' ? 'selected' : ''; ?>>Swasta</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary rounded-3 w-100 py-2 fw-bold">
                        <i class="bi bi-filter me-1"></i> Submit
                    </button>
                </div>
            </form>

            <?php if (!empty($jenjang) || !empty($status)) { ?>
                <div class="mt-3 small text-muted d-flex align-items-center justify-content-between bg-light p-2 rounded-3">
                    <div>
                        Menampilkan Statistik Filter: 
                        <?php if(!empty($jenjang)) echo '<span class="badge bg-primary me-1">Jenjang: '.$jenjang.'</span>'; ?>
                        <?php if(!empty($status)) echo '<span class="badge bg-success me-1">Status: '.$status.'</span>'; ?>
                    </div>
                    <a href="data_gtk.php" class="text-danger text-decoration-none small fw-bold"><i class="bi bi-x-circle-fill"></i> Reset Filter</a>
                </div>
            <?php } ?>
        </div>

        <!-- -->
        <div class="row g-4 text-center mb-5">
            <div class="col-6 col-lg-3">
              <div class="card border-0 shadow-sm rounded-4 p-3 bg-white card-clickable" 
                   data-bs-toggle="modal" data-bs-target="#modalDetail" data-kategori="Guru">
                <div class="text-primary mb-2"><i class="bi bi-person-video3 fs-2"></i></div>
                <h3 class="fw-bold text-dark mb-1"><?= number_format($data_guru, 0, ',', '.'); ?></h3>
                <span class="text-muted small fw-medium">Guru</span>
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
                <div class="mb-2" style="color: #6f42c1"><i class="bi bi-file-earmark-text fs-2"></i></div>
                <h3 class="fw-bold text-dark mb-1"><?= number_format($data_admin, 0, ',', '.'); ?></h3>
                <span class="text-muted small fw-medium">Tenaga Administrasi</span>
              </div>
            </div>
        </div>

        <!-- -->
        <div class="row g-4">
            <div class="col-lg-6">
              <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                <h5 class="fw-bold text-dark mb-4"><i class="bi bi-table text-primary me-2"></i>Data Rincian Terfilter</h5>
                <div class="table-responsive">
                  <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-primary text-dark">
                      <tr>
                        <th style="width: 10%">No</th>
                        <th>Kategori Personnel</th>
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
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-bar-chart-line text-success me-2"></i>Grafik Visual Terfilter</h5>
                <div class="p-2 border rounded-3 bg-light">
                  <canvas id="grafikGtk" style="width: 100%; max-height: 280px"></canvas>
                </div>
              </div>
            </div>
        </div>
    </main>

    <!-- -->
    <div class="modal fade" id="modalDetail" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow rounded-4">
          <div class="modal-header bg-primary text-white border-0 py-3">
            <h5 class="modal-title fw-bold">Detail Data</h5>
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
                  <tr><td colspan="4" class="text-center text-muted py-4">Memuat data...</td></tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <footer class="bg-dark text-white pt-4 pb-3 mt-5">
      <div class="container text-center text-secondary small">
        © 2026 Bidang Pembinaan Tenaga Kependidikan Kota Bontang
      </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
      let currentChart = null;

      const modalDetail = document.getElementById('modalDetail');
      if (modalDetail) {
        modalDetail.addEventListener('show.bs.modal', event => {
          const card = event.relatedTarget; 
          const kategori = card.getAttribute('data-kategori'); 
          
          modalDetail.querySelector('.modal-title').textContent = 'Detail Data Personnel - ' + kategori;
          const tabelBody = document.getElementById('tempat-data-detail');
          tabelBody.innerHTML = '<tr><td colspan="4" class="text-center text-muted py-4"><div class="spinner-border spinner-border-sm text-primary me-2"></div> Memuat data...</td></tr>';

          const url = `get-detail.php?kategori=${encodeURIComponent(kategori)}&jenjang=${encodeURIComponent('<?= $jenjang; ?>')}&status=${encodeURIComponent('<?= $status; ?>')}`;

          fetch(url)
            .then(response => response.text())
            .then(html => { tabelBody.innerHTML = html; })
            .catch(err => {
              tabelBody.innerHTML = '<tr><td colspan="4" class="text-center text-danger py-4">Gagal memuat detail data. Silakan coba lagi.</td></tr>';
            });
        });
      }

      const ctx = document.getElementById('grafikGtk').getContext('2d');
      currentChart = new Chart(ctx, {
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
          plugins: { legend: { display: false } },
          scales: {
            y: { beginAtZero: true, grid: { color: 'rgba(0, 0, 0, 0.05)' } },
            x: { grid: { display: false } }
          }
        }
      });
    </script>
  </body>
</html>