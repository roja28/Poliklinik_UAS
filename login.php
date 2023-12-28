<?php
include_once("koneksi.php");

$username = '';
$password = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($mysqli, $query);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);

        if (password_verify($password, $row['password'])) {
            session_start();
            $_SESSION['username'] = $username;
            header("Location: index.php");
            exit;
        } 
    } else {
        echo "<script>alert('Username atau password salah. Silakan coba lagi.');</script>";
    }
}
?>
    <!-- Main Content -->
    <main role="main" class="container">
        <div class="login-text-center">
            <h1>Login</h1>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="login-form-container">
                    <form class="login-form" method="POST" action="index.php?page=login">
                        <div class="login-form-group">
                            <label for="username">Username</label>
                            <input required="" name="username" id="username" type="text" value="<?php echo $username ?>">
                        </div>
                        <div class="login-form-group">
                            <label for="password">Password</label>
                            <input required="" name="password" id="password" type="password" value="<?php echo $password ?>">
                        </div>
                        <p>Dont have an account? <a href="index.php?page=register">Register</a></p>
                        <button type="submit" class="login-form-submit-btn" name="login">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </main>
