<?php
// 1. Koneksi ke Database (Sesuaikan nama database kamu, di sini contohnya ptk_bontang)
$koneksi = mysqli_connect("localhost", "root", "", "ptk_bontang");

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// 2. Ambil parameter fitur dari URL (default ke nuptk)
$fitur = isset($_GET['fitur']) ? $_GET['fitur'] : 'nuptk';

// 3. Atur Judul, Icon, dan Teks Tombol Tambah sesuai tombol yang diklik
switch ($fitur) {
    case 'nuptk':
        $judul = "Data NUPTK";
        $icon = "bi-card-heading text-success";
        $teks_tambah = "Tambah Data NUPTK";
        $query = mysqli_query($koneksi, "SELECT * FROM agenda ORDER BY id DESC"); 
        break;
        
    case 'sertifikasi':
        $judul = "Data Sertifikasi";
        $icon = "bi-mortarboard text-warning";
        $teks_tambah = "Tambah Data Sertifikasi";
        $query = mysqli_query($koneksi, "SELECT * FROM data_gtk ORDER BY id DESC"); 
        break;
        
    case 'unduhan':
        $judul = "Data Unduhan Berkas";
        $icon = "bi-download text-danger";
        // Mengubah teks tombol atas khusus untuk fitur unduhan berkas
        $teks_tambah = "Upload File Baru"; 
        $query = mysqli_query($koneksi, "SELECT * FROM pengumuman ORDER BY id DESC"); 
        break;
        
    default:
        $judul = "Data Layanan";
        $icon = "bi-grid";
        $teks_tambah = "Tambah Data";
        $query = mysqli_query($koneksi, "SELECT * FROM agenda ORDER BY id DESC");
        break;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $judul; ?> | PTK Bontang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top shadow mb-5">
      <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">PTK BONTANG</a>
        <div class="navbar-nav ms-auto">
            <a class="nav-link text-white" href="index.php"><i class="bi bi-arrow-left-short"></i> Kembali ke Beranda</a>
        </div>
      </div>
    </nav>

    <div class="container">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold text-dark mb-1"><i class="bi <?php echo $icon; ?> me-2"></i><?php echo $judul; ?></h2>
                    <p class="text-muted small mb-0">Menampilkan seluruh data log info yang tersimpan di database.</p>
                </div>
                <a href="form-tambah.php?fitur=<?php echo $fitur; ?>" class="btn btn-primary rounded-3 shadow-sm">
                    <i class="bi bi-plus-lg me-1"></i> <?php echo $teks_tambah; ?>
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    
                    <?php if ($fitur == 'nuptk' || $fitur == 'default') { ?>
                        <thead class="table-primary">
                            <tr>
                                <th style="width: 8%;">No</th>
                                <th>Judul / Nama Agenda</th>
                                <th>Tanggal</th>
                                <th>Lokasi</th>
                                <th>Keterangan / Deskripsi</th>
                                <th class="text-center" style="width: 15%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            if (mysqli_num_rows($query) > 0) {
                                while ($data = mysqli_fetch_assoc($query)) {
                            ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td class="fw-semibold"><?php echo htmlspecialchars($data['judul']); ?></td>
                                    <td><i class="bi bi-calendar3 text-muted me-1"></i> <?php echo isset($data['tanggal']) ? date('d M Y', strtotime($data['tanggal'])) : '-'; ?></td>
                                    <td><i class="bi bi-geo-alt text-muted me-1"></i> <?php echo htmlspecialchars($data['lokasi'] ?? '-'); ?></td>
                                    <td><span class="text-muted small"><?php echo htmlspecialchars($data['deskripsi'] ?? '-'); ?></span></td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <a href="form-edit.php?fitur=<?php echo $fitur; ?>&id=<?php echo $data['id']; ?>" class="btn btn-outline-warning"><i class="bi bi-pencil-square"></i></a>
                                            <a href="proses-hapus.php?fitur=<?php echo $fitur; ?>&id=<?php echo $data['id']; ?>" class="btn btn-outline-danger" onclick="return confirm('Yakin ingin menghapus data ini?')"><i class="bi bi-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            <?php 
                                }
                            } else {
                                echo "<tr><td colspan='6' class='text-center py-4 text-muted'>Belum ada data untuk kategori ini.</td></tr>";
                            }
                            ?>
                        </tbody>

                    <?php } else if ($fitur == 'sertifikasi') { ?>
                        <thead class="table-warning text-dark">
                            <tr>
                                <th style="width: 8%;">No</th>
                                <th>Judul Berita / Sertifikasi</th>
                                <th>Isi Berita</th>
                                <th class="text-center" style="width: 15%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            if (mysqli_num_rows($query) > 0) {
                                while ($data = mysqli_fetch_assoc($query)) {
                            ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td class="fw-semibold"><?php echo htmlspecialchars($data['judul']); ?></td>
                                    <td><span class="text-muted small"><?php echo htmlspecialchars($data['isi'] ?? $data['deskripsi'] ?? '-'); ?></span></td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <a href="form-edit.php?fitur=<?php echo $fitur; ?>&id=<?php echo $data['id']; ?>" class="btn btn-outline-warning"><i class="bi bi-pencil-square"></i></a>
                                            <a href="proses-hapus.php?fitur=<?php echo $fitur; ?>&id=<?php echo $data['id']; ?>" class="btn btn-outline-danger" onclick="return confirm('Yakin ingin menghapus data ini?')"><i class="bi bi-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            <?php 
                                }
                            } else {
                                echo "<tr><td colspan='4' class='text-center py-4 text-muted'>Belum ada data untuk kategori ini.</td></tr>";
                            }
                            ?>
                        </tbody>

                    <?php } else if ($fitur == 'unduhan') { ?>
                        <thead class="table-danger text-white">
                            <tr>
                                <th style="width: 8%;">No</th>
                                <th>Judul Berkas</th>
                                <th>Keterangan</th>
                                <th class="text-center" style="width: 25%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            if (mysqli_num_rows($query) > 0) {
                                while ($data = mysqli_fetch_assoc($query)) {
                            ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td class="fw-semibold"><?php echo htmlspecialchars($data['judul']); ?></td>
                                    <td><span class="text-muted small"><?php echo htmlspecialchars($data['isi'] ?? '-'); ?></span></td>
                                    <td class="text-center">
                                        <a href="downloads/<?php echo $data['file']; ?>" class="btn btn-sm btn-success rounded-3 me-2" download>
                                            <i class="bi bi-download me-1"></i> Download File
                                        </a>
                                        
                                        <a href="proses-hapus.php?fitur=<?php echo $fitur; ?>&id=<?php echo $data['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin ingin menghapus berkas ini?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php 
                                }
                            } else {
                                echo "<tr><td colspan='4' class='text-center py-4 text-muted'>Belum ada berkas yang siap diunduh.</td></tr>";
                            }
                            ?>
                        </tbody>
                    <?php } ?>

                </table>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>