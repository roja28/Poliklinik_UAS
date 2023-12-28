<?php
session_start();

if (isset($_GET['page'])) {
    if ($_GET['page'] === 'dokter' || $_GET['page'] === 'pasien' || $_GET['page'] === 'periksa') {
        if (!isset($_SESSION['username'])) {
            header('Location: index.php?page=login');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Poliklinik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Sistem Informasi Poliklinik</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=dokter">Dokter</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=pasien">Pasien</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=periksa">Periksa</a>
                    </li>
                </ul>
            </div>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <?php
                    if (isset($_SESSION['username'])) {
                        echo '<a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>';
                    } else {
                        echo '<li class="nav-item">';
                        echo '<a class="nav-link" href="index.php?page=login"><i class="fas fa-sign-in-alt"></i> Login</a>';
                        echo '</li>';
                        echo '<li class="nav-item">';
                        echo '<a class="nav-link" href="index.php?page=register"><i class="fas fa-user-plus"></i> Register</a>';
                        echo '</li>';
                    }
                    ?>
                </li>
            </ul>
        </div>
    </nav>
    <!-- /Navbar -->
    <!-- Main Content -->
    <main role="main" class="container">
        <?php if (!isset($_GET['page']) || $_GET['page'] === 'index') { ?>
            <div class="text-center">
                <h1>Selamat Datang di Sistem Informasi Poliklinik</h1>
            </div>
        <?php } ?>
        <div style="display: none; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-image: url('img/spidey.png'); background-size: cover; background-position: center;">
            <p style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 25px; text-align: center; color: black; text-shadow: -1px -1px 0 #fff, 1px -1px 0 #fff, -1px 1px 0 #fff, 1px 1px 0 #fff;">
                With Great Power Comes Great Responsibility - Uncle Ben<br>
                Spider Gwen punya Roja
            </p>
        </div>
        <?php
    if (isset($_GET['page'])) {
    ?>
        <?php($_GET['page']) ?>
    <?php
        include($_GET['page'] . ".php");
    }
        ?>
    </main>
    
    <!-- /Main Content -->    
    <!-- Optional Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/9ee92bcd9e.js" crossorigin="anonymous"></script>
</body>
</html>
