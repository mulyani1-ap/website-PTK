<?php
include "config/database.php";

$kategori = isset($_GET['kategori']) ? mysqli_real_escape_string($conn, $_GET['kategori']) : '';
$jenjang  = isset($_GET['jenjang']) ? mysqli_real_escape_string($conn, $_GET['jenjang']) : '';
$status   = isset($_GET['status']) ? mysqli_real_escape_string($conn, $_GET['status']) : '';

if (empty($kategori)) {
    echo "<tr><td colspan='4' class='text-center text-danger py-3'>Kategori tidak valid.</td></tr>";
    exit;
}

$query_sql = "SELECT * FROM `ptk` WHERE `jabatan` = '$kategori'";

if (!empty($jenjang)) {
    $query_sql .= " AND `sekolah_asal` LIKE '%$jenjang%'";
}

// Aturan Cerdas Negeri vs Swasta (Sama persis dengan halaman utama agar sinkron)
$negeri_keywords = "(`sekolah_asal` LIKE '%Negeri%' 
                    OR `sekolah_asal` LIKE '%SDN%' 
                    OR `sekolah_asal` LIKE '%SMPN%' 
                    OR `sekolah_asal` LIKE '%TKN%' 
                    OR `sekolah_asal` LIKE '% N %' 
                    OR `sekolah_asal` LIKE '% N.%')";

if (!empty($status)) {
    if ($status == 'Negeri') {
        $query_sql .= " AND $negeri_keywords";
    } elseif ($status == 'Swasta') {
        $query_sql .= " AND NOT $negeri_keywords";
    }
}

$query_sql .= " ORDER BY `nama` ASC";

$result = mysqli_query($conn, $query_sql);

if ($result && mysqli_num_rows($result) > 0) {
    $no = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        $nama    = htmlspecialchars($row['nama'] ?? '-');
        $nip     = htmlspecialchars($row['nip'] ?? '-');
        $sekolah = htmlspecialchars($row['sekolah_asal'] ?? '-');
        
        echo "<tr>";
        echo "<td class='text-center'>{$no}</td>";
        echo "<td>{$nama}</td>";
        echo "<td class='text-secondary'>{$nip}</td>";
        echo "<td>{$sekolah}</td>";
        echo "</tr>";
        $no++;
    }
} else {
    echo "<tr><td colspan='4' class='text-center text-muted py-4'>Tidak ada data personnel yang cocok dengan filter pencarian ini.</td></tr>";
}
?>