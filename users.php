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

// Kode koneksi ke database
require 'koneksi.php';

// Query untuk mengambil data pengguna dari tabel Users
$query = "SELECT * FROM Users";

// Eksekusi query
$result = mysqli_query($koneksi, $query);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Tambahkan link ke Bootstrap CSS di sini -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>Users</title>
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
                <a class="navbar-brand" href="dashboard.php">Admin Market</a>
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
            <h2>Users</h2>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 30%;">Username</th>
                            <th style="width: 30%;">Email</th>
                            <th style="width: 20%;">Registration Date</th>
                            <th style="width: 20%;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Tampilkan data pengguna dari hasil query
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $row['username'] . "</td>";
                            echo "<td>" . $row['email'] . "</td>";
                            echo "<td>" . $row['registration_date'] . "</td>";
                            echo "<td>";
                            echo "<a href='edit_user.php?id=" . $row['id'] . "' class='btn btn-primary' onclick='return confirm(\"Apakah Anda yakin ingin mengedit pengguna?\")'>Edit</a>";
                            echo "<a href='delete_user.php?id=" . $row['id'] . "' class='btn btn-danger' onclick='return confirm(\"Apakah Anda yakin ingin menghapus pengguna?\")'>Delete</a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                        ?>
                        <!-- Tambahkan baris lain untuk setiap pengguna -->
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Tambahkan link ke Bootstrap JavaScript di sini -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js">
    </script>
</body>

</html>
