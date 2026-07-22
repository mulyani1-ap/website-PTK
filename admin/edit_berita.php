<?php
session_start();

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}

include "../config/database.php";

// Ambil ID dari URL
$id_berita = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Tarik data berita yang mau diedit
$query = mysqli_query($conn, "SELECT * FROM berita WHERE id = '$id_berita'");
if(mysqli_num_rows($query) == 0) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='berita.php';</script>";
    exit;
}
$data = mysqli_fetch_assoc($query);

// Proses Update Data
if(isset($_POST['update']))
{
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $ringkasan = mysqli_real_escape_string($conn, $_POST['ringkasan']);
    $isi = mysqli_real_escape_string($conn, $_POST['isi']);
    $youtube_url = mysqli_real_escape_string($conn, $_POST['youtube_url']);
    $status = $_POST['status'];

    $slug = strtolower(str_replace(' ', '-', $judul));

    // 1. Cek apakah admin mengunggah thumbnail baru
    $query_update_thumb = ""; 
    if(!empty($_FILES['thumbnail']['name'])) {
        $thumbnail = $_FILES['thumbnail'];
        $namaFile = time() ."_". str_replace(' ', '_', $thumbnail['name']);
        
        if(move_uploaded_file($thumbnail['tmp_name'], "../uploads/berita/" .$namaFile)) {
            // Hapus thumbnail lama dari folder jika ada
            if(!empty($data['thumbnail']) && file_exists("../uploads/berita/" . $data['thumbnail'])) {
                unlink("../uploads/berita/" . $data['thumbnail']);
            }
            $query_update_thumb = ", thumbnail = '$namaFile'"; // Tambahkan ke query update
        }
    }

    // 2. Update Berita Utama ke Database
    mysqli_query($conn, "UPDATE berita SET 
        judul = '$judul', 
        slug = '$slug', 
        ringkasan = '$ringkasan', 
        isi = '$isi', 
        youtube_url = '$youtube_url', 
        status = '$status' 
        $query_update_thumb 
        WHERE id = '$id_berita'
    ");

    // 3. Proses Tambah Multi-upload Media & Dokumen (Tabel berita_media)
    if (!empty($_FILES['berita_media']['name'][0])) {
        $total_files = count($_FILES['berita_media']['name']);
        
        for ($i = 0; $i < $total_files; $i++) {
            $nama_media = $_FILES['berita_media']['name'][$i];
            $tmp_media  = $_FILES['berita_media']['tmp_name'][$i];
            $type_media = $_FILES['berita_media']['type'][$i];
            
            $nama_media_baru = time() . "_" . $i . "_" . str_replace(' ', '_', $nama_media);
            
            // Deteksi otomatis apakah file berupa video, file dokumen, atau gambar
            if (strpos($type_media, 'video') !== false) {
                $tipe_media = 'video';
            } elseif (strpos($type_media, 'application') !== false || strpos($type_media, 'text') !== false) {
                $tipe_media = 'file';
            } else {
                $tipe_media = 'image';
            }
            
            if (move_uploaded_file($tmp_media, "../uploads/berita/" . $nama_media_baru)) {
                mysqli_query(
                    $conn,
                    "INSERT INTO berita_media (berita_id, file_path, tipe) 
                     VALUES ('$id_berita', '$nama_media_baru', '$tipe_media')"
                );
            }
        }
    }

    echo "<script>alert('Berita berhasil diperbarui!'); window.location.href='berita.php';</script>";
    exit;
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

<div class="container" style="max-width: 850px;">
    <div class="card border-0 shadow-sm p-4 rounded-4 bg-white">
        <h3 class="fw-bold mb-4 text-dark"><i class="bi bi-pencil-square text-warning me-2"></i>Edit Berita</h3>
        
        <form action="" method="POST" enctype="multipart/form-data">
            
            <div class="mb-3">
                <label class="form-label fw-semibold">Judul Berita <span class="text-danger">*</span></label>
                <input type="text" name="judul" class="form-control" value="<?= htmlspecialchars($data['judul']); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Ringkasan Berita <span class="text-danger">*</span></label>
                <textarea name="ringkasan" class="form-control" rows="2" required><?= htmlspecialchars($data['ringkasan']); ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Isi Lengkap Berita <span class="text-danger">*</span></label>
                <textarea name="isi" class="form-control" rows="6" required><?= htmlspecialchars($data['isi']); ?></textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Status Berita</label>
                    <select name="status" class="form-select">
                        <option value="publish" <?= ($data['status'] == 'publish') ? 'selected' : ''; ?>>Publish</option>
                        <option value="draft" <?= ($data['status'] == 'draft') ? 'selected' : ''; ?>>Draft</option>
                    </select>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">URL Video YouTube</label>
                    <input type="text" name="youtube_url" class="form-control" value="<?= htmlspecialchars($data['youtube_url'] ?? ''); ?>">
                </div>
            </div>

            <hr class="my-4">
            <h5 class="fw-bold text-secondary mb-3"><i class="bi bi-folder-plus me-2"></i>Media & Lampiran</h5>

            <div class="row">
                <div class="col-md-5 mb-4">
                    <label class="form-label fw-semibold">Ganti Thumbnail Utama</label>
                    <?php if(!empty($data['thumbnail'])): ?>
                        <div class="mb-2">
                            <img src="../uploads/berita/<?= $data['thumbnail']; ?>" class="img-thumbnail" style="max-height: 100px;">
                        </div>
                    <?php endif; ?>
                    <input type="file" name="thumbnail" class="form-control" accept="image/*">
                    <small class="text-muted">Abaikan jika tidak ingin mengganti gambar sampul.</small>
                </div>

                <div class="col-md-7 mb-4">
                    <label class="form-label fw-semibold">Tambah Lampiran Baru (Multi-Upload)</label>
                    <!-- Ini yang bikin kamu bisa upload file dokumen saat edit -->
                    <input type="file" name="berita_media[]" class="form-control" accept="image/*,video/*,.pdf,.doc,.docx,.xls,.xlsx" multiple>
                    <small class="text-muted">Tahan <kbd>Ctrl</kbd> untuk memilih lebih dari 1 file sekaligus (Gambar, Video, PDF, Word, dll). File ini akan ditambahkan ke lampiran yang sudah ada.</small>
                </div>
            </div>

            <div class="d-flex gap-2 border-top pt-4">
                <button type="submit" name="update" class="btn btn-warning px-4 text-dark fw-semibold">
                    <i class="bi bi-save me-2"></i>Simpan Perubahan
                </button>
                <a href="berita.php" class="btn btn-light px-4 border">Batal</a>
            </div>

        </form>
    </div>
</div>

</body>
</html>