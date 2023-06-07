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

// Proses pembaruan produk setelah pengguna mengirimkan formulir
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data yang dikirimkan oleh pengguna
    $newName = $_POST['name'];
    $newDescription = $_POST['description'];
    $newPrice = $_POST['price'];
    $newStock = $_POST['stock'];

    // Cek apakah file gambar baru diunggah
    if ($_FILES['image']['name']) {
        // Menghapus file gambar lama
        if ($product['image']) {
            unlink($product['image']);
        }

        // Menyimpan file gambar baru
        $image = $_FILES['image']['name'];
        $target = "uploads/" . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $target);

        // Query untuk memperbarui data produk dalam tabel Products dengan foto baru
        $updateQuery = "UPDATE Products SET name = '$newName', description = '$newDescription', price = '$newPrice', stock = '$newStock', image = '$target' WHERE id = $productId";
    } else {
        // Jika file gambar tidak diunggah, melakukan pembaruan tanpa mengubah foto
        $updateQuery = "UPDATE Products SET name = '$newName', description = '$newDescription', price = '$newPrice', stock = '$newStock' WHERE id = $productId";
    }

    // Eksekusi query pembaruan
    $updateResult = mysqli_query($koneksi, $updateQuery);

    // Periksa apakah pembaruan berhasil
    if ($updateResult) {
        // Jika berhasil, redirect kembali ke halaman products.php
        header("Location: products.php");
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
    <title>Edit Product</title>
</head>

<body>
    <header>
        <!-- Kode header sama seperti di dalam products.php -->
    </header>
    <main>
        <div class="container mt-4">
            <h2>Edit Product</h2>
            <form method="post" action="" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" class="form-control" id="name" name="name"
                        value="<?php echo $product['name']; ?>">
                </div>
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea class="form-control" id="description"
                        name="description"><?php echo $product['description']; ?></textarea>
                </div>
                <div class="form-group">
                    <label for="price">Price:</label>
                    <input type="text" class="form-control" id="price" name="price"
                        value="<?php echo $product['price']; ?>">
                </div>
                <div class="form-group">
                    <label for="stock">Stock:</label>
                    <input type="text" class="form-control" id="stock" name="stock"
                        value="<?php echo $product['stock']; ?>">
                </div>
                <div class="form-group">
                    <label for="image">Image:</label>
                    <?php if ($product['image']): ?>
                        <img src="<?php echo $product['image']; ?>" alt="Product Image" width="100">
                    <?php else: ?>
                        <p>No image available</p>
                    <?php endif; ?>
                    <input type="file" class="form-control" id="image" name="image">
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