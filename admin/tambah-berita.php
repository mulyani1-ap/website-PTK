<?php
session_start();

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}

include "../config/database.php";

if(isset($_POST['simpan']))
{
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $ringkasan = mysqli_real_escape_string($conn, $_POST['ringkasan']);
    $isi = mysqli_real_escape_string($conn, $_POST['isi']);
    $status = $_POST['status'];

    $slug = strtolower(
        str_replace(' ', '-', $judul)
    );

    // 1. Upload Thumbnail Utama
    $thumbnail = $_FILES['thumbnail'];
    $namaFile = time() ."_". $thumbnail['name'];
    move_uploaded_file(
      $thumbnail['tmp_name'],
      "../uploads/berita/" .$namaFile
    );

    // 2. Simpan Berita Utama ke Database
    mysqli_query(
        $conn,
        "INSERT INTO berita
        (
            judul,
            slug,
            ringkasan,
            isi,
            thumbnail,
            status,
            user_id
        )
        VALUES
        (
            '$judul',
            '$slug',
            '$ringkasan',
            '$isi',
            '$namaFile',
            '$status',
            1
        )"
    );

    // 3. Ambil ID Berita yang baru saja dimasukkan
    $berita_id = mysqli_insert_id($conn);

    // 4. Proses Multi-upload Media Pendukung
    if (!empty($_FILES['berita_media']['name'][0])) {
        $total_files = count($_FILES['berita_media']['name']);
        
        for ($i = 0; $i < $total_files; $i++) {
            $nama_media = $_FILES['berita_media']['name'][$i];
            $tmp_media  = $_FILES['berita_media']['tmp_name'][$i];
            $type_media = $_FILES['berita_media']['type'][$i];
            
            $nama_media_baru = time() . "_" . $i . "_" . $nama_media;
            $tipe_media = (strpos($type_media, 'video') !== false) ? 'video' : 'image';
            
            if (move_uploaded_file($tmp_media, "../uploads/berita/" . $nama_media_baru)) {
                mysqli_query(
                    $conn,
                    "INSERT INTO berita_media (berita_id, file_path, tipe) 
                     VALUES ('$berita_id', '$nama_media_baru', '$tipe_media')"
                );
            }
        }
    }

    header("Location: berita.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Berita | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light py-5">

<div class="container" style="max-width: 750px;">
    <div class="card border-0 shadow-sm p-4 rounded-4 bg-white">
        <h3 class="fw-bold mb-4 text-dark"><i class="bi bi-journal-plus text-primary me-2"></i>Tambah Berita Baru</h3>
        
        <form action="" method="POST" enctype="multipart/form-data">
            
            <div class="mb-3">
                <label class="form-label fw-semibold">Judul Berita</label>
                <input type="text" name="judul" class="form-control" placeholder="Masukkan judul berita..." required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Ringkasan Berita</label>
                <textarea name="ringkasan" class="form-control" rows="2" placeholder="Tulis ringkasan singkat berita..." required></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Isi Lengkap Berita</label>
                <textarea name="isi" class="form-control" rows="6" placeholder="Tulis isi berita secara lengkap di sini..." required></textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Status Berita</label>
                    <select name="status" class="form-select">
                        <option value="publish">Publish</option>
                        <option value="draft">Draft</option>
                    </select>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Thumbnail Utama (Image)</label>
                    <input type="file" name="thumbnail" class="form-control" accept="image/*" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Media Pendukung Tambahan (Bisa banyak file Image/Video)</label>
                <input type="file" name="berita_media[]" class="form-control" accept="image/*,video/*" multiple>
                <small class="text-muted">Tekan Ctrl sambil klik file untuk memilih lebih dari 1 gambar/video.</small>
            </div>

            <div class="d-flex gap-2 border-top pt-3">
                <button type="submit" name="simpan" class="btn btn-primary px-4">
                    <i class="bi bi-cloud-arrow-up me-2"></i>Simpan & Publikasikan
                </button>
                <a href="berita.php" class="btn btn-light px-4">Batal</a>
            </div>

        </form>
    </div>
</div>

</body>
</html>