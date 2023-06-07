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
    header("Location: login.php");
    exit;
}

// Cek apakah tombol logout ditekan
if (isset($_POST['logout'])) {
    logout();
}

// Koneksi ke database
include 'koneksi.php';

// Initialize the total price variable
$totalPrice = 0;

// Query untuk mengambil data dari tabel Orders
$query = "SELECT O.order_number, O.order_date, U.username, P.name AS product_name, P.price, OD.quantity, O.status 
          FROM orders AS O
          INNER JOIN orderdetails AS OD ON O.order_number = OD.order_id
          INNER JOIN products AS P ON OD.product_id = P.id
          INNER JOIN users AS U ON O.user_id = U.id";

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
    <title>Orders</title>
    <style>
        @media print {

            /* Styles untuk tampilan saat di-print */
            header,
            footer,
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <header class="no-print">
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
            <h2>Orders</h2>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 5%;">No.</th>
                            <th style="width: 15%;">Order Number</th>
                            <th style="width: 15%;">Date</th>
                            <th style="width: 20%;">Product Details</th>
                            <th style="width: 10%;">Quantity</th>
                            <th style="width: 10%;">Price</th>
                            <th style="width: 20%;">Delivery Status</th>
                            <th style="width: 15%;" class="no-print">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Perulangan untuk menampilkan data pesanan
                        $number = 1; // Inisialisasi nomor urut
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<tr id="order-' . $row['order_number'] . '">';
                            echo '<td>' . $number . '</td>';
                            echo '<td>' . $row['order_number'] . '</td>';
                            echo '<td>' . $row['order_date'] . '</td>';
                            echo '<td>' . $row['product_name'] . '</td>';
                            echo '<td>' . $row['quantity'] . '</td>';
                            echo '<td>' . $row['price'] . '</td>'; // Display the price
                            echo '<td>
                <span id="status-' . $row['order_number'] . '">' . $row['status'] . '</span>
                <select class="form-select delivery-status no-print" data-order-number="' . $row['order_number'] . '">
                    <option value="Pending" ' . ($row['status'] == 'Pending' ? 'selected' : '') . '>Pending</option>
                    <option value="Shipped" ' . ($row['status'] == 'Shipped' ? 'selected' : '') . '>Shipped</option>
                    <option value="Delivered" ' . ($row['status'] == 'Delivered' ? 'selected' : '') . '>Delivered</option>
                    <option value="Cancelled" ' . ($row['status'] == 'Cancelled' ? 'selected' : '') . '>Cancelled</option>
                </select>
            </td>';
                            echo '<td class="no-print">
                <a href="#" class="btn btn-primary update-status-btn" data-order-id="' . $row['order_number'] . '">Update Status</a>
            </td>';
                            echo '</tr>';

                            // Calculate the subtotal for the current order row
                            $subtotal = $row['price'] * $row['quantity'];
                            // Add the subtotal to the total price
                            $totalPrice += $subtotal;

                            // Increase the order number
                            $number++;
                        }
                        ?>

                        <!-- Display the total price row -->
                        <tr>
                            <td colspan="6" align="right"><strong>Total Price:</strong></td>
                            <td>
                                <?php echo $totalPrice; ?>
                            </td>
                            <td class="no-print"></td>
                        </tr>
                    </tbody>
                </table>
                <div class="mt-4 mb-4">
                    <a href="report_orders_pdf.php" class="btn btn-primary no-print">Export to PDF</a><br>
                    <button class="btn btn-primary mt-3 no-print" onclick="window.print()">Print</button>
                </div>
            </div>
        </div>
    </main>


    <!-- Tambahkan link ke Bootstrap JavaScript di sini -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            // Fungsi untuk mengupdate status pesanan menggunakan AJAX
            function updateStatus(orderId, status) {
                $.ajax({
                    url: "update_status.php",
                    type: "POST",
                    data: { orderId: orderId, status: status },
                    success: function (response) {
                        console.log(response);
                        // Update teks status pada elemen <span> dengan ID yang sesuai
                        $("#status-" + orderId).text(status);
                    },
                    error: function (xhr, status, error) {
                        console.log(xhr.responseText);
                    }
                });
            }

            // Event listener untuk memperbarui status pesanan
            $(".update-status-btn").click(function () {
                var orderId = $(this).data("order-id");
                var status = $(".delivery-status[data-order-number='" + orderId + "']").val();
                updateStatus(orderId, status);
            });

            // Event listener untuk menghapus pesanan
            $(".delete-btn").click(function () {
                var orderId = $(this).data("order-id");
                if (confirm("Are you sure you want to delete this order?")) {
                    $.ajax({
                        url: "delete_order.php",
                        type: "POST",
                        data: { orderId: orderId },
                        success: function (response) {
                            console.log(response);
                            // Hapus baris pesanan dari tabel
                            $("#order-" + orderId).remove();
                        },
                        error: function (xhr, status, error) {
                            console.log(xhr.responseText);
                        }
                    });
                }
            });
        });
    </script>
</body>

</html>