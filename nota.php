<?php
include_once("koneksi.php");

if (!isset($_GET['id'])) {
    echo "ID periksa tidak ditemukan.";
    exit;
}

$id_periksa = $_GET['id'];

$query_nota = "SELECT periksa.id, 
                     periksa.id_pasien, 
                     periksa.id_dokter, 
                     periksa.tgl_periksa, 
                     periksa.catatan, 
                     periksa.obat, 
                     pasien.nama AS nama_pasien, 
                     dokter.nama AS nama_dokter,
                     GROUP_CONCAT(obat.nama_obat SEPARATOR '<br>') AS nama_obat,
                     SUM(obat.harga) + 150000 AS total_harga
              FROM periksa
              LEFT JOIN pasien ON periksa.id_pasien = pasien.id_pasien
              LEFT JOIN dokter ON periksa.id_dokter = dokter.id_dokter
              LEFT JOIN obat ON FIND_IN_SET(obat.id_obat, periksa.obat)
              WHERE periksa.id = $id_periksa
              GROUP BY periksa.id";

$result_nota = mysqli_query($mysqli, $query_nota);

if (!$result_nota || mysqli_num_rows($result_nota) === 0) {
    echo "Data nota tidak ditemukan.";
    exit;
}

$nota_data = mysqli_fetch_assoc($result_nota);
?>
<div class="container">
    <div class="header">
        <h1>Nota Periksa</h1>
    </div>
    <table class="nota-table">
        <tr>
            <th>ID</th>
            <th>Nama Pasien</th>
            <th>Nama Dokter</th>
            <th>Tanggal Periksa</th>
            <th>Catatan</th>
            <th>Obat</th>
            <th>Total Harga</th>
        </tr>
        <tr>
            <td><?php echo $nota_data['id']; ?></td>
            <td><?php echo $nota_data['nama_pasien']; ?></td>
            <td><?php echo $nota_data['nama_dokter']; ?></td>
            <td><?php echo $nota_data['tgl_periksa']; ?></td>
            <td><?php echo $nota_data['catatan']; ?></td>
            <td>
                <?php
                echo $nota_data['nama_obat'];
                ?>
            </td>
            <td>
                <?php
                echo 'Rp ' . number_format($nota_data['total_harga']);
                ?>
            </td>
        </tr>
    </table>
</div>
