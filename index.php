<?php
require_once 'koneksi.php';

// Fungsi untuk memvalidasi login
function validate_login($username, $password)
{
    global $koneksi;

    // Menghindari serangan SQL Injection
    $username = mysqli_real_escape_string($koneksi, $username);

    // Menggunakan prepared statement untuk mencegah serangan SQL Injection
    $query = mysqli_prepare($koneksi, "SELECT password FROM Users WHERE username = ?");
    mysqli_stmt_bind_param($query, "s", $username);
    mysqli_stmt_execute($query);
    $result = mysqli_stmt_get_result($query);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $hashed_password = $row['password'];

        // Verifikasi kata sandi
        if (password_verify($password, $hashed_password)) {
            // Login berhasil
            return true;
        }
    }

    // Login gagal
    return false;
}

// Cek apakah form login disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (validate_login($username, $password)) {
        // Mulai sesi
        session_start();

        // Tambahkan data ke sesi
        $_SESSION['username'] = $username;

        // Periksa apakah checkbox "Remember Me" dicentang
        if (isset($_POST['remember'])) {
            // Set cookie dengan masa berlaku 30 hari
            setcookie('username', $username, time() + (30 * 24 * 60 * 60));
        }

        // Redirect ke halaman selanjutnya setelah login berhasil
        header("Location: dashboard.php");
        exit;
    } else {
        // Menampilkan pesan error jika login gagal
        $error_message = "Username atau password salah.";
    }
}

// Periksa apakah cookie 'username' sudah ada
if (isset($_COOKIE['username'])) {
    // Mulai sesi
    session_start();

    // Tambahkan data ke sesi
    $_SESSION['username'] = $_COOKIE['username'];

    // Redirect ke halaman selanjutnya karena pengguna telah diingat
    header("Location: dashboard.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Tambahkan link ke Bootstrap CSS di sini -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>Login</title>
    <style>
        body,
        html {
            height: 100%;
        }

        body {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="card-body">
                <div class="rounded-4 d-flex justify-content-center align-items-center flex-column left-box">
                    <div class=" featured-image mb-3">
                        <img src="images/main.png" class="img-fluid" style="width: 100px; height: 100px;">
                    </div>
                </div>
                <h1 class="card-title text-center">Login</h1>
                <form method="POST">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" id="username" name="username" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Remember Me</label>
                    </div>

                    <br>
                    <div class="form-group">
                        <input type="submit" value="Login" class="btn btn-primary w-100">
                    </div>

                    <p class="text-center">Belum punya akun? <a href="register.php">Daftar disini</a></p>
                </form>
                <?php if (isset($error_message)): ?>
                    <p class="text-danger text-center">
                        <?php echo $error_message; ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Tambahkan link ke Bootstrap JavaScript di sini -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous">
        </script>
</body>

</html>