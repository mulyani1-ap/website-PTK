<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}
include "../config/database.php";

if(isset($_GET['id'])){
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    
    $hapus = mysqli_query($conn, "DELETE FROM pengumuman WHERE id = '$id'");

    if($hapus){
        echo "<script>alert('Pengumuman berhasil dihapus!'); window.location='pengumuman.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data.'); window.location='pengumuman.php';</script>";
    }
} else {
    header("Location: pengumuman.php");
}
?>