<?php
session_start();

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}

include "../config/database.php";

// 1. Ambil ID Berita dari parameter URL
if (!isset($_GET['id'])) {
    header("Location: berita.php");
    exit;
}
$id = mysqli_real_escape_string($conn, $_GET['id']);

// 2. Ambil data berita lama berdasarkan ID
$query = mysqli_query($conn, "SELECT * FROM berita WHERE id = '$id'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>alert('Data berita tidak ditemukan!'); window.location='berita.php';</script>";
    exit;
}

// 3. Proses ketika tombol Update ditekan
if (isset($_POST['update'])) {
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $ringkasan = mysqli_real_escape_string($conn, $_POST['ringkasan']);
    $isi = mysqli_real_escape_string($conn, $_POST['isi']);
    $status = $_POST['status'];

    $slug = strtolower(str_replace(' ', '-', $judul));

    // Cek apakah user mengupload gambar thumbnail baru
    if (!empty($_FILES['thumbnail']['name'])) {
        $thumbnail = $_FILES['thumbnail'];
        $namaFile = time() . "_" . $thumbnail['name'];
        
        // Hapus file thumbnail lama di folder biar hemat storage
        if (!empty($data['thumbnail']) && file_exists("../uploads/berita/" . $data['thumbnail'])) {
            unlink("../uploads/berita/" . $data['thumbnail']);
        }
        
        // Upload file thumbnail baru
        move_uploaded_file($thumbnail['tmp_name'], "../uploads/berita/" . $namaFile);

        // Query update beserta thumbnail baru
        $update_query = "UPDATE berita SET judul='$judul', slug='$slug', ringkasan='$ringkasan', isi='$isi', status='$status', thumbnail='$namaFile' WHERE id='$id'";
    } else {
        // Query update tanpa mengubah thumbnail lama
        $update_query = "UPDATE berita SET judul='$judul', slug='$slug', ringkasan='$ringkasan', isi='$isi', status='$status' WHERE id='$id'";
    }

    $proses_update = mysqli_query($conn, $update_query);

    // 4. Proses Multi-upload Media Pendukung Baru (Jika Ada)
    if (!empty($_FILES['berita_media']['name'][0])) {
        $total_files = count($_FILES['berita_media']['name']);
        
        for ($i = 0; $i < $total_files; $i++) {
            $nama_media = $_FILES['berita_media']['name'][$i];
            $tmp_media  = $_FILES['berita_media']['tmp_name'][$i];
            $type_media = $_FILES['berita_media']['type'][$i];
            
            $nama_media_baru = time() . "_" . $i . "_" . $nama_media;
            $tipe_media = (strpos($type_media, 'video') !== false) ? 'video' : 'image';
            
            if (move_uploaded_file($tmp_media, "../uploads/berita/" . $nama_media_baru)) {
                mysqli_query($conn, "INSERT INTO berita_media (berita_id, file_path, tipe) VALUES ('$id', '$nama_media_baru', '$tipe_media')");
            }
        }
    }

    if ($proses_update) {
        echo "<script>alert('Berita berhasil diperbarui!'); window.location='berita.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui berita: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Berita | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light py-5">

<div class="container" style="max-width: 750px;">
    <div class="card border-0 shadow-sm p-4 rounded-4 bg-white">
        <h3 class="fw-bold mb-4 text-dark"><i class="bi bi-pencil-square text-warning me-2"></i>Edit Berita</h3>
        
        <form action="" method="POST" enctype="multipart/form-data">
            
            <div class="mb-3">
                <label class="form-label fw-semibold">Judul Berita</label>
                <input type="text" name="judul" class="form-control" value="<?= htmlspecialchars($data['judul']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Ringkasan Berita</label>
                <textarea name="ringkasan" class="form-control" rows="2" required><?= htmlspecialchars($data['ringkasan']) ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Isi Lengkap Berita</label>
                <textarea name="isi" class="form-control" rows="6" required><?= htmlspecialchars($data['isi']) ?></textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Status Berita</label>
                    <select name="status" class="form-select">
                        <option value="publish" <?= $data['status'] == 'publish' ? 'selected' : '' ?>>Publish</option>
                        <option value="draft" <?= $data['status'] == 'draft' ? 'selected' : '' ?>>Draft</option>
                    </select>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Ganti Thumbnail Utama</label>
                    <input type="file" name="thumbnail" class="form-control" accept="image/*">
                    <div class="form-text text-muted mt-1">
                        Gambar saat ini: <code class="text-danger"><?= $data['thumbnail'] ?></code>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Tambah Media Pendukung Baru (Opsional)</label>
                <input type="file" name="berita_media[]" class="form-control" accept="image/*,video/*" multiple>
                <small class="text-muted">Kosongkan jika tidak ingin menambah file dokumentasi baru.</small>
            </div>

            <div class="d-flex gap-2 border-top pt-3">
                <button type="submit" name="update" class="btn btn-warning text-dark fw-semibold px-4">
                    <i class="bi bi-save me-2"></i>Simpan Perubahan
                </button>
                <a href="berita.php" class="btn btn-light px-4">Batal</a>
            </div>

        </form>
    </div>
</div>
</body>
</html>