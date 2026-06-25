<?php
include "config/database.php";

// 1. PERBAIKAN: Menambahkan huruf 'i' pada mysqli_query
$ambil_agenda = mysqli_query($conn, "SELECT * FROM agenda ORDER BY tanggal DESC LIMIT 3");
?>

<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Bidang Pembinaan Tenaga Kependidikan | Disdikbud Kota Bontang</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="assets/css/style.css" />
  </head>

  <body>
    <div class="topbar">
      <div class="container">
        <div class="d-flex justify-content-between">
          <span> DINAS PENDIDIKAN DAN KEBUDAYAAN KOTA BONTANG </span>
          <span> Bidang Pembinaan Tenaga Kependidikan </span>
        </div>
      </div>
    </div>

    <nav class="navbar navbar-expand-lg navbar-dark sticky-top shadow">
      <div class="container">
        <a class="navbar-brand" href="index.php"> PTK BONTANG </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menu">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a class="nav-link active" href="index.php"> Beranda </a></li>
            <li class="nav-item"><a class="nav-link" href="profil.html"> Profil </a></li>
            <li class="nav-item"><a class="nav-link" href="struktur.html"> Struktur </a></li>
            <li class="nav-item"><a class="nav-link" href="data-gtk.html"> Data GTK </a></li>
            <li class="nav-item"><a class="nav-link" href="kontak.html"> Kontak </a></li>
          </ul>
        </div>
      </div>
    </nav>

    <section class="hero">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-6">
            <span class="badge bg-warning text-dark px-3 py-2 mb-3">Portal Resmi PTK</span>
            <h1 class="display-3 fw-bold mb-4">Membangun Tenaga Kependidikan Berkualitas</h1>
            <p class="lead mb-4">Sistem informasi dan layanan terpadu Bidang Pembinaan Tenaga Kependidikan Dinas Pendidikan dan Kebudayaan Kota Bontang.</p>
            <div class="d-flex gap-3">
              <a href="kontak.html" class="btn btn-light btn-lg"><i class="bi bi-envelope"></i> Hubungi Kami</a>
              <a href="profil.html" class="btn btn-outline-light btn-lg">Profil Bidang</a>
            </div>
          </div>
          <div class="col-lg-6 text-center">
            <!--- Gambar Dashboard --->  
            <img src="assets/images/prof.jpeg" alt="PTK Bontang" class="img-fluid rounded-4 shadow-lg"/>
          </div>
        </div>
      </div>
    </section>

    <section class="floating-stats">
      <div class="container">
        <div class="row g-4">
          <div class="col-md-3"><div class="mini-stat"><h3>5.324</h3><p>Guru</p></div></div>
          <div class="col-md-3"><div class="mini-stat"><h3>243</h3><p>Kepala Sekolah</p></div></div>
          <div class="col-md-3"><div class="mini-stat"><h3>57</h3><p>Pengawas</p></div></div>
          <div class="col-md-3"><div class="mini-stat"><h3>189</h3><p>Tenaga Administrasi</p></div></div>
        </div>
      </div>
    </section>

    <section class="container py-5">
      <div class="sambutan-modern">
        <div class="row align-items-center">
          <div class="col-lg-4 text-center">
            <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?w=500" class="kepala-img">
          </div>
          <div class="col-lg-8">
            <span class="badge bg-primary mb-3">Sambutan Kepala Bidang</span>
            <h2 class="fw-bold mb-3">Selamat Datang di Portal PTK Bontang</h2>
            <p>Portal ini menjadi pusat informasi, layanan, dan pengembangan tenaga kependidikan di Kota Bontang.</p>
            <h5 class="mt-4 mb-0">Nama Kepala Bidang</h5>
            <small>Kepala Bidang Pembinaan Tenaga Kependidikan</small>
          </div>
        </div>
      </div>
    </section>

    <section class="container my-5">
      <div class="text-center mb-5">
        <h2 class="fw-bold text-dark">Akses Cepat</h2>
        <p class="text-muted">Layanan utama Bidang Pembinaan Tenaga Kependidikan</p>
      </div>

      <div class="row g-4 justify-content-center">
        <div class="col-md-6 col-lg-3">
          <div class="card border-0 shadow-sm text-center p-4 bg-light h-100 position-relative text-dark hover-card">
            <div class="mb-3 text-primary"><i class="bi bi-person-badge fs-1"></i></div>
            <h5 class="fw-bold mb-2">Data GTK</h5>
            <p class="text-muted small mb-0">Data guru, kepala sekolah, dan tenaga kependidikan</p>
            <a href="data-gtk.html" class="stretched-link"></a>
          </div>
        </div>

        <div class="col-md-6 col-lg-3">
          <div class="card border-0 shadow-sm text-center p-4 bg-light h-100 position-relative text-dark hover-card">
            <div class="mb-3 text-success"><i class="bi bi-card-heading fs-1"></i></div>
            <h5 class="fw-bold mb-2">NUPTK</h5>
            <p class="text-muted small mb-0">Cek status, validasi, dan riwayat NUPTK GTK</p>
            <a href="kelola.php?fitur=nuptk" class="stretched-link"></a>
          </div>
        </div>

        <div class="col-md-6 col-lg-3">
          <div class="card border-0 shadow-sm text-center p-4 bg-light h-100 position-relative text-dark hover-card">
            <div class="mb-3 text-warning"><i class="bi bi-mortarboard fs-1"></i></div>
            <h5 class="fw-bold mb-2">Sertifikasi</h5>
            <p class="text-muted small mb-0">Informasi tunjangan profesi dan status sertifikasi guru</p>
            <a href="kelola.php?fitur=sertifikasi" class="stretched-link"></a>
          </div>
        </div>

        <div class="col-md-6 col-lg-3">
          <div class="card border-0 shadow-sm text-center p-4 bg-light h-100 position-relative text-dark hover-card">
            <div class="mb-3 text-danger"><i class="bi bi-download fs-1"></i></div>
            <h5 class="fw-bold mb-2">Unduhan</h5>
            <p class="text-muted small mb-0">Berkas administrasi dan panduan layanan GTK</p>
            <a href="kelola.php?fitur=unduhan" class="stretched-link"></a>
          </div>
        </div>
      </div>
    </section>

    <section class="py-5">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-6">
            <h2 class="section-title">Tentang Bidang PTK</h2>
            <p>Bidang Pembinaan Tenaga Kependidikan bertugas melaksanakan pembinaan, pengembangan kompetensi, peningkatan kualitas sumber daya manusia, serta pengelolaan administrasi tenaga kependidikan di lingkungan satuan pendidikan Kota Bontang.</p>
            <a href="profil.html" class="btn btn-primary"> Selengkapnya </a>
          </div>
          <div class="col-lg-6">
            <div class="card border-0 shadow-lg rounded-4 p-4">
              <h4 class="mb-4">Fokus Utama</h4>
              <ul class="list-group list-group-flush">
                <li class="list-group-item">Peningkatan Kompetensi GTK</li>
                <li class="list-group-item">Sertifikasi Guru</li>
                <li class="list-group-item">Pelatihan dan Diklat</li>
                <li class="list-group-item">Pembinaan Administrasi GTK</li>
                <li class="list-group-item">Pengembangan SDM Pendidikan</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="py-5 bg-white">
      <div class="container">
        <div class="text-center mb-5">
          <h2 class="section-title">Berita Terbaru</h2>
        </div>
        <div class="row g-4">
          <?php
          $berita = mysqli_query($conn, "SELECT * FROM berita WHERE status='publish' ORDER BY id DESC LIMIT 3");
          while($row = mysqli_fetch_assoc($berita)):
          ?>
          <div class="col-lg-4">
            <div class="card news-card h-100">
              <img src="uploads/berita/<?= $row['thumbnail']; ?>" class="card-img-top" alt="Gambar Berita" style="height:220px;object-fit:cover;" onerror="this.onerror=null; this.src='https://placehold.co/600x400/e0e0e0/666666?text=No+Image';">
              <div class="card-body">
                <span class="badge bg-primary mb-3">Berita</span>
                <h5><?= $row['judul']; ?></h5>
                <p><?= substr($row['ringkasan'],0,100); ?>...</p>
                <a href="detail-berita.php?id=<?= $row['id']; ?>" class="btn btn-primary">Baca Selengkapnya</a>
              </div>
            </div>
          </div>
          <?php endwhile; ?>
        </div>
      </div>
    </section>

    <!--- PENGUMUMAN ---> 
  <section class="py-5 bg-light">
      <div class="container">
        <div class="text-center mb-5">
          <h2 class="section-title fw-bold">Pengumuman Terbaru</h2>
          <div class="mx-auto bg-warning rounded mt-2" style="width: 50px; height: 4px;"></div>
        </div>
        
        <div class="row g-4 justify-content-center">
          <?php
          // Mengambil 3 pengumuman terbaru dari database
          $query_pengumuman = mysqli_query($conn, "SELECT * FROM pengumuman ORDER BY id DESC LIMIT 3");
          
          if(mysqli_num_rows($query_pengumuman) > 0) {
              while($pengumuman = mysqli_fetch_assoc($query_pengumuman)):
          ?>
              <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm rounded-4 bg-white position-relative overflow-hidden" style="transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 25px rgba(13, 110, 253, 0.15)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 20px rgba(0,0,0,0.05)'">
                  
                  <div class="bg-primary" style="height: 5px; width: 100%;"></div>
                  
                  <div class="card-body p-4 d-flex flex-column h-100">
                    <div class="mb-3">
                      <span class="text-muted small d-inline-flex align-items-center bg-light px-2 py-1 rounded-3 border">
                        <i class="bi bi-calendar3 text-primary me-2"></i>
                        <?= date('d M Y', strtotime($pengumuman['created_at'])); ?>
                      </span>
                    </div>
                    
                    <h5 class="card-title fw-bold text-dark mb-3 lh-sm text-capitalize">
                      <?= htmlspecialchars($pengumuman['judul']); ?>
                    </h5>
                    
                    <p class="card-text text-secondary small flex-grow-1 lh-base text-break" style="display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; overflow: hidden;">
                      <?= nl2br(htmlspecialchars($pengumuman['isi'])); ?>
                    </p>
                    
                    <div class="border-top mt-3 pt-3 text-end">
                      <a href="detail-pengumuman.php?id=<?= $pengumuman['id']; ?>" class="text-primary fw-semibold small text-decoration-none stretched-link" style="font-size: 0.85rem;">
                        Lihat Detail <i class="bi bi-arrow-right ms-1"></i>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
          <?php 
              endwhile;
          } else {
          ?>
              <div class="col-12 text-center text-muted py-5">
                <div class="bg-white p-5 rounded-4 shadow-sm border">
                  <i class="bi bi-megaphone-fill fs-1 d-block mb-3 text-muted"></i>
                  <p class="mb-0 fw-medium">Belum ada pengumuman resmi saat ini.</p>
                </div>
              </div>
          <?php } ?>
        </div>
      </div>
    </section>

    <section class="py-5 bg-white">
      <div class="container">
        <div class="text-center mb-5">
          <h2 class="section-title">Agenda Kegiatan</h2>
        </div>
        <div class="row g-4">
          <?php 
          if(mysqli_num_rows($ambil_agenda) > 0) {
              while($agenda = mysqli_fetch_assoc($ambil_agenda)): 
          ?>
              <div class="col-md-4">
            <a href="detail-agenda.php?id=<?= $agenda['id']; ?>" class="text-decoration-none text-dark">
              <div class="card border-0 shadow-sm rounded-4 p-4 h-100 btn-hover-agenda" style="transition: transform 0.2s;">
                <h5 class="text-primary fw-bold">
                  <?= date('d F Y', strtotime($agenda['tanggal'])); ?>
                </h5>
                <p class="fw-bold mb-1"><?= $agenda['judul']; ?></p>
                <small class="text-muted d-block"><i class="bi bi-geo-alt"></i> <?= $agenda['lokasi']; ?></small>
              </div>
            </a>
          </div>
          <?php 
              endwhile; 
          } else { 
          ?>
              <div class="col-12 text-center text-muted">Belum ada agenda kegiatan saat ini.</div>
          <?php } ?>
        </div>
      </div>
    </section>

    <footer class="footer">
      <div class="container">
        <div class="row">
          <div class="col-lg-4">
            <h4>PTK BONTANG</h4>
            <p>Website resmi Bidang Pembinaan Tenaga Kependidikan Dinas Pendidikan dan Kebudayaan Kota Bontang.</p>
          </div>
          <div class="col-lg-4">
            <h5>Menu Cepat</h5>
            <ul class="list-unstyled">
              <li>Profil</li>
              <li>Struktur Organisasi</li>
              <li>Data GTK</li>
              </ul>
          </div>
          <div class="col-lg-4">
            <h5>Kontak</h5>
            <p>Dinas Pendidikan dan Kebudayaan Kota Bontang</p>
            <p>Email: -------</p>
          </div>
        </div>
        <hr>
        <div class="text-center">© 2026 Bidang Pembinaan Tenaga Kependidikan Kota Bontang</div>
      </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>