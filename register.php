<?php
include_once("koneksi.php");

$username = '';
$password = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($mysqli, $query);

    if (mysqli_num_rows($result) > 0) {
        $registrationMessage = "Username sudah digunakan. Silakan coba username lain.";
        $username = '';
        $password = '';
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO users (username, password) VALUES ('$username', '$hashedPassword')";
        if (mysqli_query($mysqli, $query)) {
            $registrationMessage = "Registrasi Berhasil. Silahkan Login.";
            echo "<script>alert('$registrationMessage'); window.location.href='index.php?page=login';</script>";
            // header("Location: login.php");
        } else {
            $registrationMessage = "Registrasi gagal. Silakan coba lagi.";
            $username = '';
            $password = '';
        }
    }
}
?>
    <!-- Main Content -->
    <main role="main" class="container">
        <div class="login-text-center">
            <h1>Register</h1>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="login-form-container">
                    <form class="login-form" method="POST" action="index.php?page=register">
                        <div class="login-form-group">
                            <label for="username">Username</label>
                            <input required="" name="username" id="username" type="text" value="<?php echo $username ?>">
                        </div>
                        <div class="login-form-group">
                            <label for="password">Password</label>
                            <input required="" name="password" id="password" type="password" value="<?php echo $password ?>">
                        </div>
                        <p>Already have an account? <a href="index.php?page=login">Login</a></p>
                        <button type="submit" class="login-form-submit-btn" name="register">Register</button>
                    </form>
                    <?php
                    if (isset($registrationMessage)) {
                        if (strpos($registrationMessage, 'berhasil') !== false) {
                            echo '<div class="alert alert-success" role="alert">' . $registrationMessage . '</div>';
                        } else {
                            echo '<div class="alert alert-danger" role="alert">' . $registrationMessage . '</div>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </main>
    <!-- /Main Content -->
    <!-- Optional Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/9ee92bcd9e.js" crossorigin="anonymous"></script>
    
</body>
</html>
