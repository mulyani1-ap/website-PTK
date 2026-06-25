<?php
session_start();

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}

include "../config/database.php";

$id = "";
$judul = "";
$ringkasan = "";

// MODE EDIT
if(isset($_GET['id'])){
    $id = $_GET['id'];

    $query = "SELECT * FROM ptk WHERE id='$id'";
    $result = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($result);

    if($data){
        $judul = $data['judul'];
        $ringkasan = $data['ringkasan'];
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $id ? "Edit PTK" : "Tambah PTK"; ?></title>

    <style>
        body{
            font-family: Arial;
            background: #f5f5f5;
        }

        .container{
            width: 50%;
            margin: 40px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
        }

        input, textarea{
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
        }

        button{
            padding: 10px 15px;
            background: #007bff;
            border: none;
            color: white;
            cursor: pointer;
        }

        a{
            text-decoration: none;
            color: #555;
        }
    </style>
</head>
<body>

<div class="container">
    <h2><?= $id ? "Edit PTK" : "Tambah PTK"; ?></h2>

    <form action="simpan_ptk.php" method="POST">

        <input type="hidden" name="id" value="<?= $id; ?>">

        <label>Judul</label>
        <input type="text" name="judul" value="<?= htmlspecialchars($judul); ?>" required>

        <label>Ringkasan</label>
        <textarea name="ringkasan" rows="6" required><?= htmlspecialchars($ringkasan); ?></textarea>

        <button type="submit" name="simpan">
            <?= $id ? "Update" : "Simpan"; ?>
        </button>

    </form>

    <br>
    <a href="dashboard.php">← Kembali</a>
</div>

</body>
</html>