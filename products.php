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

// Konfigurasi pagination
$itemsPerPage = 10;
$currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;

// Cek apakah ada kata kunci pencarian
$searchKeyword = isset($_GET['search']) ? $_GET['search'] : '';

// Query untuk mendapatkan jumlah total produk
$countQuery = "SELECT COUNT(*) AS total FROM Products";
if (!empty($searchKeyword)) {
    $countQuery .= " WHERE name LIKE '%$searchKeyword%'";
}
$countResult = mysqli_query($koneksi, $countQuery);
$totalItems = mysqli_fetch_assoc($countResult)['total'];

// Hitung jumlah total halaman
$totalPages = ceil($totalItems / $itemsPerPage);

// Hitung offset
$offset = ($currentPage - 1) * $itemsPerPage;

// Query untuk mendapatkan data produk dengan pagination dan pencarian
$query = "SELECT * FROM Products";
if (!empty($searchKeyword)) {
    $query .= " WHERE name LIKE '%$searchKeyword%'";
}
$query .= " LIMIT $itemsPerPage OFFSET $offset";
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
    <title>Products</title>
    <style>
        @media (min-width: 992px) {
            .navbar-nav .ms-auto {
                margin-left: auto;
            }
        }

        @media print {
            body * {
                visibility: hidden;
            }

            #print-table,
            #print-table * {
                visibility: visible;
            }

            #print-table {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }

            .print-hide {
                display: none;
            }

            .no-print {
                display: none;
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
                            <a class="nav-link" href="add_product.php">Add Product</a>
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
            <h2>Products</h2>
            <div class="mb-3">
                <form method="get" action="">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search" name="search">
                        <button class="btn btn-primary" type="submit">Search</button>
                    </div>
                </form>
            </div>
            <div id="print-table" class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 20%;">Name</th>
                            <th style="width: 30%;">Description</th>
                            <th style="width: 12%;">Price (k)</th>
                            <th style="width: 8%;">Stock (pcs)</th>
                            <th style="width: 15%;">Image</th>
                            <th style="width: 15%;" class="print-hide">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Variabel total harga
                        $totalPrice = 0;
                        // Perulangan untuk menampilkan data produk dari hasil query
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<tr>';
                            echo '<td>' . $row['name'] . '</td>';
                            echo '<td>' . $row['description'] . '</td>';
                            echo '<td>' . $row['price'] . '</td>';
                            echo '<td>' . $row['stock'] . '</td>';
                            echo '<td><img src="' . $row['image'] . '" alt="Product Image" width="100"></td>';
                            echo '<td class="print-hide">';
                            echo '<a href="edit_products.php?id=' . $row['id'] . '" class="btn btn-primary print-hide">Edit</a>';
                            echo '<a href="delete_products.php?id=' . $row['id'] . '" class="btn btn-danger print-hide">Delete</a>';
                            echo '</td>';
                            $totalPrice += $row['price'];
                            echo '</tr>';
                        }
                        ?>
                        <!-- Baris total harga -->
                        <tr>
                            <td colspan="2"></td>
                            <td colspan="2" class=""><strong>Total Price:</strong>
                                <?php echo $totalPrice; ?>
                            </td>
                            <td class=""></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <nav aria-label="Pagination">
                <ul class="pagination justify-content-center">
                    <?php
                    // Tampilkan tombol navigasi halaman sebelumnya jika tidak berada di halaman pertama
                    if ($currentPage > 1) {
                        echo '<li class="page-item"><a class="page-link" href="?page=' . ($currentPage - 1) . '">Previous</a></li>';
                    }

                    // Tampilkan link ke setiap halaman
                    for ($i = 1; $i <= $totalPages; $i++) {
                        echo '<li class="page-item ' . ($currentPage == $i ? 'active' : '') . '"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
                    }

                    // Tampilkan tombol navigasi halaman selanjutnya jika tidak berada di halaman terakhir
                    if ($currentPage < $totalPages) {
                        echo '<li class="page-item"><a class="page-link" href="?page=' . ($currentPage + 1) . '">Next</a></li>';
                    }
                    ?>
                </ul>
            </nav>
            <div class="mt-4 mb-4">
                <a href="report_products_pdf.php" class="btn btn-primary">Export to PDF</a>
                <button class="btn btn-primary" onclick="printTable()">Print</button>
            </div>
        </div>
    </main>

    <!-- Tambahkan link ke Bootstrap JavaScript di sini -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js">
    </script>
    <script>
        function printTable() {
            window.print();
        }
    </script>
</body>

</html>