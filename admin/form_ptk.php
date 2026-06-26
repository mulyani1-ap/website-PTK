<?php
session_start();

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}

include "../config/database.php";

$id = "";
$nama = "";
$nip = "";
$jabatan = "";
$sekolah_asal = "";

// 1. MODE EDIT: Ambil data lama dari database jika ada parameter ID
if(isset($_GET['id'])){
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    $query = "SELECT * FROM ptk WHERE id='$id'";
    $result = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($result);

    if($data){
        $nama = $data['nama'];
        $nip = $data['nip'];
        $jabatan = $data['jabatan'];
        $sekolah_asal = $data['sekolah_asal'];
    }
}

// 2. PROSES SIMPAN / UPDATE DATA (Saat tombol ditekan)
if(isset($_POST['simpan'])){
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $nip = mysqli_real_escape_string($conn, $_POST['nip']);
    $jabatan = mysqli_real_escape_string($conn, $_POST['jabatan']);
    $sekolah_asal = mysqli_real_escape_string($conn, $_POST['sekolah_asal']);

    if($id != ""){
        // Jika ID ada, lakukan UPDATE
        $sql = "UPDATE ptk SET nama='$nama', nip='$nip', jabatan='$jabatan', sekolah_asal='$sekolah_asal' WHERE id='$id'";
    } else {
        // Jika ID kosong, lakukan INSERT data baru
        $sql = "INSERT INTO ptk (nama, nip, jabatan, sekolah_asal) VALUES ('$nama', '$nip', '$jabatan', '$sekolah_asal')";
    }

    if(mysqli_query($conn, $sql)){
        header("Location: ptk.php"); // Redirect kembali ke tabel manajemen ptk setelah sukses
        exit;
    } else {
        echo "<script>alert('Gagal menyimpan data!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $id ? "Edit Data PTK" : "Tambah Data PTK"; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f5f7fb; font-family: Arial, sans-serif; }
        .container { max-width: 600px; margin-top: 50px; }
        .card-box { background: white; border-radius: 15px; padding: 30px; box-shadow: 0 10px 25px rgba(0,0,0,.05); }
    </style>
</head>
<body>

<div class="container">
    <div class="card-box">
        <h3 class="fw-bold text-dark mb-4"><?= $id ? "Edit Personnel" : "Tambah Personnel Baru"; ?></h3>
        
        <form action="" method="POST">

            <input type="hidden" name="id" value="<?= $id; ?>">

            <div class="mb-3">
                <label class="form-label fw-medium">Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($nama); ?>" required placeholder="Masukkan nama beserta gelar">
            </div>

            <div class="mb-3">
                <label class="form-label fw-medium">NIP (Opsional)</label>
                <input type="text" name="nip" class="form-control" value="<?= htmlspecialchars($nip); ?>" placeholder="Masukkan NIP jika ada">
            </div>

            <div class="mb-3">
                <label class="form-label fw-medium">Jabatan / Kategori</label>
                <select name="jabatan" class="form-select" required>
                    <option value="">-- Pilih Jabatan --</option>
                    <option value="Guru" <?= $jabatan == 'Guru' ? 'selected' : ''; ?>>Guru</option>
                    <option value="Kepala Sekolah" <?= $jabatan == 'Kepala Sekolah' ? 'selected' : ''; ?>>Kepala Sekolah</option>
                    <option value="Pengawas" <?= $jabatan == 'Pengawas' ? 'selected' : ''; ?>>Pengawas</option>
                    <option value="Tenaga Administrasi" <?= $jabatan == 'Tenaga Administrasi' ? 'selected' : ''; ?>>Tenaga Administrasi</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="form-label fw-medium">Sekolah Asal / Instansi</label>
                <input type="text" name="sekolah_asal" class="form-control" value="<?= htmlspecialchars($sekolah_asal); ?>" placeholder="Contoh: SDN 001 Bontang Utara">
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <a href="ptk.php" class="text-secondary text-decoration-none">← Kembali</a>
                <button type="submit" name="simpan" class="btn btn-primary px-4 rounded-pill">
                    <?= $id ? "Update Data" : "Simpan Data"; ?>
                </button>
            </div>

        </form>
    </div>
</div>

</body>
</html>