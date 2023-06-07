<?php
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    // Jika tidak, redirect ke halaman login
    header("Location: index.php");
    exit;
}

// Kode koneksi ke database
require 'koneksi.php';

// Cek apakah parameter id pengguna diberikan dalam URL
if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    // Query untuk mengambil data pengguna berdasarkan id
    $query = "SELECT * FROM Users WHERE id = $userId";

    // Eksekusi query
    $result = mysqli_query($koneksi, $query);

    // Periksa apakah pengguna ditemukan
    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
    } else {
        // Jika pengguna tidak ditemukan, redirect kembali ke halaman users.php
        header("Location: users.php");
        exit;
    }
} else {
    // Jika parameter id tidak diberikan, redirect kembali ke halaman users.php
    header("Location: users.php");
    exit;
}

// Proses pembaruan pengguna setelah pengguna mengirimkan formulir
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data yang dikirimkan oleh pengguna
    $newUsername = $_POST['username'];
    $newEmail = $_POST['email'];

    // Query untuk memperbarui data pengguna dalam tabel Users
    $updateQuery = "UPDATE Users SET username = '$newUsername', email = '$newEmail' WHERE id = $userId";

    // Eksekusi query pembaruan
    $updateResult = mysqli_query($koneksi, $updateQuery);

    // Periksa apakah pembaruan berhasil
    if ($updateResult) {
        // Jika berhasil, redirect kembali ke halaman users.php
        header("Location: users.php");
        exit;
    } else {
        // Jika gagal, tampilkan pesan error
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Tambahkan link ke Bootstrap CSS di sini -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>Edit User</title>
    <style>
        @media (min-width: 992px) {
            .navbar-nav .ms-auto {
                margin-left: auto;
            }
        }
    </style>
</head>

<body>
    <header>
        <!-- Kode header sama seperti di dalam users.php -->
    </header>
    <main>
        <div class="container mt-4">
            <h2>Edit User</h2>
            <form method="post" action="">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username"
                        value="<?php echo $user['username']; ?>">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email"
                        value="<?php echo $user['email']; ?>">
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </main>
    <!-- Tambahkan link ke Bootstrap JavaScript di sini -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js">
    </script>
</body>

</html>