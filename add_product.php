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

// Cek apakah form telah disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data yang dikirimkan melalui form
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    // Tangani pengunggahan gambar
    $image = $_FILES['image']['name'];
    $imageTemp = $_FILES['image']['tmp_name'];
    $imagePath = 'uploads/' . $image;

    // Pindahkan file gambar ke folder uploads
    move_uploaded_file($imageTemp, $imagePath);

    // Query untuk menyimpan data produk baru ke database
    $insertQuery = "INSERT INTO Products (name, description, price, stock, image) VALUES ('$name', '$description', $price, $stock, '$imagePath')";
    $result = mysqli_query($koneksi, $insertQuery);

    if ($result) {
        // Redirect ke halaman products setelah berhasil menambahkan produk
        header("Location: products.php");
        exit;
    } else {
        // Tampilkan pesan error jika terjadi kesalahan dalam penyimpanan data
        $errorMessage = 'Failed to add product. Please try again.';
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
    <title>Add Product</title>
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
            <h2>Add Product</h2>
            <?php
            if (isset($errorMessage)) {
                echo '<div class="alert alert-danger">' . $errorMessage . '</div>';
            }
            ?>
            <form method="post" action="" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Price</label>
                    <input type="text" pattern="[0-9]*" class="form-control" id="price" name="price" required
                        title="Please enter a numeric value">
                </div>
                <div class="mb-3">
                    <label for="stock" class="form-label">Stock</label>
                    <input type="text" pattern="[0-9]*" class="form-control" id="stock" name="stock" required
                        title="Please enter a numeric value">
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Image</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                </div>
                <button type="submit" class="btn btn-primary">Add Product</button>
            </form>
        </div>
    </main>

    <!-- Tambahkan link ke Bootstrap JavaScript di sini -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js">
    </script>
</body>

</html>