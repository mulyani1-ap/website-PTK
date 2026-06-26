<?php
// get-detail.php
include "config/database.php";

$kategori = $_GET['kategori'] ?? '';

// Amankan input dari SQL Injection
$kategori = mysqli_real_escape_string($conn, $kategori);

// Sesuaikan nama kolom ('nama', 'nip', 'sekolah', 'jabatan') dengan struktur tabel 'ptk' Anda
$query = mysqli_query($conn, "SELECT * FROM ptk WHERE jabatan = '$kategori' ORDER BY nama ASC");

$no = 1;
if (mysqli_num_rows($query) > 0) {
    while ($row = mysqli_fetch_assoc($query)) {
        echo "<tr>";
        echo "<td>" . $no++ . "</td>";
        echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
        echo "<td>" . htmlspecialchars($row['nip'] ?? '-') . "</td>";
        echo "<td>" . htmlspecialchars($row['sekolah'] ?? '-') . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='4' class='text-center text-muted py-3'>Tidak ada data detail untuk kategori ini.</td></tr>";
}
?>