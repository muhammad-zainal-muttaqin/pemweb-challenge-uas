<?php
session_start();
require_once 'koneksi.php';

// Fungsi untuk mendapatkan ID terakhir yang digunakan sebagai nilai ID berikutnya
function getLastUserID()
{
    global $koneksi;
    $query = "SELECT MAX(id) AS last_id FROM Users";
    $result = mysqli_query($koneksi, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['last_id'] + 1;
}

// Fungsi untuk memvalidasi pendaftaran
function validate_registration($username, $password, $email)
{
    global $koneksi;

    // Cek apakah username atau email sudah terdaftar sebelumnya
    $query = "SELECT * FROM Users WHERE username = ? OR email = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "ss", $username, $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        return "Username atau email sudah terdaftar";
    }

    // Buat nambahkan pengguna baru ke database
    $id = getLastUserID();
    $registration_date = date("Y-m-d");
    $query = "INSERT INTO Users (id, username, password, email, registration_date) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($koneksi, $query);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    mysqli_stmt_bind_param($stmt, "issss", $id, $username, $hashed_password, $email, $registration_date);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['username'] = $username; // Simpan username ke dalam session
        return "success";
    } else {
        return "Gagal melakukan pendaftaran";
    }
}

// Fungsi untuk logout
function logout()
{
    session_unset(); // Menghapus semua data session
    session_destroy(); // Menghapus session
    header("Location: index.php"); // Mengarahkan pengguna kembali ke halaman login
    exit();
}

// Proses form pendaftaran
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['logout'])) {
        logout(); // Memanggil fungsi logout jika tombol logout ditekan
    }

    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    $registration_result = validate_registration($username, $password, $email);

    if ($registration_result === "success") {
        header("Location: dashboard.php");
        exit();
    } else {
        $error_message = $registration_result;
    }
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
    <title>Register</title>
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
                <h1 class="card-title text-center">Register</h1>
                <form method="POST" action="" onsubmit="return validateForm()">
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" id="username" name="username" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                    <br>
                    <div class="form-group">
                        <input type="submit" value="Register" class="btn btn-primary w-100">
                    </div>
                    <?php
                    if (isset($error_message)) {
                        echo '<p class="text-center text-danger">' . $error_message . '</p>';
                    }
                    ?>
                    <p class="text-center">Sudah punya akun? <a href="index.php">Masuk disini</a></p>
                </form>
            </div>
        </div>
    </div>

    <!-- Tambahkan link ke Bootstrap JavaScript di sini -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous">
        </script>
    <script>
        function validateForm() {
            var email = document.getElementById('email').value;
            var username = document.getElementById('username').value;
            var password = document.getElementById('password').value;

            // Validasi email
            var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                alert('Masukkan email yang valid');
                return false;
            }

            // Validasi username minimal 8 karakter
            if (username.length < 8) {
                alert('Username harus minimal 8 karakter');
                return false;
            }

            // Validasi password harus memuat huruf besar, huruf kecil, angka, dan simbol
            var passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/;
            if (!passwordPattern.test(password)) {
                alert('Password harus memuat huruf besar, huruf kecil, angka, dan simbol');
                return false;
            }

            // Jika semua validasi berhasil, kembalikan nilai true
            return true;
        }
    </script>
</body>

</html>