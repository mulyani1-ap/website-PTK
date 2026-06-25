<?php

$host = "localhost";
$user = "root";
$pass = "";
$db   = "ptk_bontang";

$conn = mysqli_connect(
    $host,
    $user,
    $pass,
    $db
);

if (!$conn) {
    die("Koneksi gagal");
}