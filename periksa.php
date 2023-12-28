<?php

include_once("koneksi.php");

$id_pasien = '';
$id_dokter = '';
$tgl_periksa = '';
$catatan = '';
$obat = '';
$id = '';

$daftar_dokter = mysqli_query($mysqli, "SELECT id_dokter, nama FROM dokter");
$daftar_pasien = mysqli_query($mysqli, "SELECT id_pasien, nama FROM pasien");

$biaya_jasa_dokter = 150000;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['simpan'])) {
    $id_pasien = $_POST['id_pasien'];
    $id_dokter = $_POST['id_dokter'];
    $tgl_periksa = $_POST['tgl_periksa'];
    $catatan = $_POST['catatan'];
    $obat_string = implode(",", $_POST['obat']);
    

    if (!empty($_POST['id'])) {
        $id = $_POST['id'];
        $query = "UPDATE periksa SET id_pasien = $id_pasien, id_dokter = $id_dokter, tgl_periksa = '$tgl_periksa', catatan = '$catatan', obat = '$obat_string' WHERE id = $id";
    } else {
        $query = "INSERT INTO periksa (id_pasien, id_dokter, tgl_periksa, catatan, obat) VALUES ($id_pasien, $id_dokter, '$tgl_periksa', '$catatan', '$obat_string')";
    }

    if (mysqli_query($mysqli, $query)) {
        $total_harga_obat = 0;
        if (!empty($_POST['obat'])) {
            foreach ($_POST['obat'] as $selected_obat) {
                $harga_obat_result = mysqli_query($mysqli, "SELECT harga FROM obat WHERE id_obat = $selected_obat");
                $harga_obat_data = mysqli_fetch_assoc($harga_obat_result);
                $total_harga_obat += $harga_obat_data['harga'];
            }
        }

        $total_biaya_periksa = $biaya_jasa_dokter + $total_harga_obat;

        echo "<script>alert('Data berhasil disimpan. Total biaya periksa: " . number_format($total_biaya_periksa, 0, ',', '.') . "');</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan: " . mysqli_error($mysqli) . "');</script>";
    }
}

if (isset($_GET['id']) && $_GET['aksi'] == 'hapus') {
    $id = $_GET['id'];
    $query = "DELETE FROM periksa WHERE id = $id";
    if (mysqli_query($mysqli, $query)) {
        echo "<script>
            if (confirm('Data berhasil dihapus. Kembali ke halaman Periksa?')) {
                window.location.href = 'index.php?page=periksa';
            } else {
                window.location.href = 'index.php';
            }
        </script>";
    } else {
        echo "<script>alert('Terjadi kesalahan: " . mysqli_error($mysqli) . "');</script>";
    }
}

if (isset($_GET['id']) && $_GET['aksi'] == 'edit') {
    $id = $_GET['id'];
    $query = "SELECT * FROM periksa WHERE id = $id";
    $result = mysqli_query($mysqli, $query);

    if (mysqli_num_rows($result) == 1) {
        $data = mysqli_fetch_assoc($result);
        $id_pasien = $data['id_pasien'];
        $id_dokter = $data['id_dokter'];
        $tgl_periksa = $data['tgl_periksa'];
        $catatan = $data['catatan'];
        $obat = $data['obat'];

        if (!empty($id)) {
            $query_delete_old = "DELETE FROM periksa WHERE id = $id";
            mysqli_query($mysqli, $query_delete_old);
        }
    }
}

$query = "SELECT periksa.id, 
                 periksa.id_pasien, 
                 periksa.id_dokter, 
                 periksa.tgl_periksa, 
                 periksa.catatan, 
                 periksa.obat, 
                 pasien.nama AS nama_pasien, 
                 dokter.nama AS nama_dokter
          FROM periksa
          LEFT JOIN pasien ON periksa.id_pasien = pasien.id_pasien
          LEFT JOIN dokter ON periksa.id_dokter = dokter.id_dokter";

$result = mysqli_query($mysqli, $query);

?>

