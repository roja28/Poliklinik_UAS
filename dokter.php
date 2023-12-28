<?php
include_once("koneksi.php");

$nama = '';
$alamat = '';
$no_hp = '';
$id = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['simpan'])) {
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $no_hp = $_POST['no_hp'];

    if (!empty($_POST['id'])) {
        $id = $_POST['id'];
        $query = "UPDATE dokter SET nama = '$nama', alamat = '$alamat', no_hp = '$no_hp' WHERE id_dokter = $id";
    } else {
        $query = "INSERT INTO dokter (nama, alamat, no_hp) VALUES ('$nama', '$alamat', '$no_hp')";
    }

    if (mysqli_query($mysqli, $query)) {
        echo "<script>alert('Data berhasil disimpan');</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan: " . mysqli_error($mysqli) . "');</script>";
    }
}

if (isset($_GET['id']) && $_GET['aksi'] == 'hapus') {
    $id = $_GET['id'];
    $query = "DELETE FROM dokter WHERE id_dokter = $id";
    if (mysqli_query($mysqli, $query)) {
        echo "<script>
            if (confirm('Data berhasil dihapus. Kembali ke halaman Dokter?')) {
                window.location.href = 'index.php?page=dokter';
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
    $query = "SELECT * FROM dokter WHERE id_dokter = $id";
    $result = mysqli_query($mysqli, $query);

    if (mysqli_num_rows($result) == 1) {
        $data = mysqli_fetch_assoc($result);
        $nama = $data['nama'];
        $alamat = $data['alamat'];
        $no_hp = $data['no_hp'];
    }
}
?>

<div class="container">
    <div class="header">
        <h1>Data Dokter</h1>
    </div>
    <div class="row">
        <!-- Form -->
        <div class="col-md-3">
            <div class="form-container">
                <form class="form" method="POST" action="">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <div class="form-group">
                        <label for="nama">Nama Dokter</label>
                        <input required="" name="nama" id="nama" type="text" value="<?php echo $nama ?>">
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <input required="" name="alamat" id="alamat" type="text" value="<?php echo $alamat ?>">
                    </div>
                    <div class="form-group">
                        <label for="no_hp">No HP</label>
                        <input required="" name="no_hp" id="no_hp" type="text" pattern=".{11,}" title="Nomor HP harus memiliki lebih dari 10 digit" value="<?php echo $no_hp ?>">
                    </div>
                    <button type="submit" class="form-submit-btn" name="simpan">Simpan</button>
                </form>
            </div>
        </div>
        <!-- /Form -->
        <!-- Table -->
        <div class="col-md-8 table-container">
            <?php
            $result = mysqli_query($mysqli, "SELECT * FROM dokter");
            if (mysqli_num_rows($result) == 0) {
            ?>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">id</th>
                            <th scope="col">Nama Dokter</th>
                            <th scope="col">Alamat</th>
                            <th scope="col">No HP</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="5">Tidak ada Dokter</td>
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
                            <th scope="col">Nama Dokter</th>
                            <th scope="col">Alamat</th>
                            <th scope="col">No HP</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        while ($data = mysqli_fetch_array($result)) {
                        ?>
                            <tr>
                                <th scope="row"><?php echo $no++ ?></th>
                                <td><?php echo $data['nama'] ?></td>
                                <td><?php echo $data['alamat'] ?></td>
                                <td><?php echo $data['no_hp'] ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <a class="btn btn-primary rounded-pill px-3" href="index.php?page=dokter&aksi=edit&id=<?php echo $data['id_dokter'] ?>">Edit</a>
                                        <a class="btn btn-danger rounded-pill px-3" href="index.php?page=dokter&aksi=hapus&id=<?php echo $data['id_dokter'] ?>">Hapus</a>
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