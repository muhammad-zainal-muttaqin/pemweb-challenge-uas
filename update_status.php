<?php
// Koneksi ke database
include 'koneksi.php';

// Cek apakah ada data yang dikirim melalui metode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['orderId']) && isset($_POST['status'])) {
    $orderId = $_POST['orderId'];
    $status = $_POST['status'];

    // Update status pesanan dalam database
    $updateQuery = "UPDATE Orders SET status = '$status' WHERE order_number = '$orderId'";
    $updateResult = mysqli_query($koneksi, $updateQuery);

    if ($updateResult) {
        echo "Status updated successfully";
    } else {
        echo "Failed to update status";
    }
} else {
    echo "Invalid request";
}

?>