<?php
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    // Jika tidak, redirect ke halaman login
    header("Location: index.php");
    exit;
}

// Fungsi untuk logout
function logout()
{
    // Hapus cookie
    if (isset($_COOKIE['username'])) {
        setcookie('username', '', time() - 3600); // Set waktu kadaluarsa cookie ke masa lalu
    }
    // Menghapus semua data sesi
    session_unset();

    // Menghancurkan sesi
    session_destroy();

    // Redirect ke halaman login setelah logout
    header("Location: index.php");
    exit;
}

// Cek apakah tombol logout ditekan
if (isset($_POST['logout'])) {
    logout();
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
    <title>Admin Market</title>
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
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container">
                <a class="navbar-brand" href="#">Admin Market</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="users.php">Users</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="products.php">Products</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="orders.php">Orders</a>
                        </li>
                    </ul>
                    <div class="navbar-nav ms-auto">
                        <form method="post" action="">
                            <button type="submit" name="logout" class="btn btn-danger">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <main>
        <div class="container mt-4">
            <h2>Admin Market</h2>
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Users</h5>
                            <p class="card-text">Manage users and their information.</p>
                            <a href="users.php" class="btn btn-primary">View Users</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Products</h5>
                            <p class="card-text">Manage products and their details.</p>
                            <a href="products.php" class="btn btn-primary">View Products</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Orders</h5>
                            <p class="card-text">Manage orders and track their status.</p>
                            <a href="orders.php" class="btn btn-primary">View Orders</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Tambahkan link ke Bootstrap JavaScript di sini -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js">
    </script>
</body>

</html>