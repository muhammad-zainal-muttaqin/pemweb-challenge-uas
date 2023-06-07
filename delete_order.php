<?php
// Koneksi ke database
include 'koneksi.php';

if (isset($_POST['orderId'])) {
    $orderId = $_POST['orderId'];

    // Query untuk menghapus pesanan berdasarkan order_id   
    $deleteQuery = "DELETE FROM orders WHERE order_number = '$orderId'";
    $deleteResult = mysqli_query($koneksi, $deleteQuery);

    if ($deleteResult) {
        echo "Pesanan berhasil dihapus.";
    } else {
        echo "Gagal menghapus pesanan.";
    }

    // Menutup koneksi database
    mysqli_close($koneksi);
}
?>