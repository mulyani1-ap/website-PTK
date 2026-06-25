<?php include "header.php"; ?>

<main class="py-5 bg-light" style="min-height: 80vh;">
  <div class="container">
    <div class="text-center mb-5">
      <span class="badge bg-primary px-3 py-2 mb-2">Pusat Layanan</span>
      <h2 class="fw-bold text-dark display-5">Layanan Bidang PTK</h2>
      <p class="text-muted">Informasi layanan dan administrasi tenaga kependidikan secara terpadu</p>
    </div>

    <div class="row g-4 justify-content-center">
      <div class="col-md-6 col-lg-4">
        <div class="card border-0 shadow-sm text-center p-4 bg-white h-100 rounded-4 transition-card">
          <div class="mb-3 text-warning"><i class="bi bi-award fs-1"></i></div>
          <h4 class="fw-bold mb-3">Sertifikasi Guru</h4>
          <p class="text-muted small mb-4">Informasi lengkap pengajuan, validasi data, dan pencairan tunjangan profesi guru.</p>
          <a href="detail-layanan.php?jenis=sertifikasi" class="btn btn-primary w-100 rounded-pill">Detail Layanan</a>
        </div>
      </div>

      <div class="col-md-6 col-lg-4">
        <div class="card border-0 shadow-sm text-center p-4 bg-white h-100 rounded-4 transition-card">
          <div class="mb-3 text-primary"><i class="bi bi-card-heading fs-1"></i></div>
          <h4 class="fw-bold mb-3">NUPTK</h4>
          <p class="text-muted small mb-4">Pengelolaan, pengusulan baru, serta pembaruan data status Nomor Unik Pendidik dan Tenaga Kependidikan.</p>
          <a href="detail-layanan.php?jenis=nuptk" class="btn btn-primary w-100 rounded-pill">Detail Layanan</a>
        </div>
      </div>

      <div class="col-md-6 col-lg-4">
        <div class="card border-0 shadow-sm text-center p-4 bg-white h-100 rounded-4 transition-card">
          <div class="mb-3 text-success"><i class="bi bi-book fs-1"></i></div>
          <h4 class="fw-bold mb-3">Diklat & Pelatihan</h4>
          <p class="text-muted small mb-4">Peningkatan kompetensi GTK melalui program diklat, workshop, dan pelatihan berkelanjutan.</p>
          <a href="detail-layanan.php?jenis=diklat" class="btn btn-primary w-100 rounded-pill">Detail Layanan</a>
        </div>
      </div>
    </div>
  </div>
</main>

<?php include "footer.php"; ?>