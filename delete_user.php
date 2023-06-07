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

    // Query untuk menghapus pengguna berdasarkan id
    $deleteQuery = "DELETE FROM Users WHERE id = $userId";

    // Eksekusi query penghapusan
    $deleteResult = mysqli_query($koneksi, $deleteQuery);

    // Periksa apakah pengguna berhasil dihapus
    if ($deleteResult) {
        // Jika berhasil, redirect kembali ke halaman users.php
        header("Location: users.php");
        exit;
    } else {
        // Jika gagal, tampilkan pesan error
        echo "Error: " . mysqli_error($koneksi);
    }
} else {
    // Jika parameter id tidak diberikan, redirect kembali ke halaman users.php
    header("Location: users.php");
    exit;
}
?>