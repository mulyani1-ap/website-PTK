<?php
session_start();

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}

include "../config/database.php";

if(isset($_POST['simpan'])){

    $id = $_POST['id'];
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $ringkasan = mysqli_real_escape_string($conn, $_POST['ringkasan']);

    // MODE EDIT
    if(!empty($id)){
        $query = "UPDATE ptk 
                  SET judul='$judul', ringkasan='$ringkasan' 
                  WHERE id='$id'";
    }
    // MODE TAMBAH
    else{
        $query = "INSERT INTO ptk (judul, ringkasan) 
                  VALUES ('$judul', '$ringkasan')";
    }

    if(mysqli_query($conn, $query)){
        header("Location: dashboard.php");
    }else{
        echo "Gagal simpan data: " . mysqli_error($conn);
    }
}
?>