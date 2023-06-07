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

// Cek apakah parameter id produk diberikan dalam URL
if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    // Query untuk mendapatkan data produk berdasarkan id
    $query = "SELECT * FROM Products WHERE id = $productId";

    // Eksekusi query
    $result = mysqli_query($koneksi, $query);

    // Periksa apakah produk ditemukan
    if (mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);
    } else {
        // Jika produk tidak ditemukan, redirect kembali ke halaman products.php
        header("Location: products.php");
        exit;
    }
} else {
    // Jika parameter id tidak diberikan, redirect kembali ke halaman products.php
    header("Location: products.php");
    exit;
}

// Proses penghapusan produk setelah konfirmasi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Periksa apakah ada pesanan terkait dengan produk
    $checkOrderQuery = "SELECT COUNT(*) AS total FROM orderdetails WHERE product_id = $productId";
    $checkOrderResult = mysqli_query($koneksi, $checkOrderQuery);
    $totalOrders = mysqli_fetch_assoc($checkOrderResult)['total'];

    if ($totalOrders > 0) {
        // Ada pesanan terkait, tampilkan pesan kesalahan
        echo "Tidak dapat menghapus produk karena terdapat pesanan yang terkait.";
    } else {
        // Tidak ada pesanan terkait, lakukan penghapusan produk
        $deleteQuery = "DELETE FROM Products WHERE id = $productId";
        mysqli_query($koneksi, $deleteQuery);

        // Redirect ke halaman produk setelah menghapus
        header("Location: products.php");
        exit;
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
    <title>Delete Product</title>
</head>

<body>
    <header>
        <!-- Kode header sama seperti di dalam products.php -->
    </header>
    <main>
        <div class="container mt-4">
            <h2>Delete Product</h2>
            <p>Are you sure you want to delete the following product?</p>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">
                        <?php echo $product['name']; ?>
                    </h5>
                    <p class="card-text">
                        <?php echo $product['description']; ?>
                    </p>
                    <p class="card-text">Price:
                        <?php echo $product['price']; ?>
                    </p>
                    <p class="card-text">Stock:
                        <?php echo $product['stock']; ?>
                    </p>
                    <form method="post" action="">
                        <button type="submit" class="btn btn-danger">Delete</button>
                        <a href="products.php" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <!-- Tambahkan link ke Bootstrap JavaScript di sini -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js">
    </script>
</body>

</html>