<div class="container">
    <div class="header">
        <h1>Data Periksa</h1>
    </div>
    <div class="row">
        <!-- Form -->
        <div class="col-md-3">
            <div class="form-container">
                <form class="form" method="POST" action="">
                    <div class="form-group">
                        <label for="id_pasien">ID Pasien</label>
                        <select name="id_pasien" id="id_pasien" required>
                            <option value="">Pilih ID Pasien</option>
                            <?php
                            while ($row = mysqli_fetch_assoc($daftar_pasien)) {
                                $selected = ($id_pasien == $row['id_pasien']) ? "selected" : "";
                                echo "<option value='" . $row['id_pasien'] . "' " . $selected . ">" . $row['id_pasien'] . ' - ' . $row['nama'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="id_dokter">ID Dokter</label>
                        <select name="id_dokter" id="id_dokter" required>
                            <option value="">Pilih ID Dokter</option>
                            <?php
                            while ($row = mysqli_fetch_assoc($daftar_dokter)) {
                                $selected = ($id_dokter == $row['id_dokter']) ? "selected" : "";
                                echo "<option value='" . $row['id_dokter'] . "' " . $selected . ">" . $row['id_dokter'] . ' - ' . $row['nama'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tgl_periksa">Tanggal Periksa</label>
                        <input required="" name="tgl_periksa" id="tgl_periksa" type="datetime-local" value="<?php echo $tgl_periksa ?>">
                    </div>
                    <div class="form-group">
                        <label for="catatan">Catatan</label>
                        <textarea required="" name="catatan" id="catatan"><?php echo $catatan ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="obat">Obat</label><br>
                        <div class="checkbox-options">
                            <?php
                            $obat_result = mysqli_query($mysqli, "SELECT * FROM obat");
                            while ($row = mysqli_fetch_array($obat_result)) {
                                echo '<label class="checkbox-label">';
                                echo '<input type="checkbox" name="obat[]" value="' . $row['id_obat'] . '">' .'<br>'. $row['id_obat'] . ' - ' . $row['nama_obat'] . ' - ' . $row['kemasan'] . ' - Rp ' . number_format($row['harga'], 0, ',', '.') . '<br>';
                                echo '</label>';
                            }
                            ?>
                        </div>
                    </div>
                    <button type="submit" class="form-submit-btn" name="simpan">Simpan</button>
                </form>
            </div>
        </div>
        <!-- /Form -->
        <!-- Table -->
        <div class="col-md-8 table-container">
            <?php
            if (mysqli_num_rows($result) == 0) {
            ?>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">id</th>
                            <th scope="col">Nama Pasien</th>
                            <th scope="col">Nama Dokter</th>
                            <th scope="col">Tanggal Periksa</th>
                            <th scope="col">Catatan</th>
                            <th scope="col">Obat</th>
                            <th scope="col">Total Harga</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="7">Tidak ada data Periksa</td>
                        </tr>
                    </tbody>
                </table>
            <?php
            } else {
            ?>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">id</th>
                            <th scope="col">Nama Pasien</th>
                            <th scope="col">Nama Dokter</th>
                            <th scope="col">Tanggal Periksa</th>
                            <th scope="col">Catatan</th>
                            <th scope="col">Obat</th>
                            <th scope="col">Total Harga</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($data = mysqli_fetch_array($result)) {
                        ?>
                            <tr>
                                <th scope="row"><?php echo $data['id'] ?></th>
                                <td><?php echo $data['nama_pasien'] ?></td>
                                <td><?php echo $data['nama_dokter'] ?></td>
                                <td><?php echo $data['tgl_periksa'] ?></td>
                                <td><?php echo $data['catatan'] ?></td>
                                <td>
                                <?php
                                if (!empty($data['obat'])) {
                                    $selected_obats = explode(",", $data['obat']);
                                    echo '<span class="obat-tooltip">';
                                    echo $data['obat'];
                                    echo '<span class="obat-tooltiptext">';
                                    foreach ($selected_obats as $selected_obat) {
                                        $nama_obat_result = mysqli_query($mysqli, "SELECT nama_obat FROM obat WHERE id_obat = $selected_obat");
                                        $nama_obat_data = mysqli_fetch_assoc($nama_obat_result);
                                        echo $nama_obat_data['nama_obat'] . "<br>";
                                    }
                                    echo '</span></span>';
                                } else {
                                    echo '-';
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                $total_harga_obat_row = 0;
                                if (!empty($data['obat'])) {
                                    $selected_obats = explode(",", $data['obat']);
                                    foreach ($selected_obats as $selected_obat) {
                                        $harga_obat_result = mysqli_query($mysqli, "SELECT harga FROM obat WHERE id_obat = $selected_obat");
                                        $harga_obat_data = mysqli_fetch_assoc($harga_obat_result);
                                        $total_harga_obat_row += $harga_obat_data['harga'];
                                    }
                                }
                                $total_biaya_periksa_row = $biaya_jasa_dokter + $total_harga_obat_row;
                                echo "Rp " . number_format($total_biaya_periksa_row, 0, ',', '.');
                                ?>
                            </td>
                                <td>
                                    <div class="action-buttons">
                                        <a class="btn btn-primary rounded-pill px-3" href="index.php?page=periksa&aksi=edit&id=<?php echo $data['id'] ?>">Edit</a>
                                        <a class="btn btn-danger rounded-pill px-3" href="index.php?page=periksa&aksi=hapus&id=<?php echo $data['id'] ?>">Hapus</a>
                                        <a class="btn btn-success rounded-pill px-3" href="index.php?page=nota&id=<?php echo $data['id'] ?>">Print</a>
                                    </div>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            <?php
            }
            ?>
        </div>
        <!-- /Table -->
    </div>
</div>
