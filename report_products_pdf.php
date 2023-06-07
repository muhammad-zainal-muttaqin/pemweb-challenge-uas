<?php
require_once 'TCPDF-6.6.2/tcpdf.php';

// Kode koneksi ke database
require_once 'koneksi.php';

// Query untuk mendapatkan data produk
$query = "SELECT * FROM Products";
$result = mysqli_query($koneksi, $query);

// Inisialisasi objek TCPDF
$pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8');

// Set informasi dokumen
$pdf->SetCreator('Muhammad Zainal Muttaqin');
$pdf->SetAuthor('Muhammad Zainal Muttaqin');
$pdf->SetTitle('Product Report Zainal');
$pdf->SetSubject('Product Report');
$pdf->SetKeywords('Product, Report');

// Set header halaman
$pdf->setHeaderData('', 0, 'Product Report Zainal', '');

// Set footer halaman
$pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

// Set ukuran margin
$pdf->SetMargins(20, 20, 20, true);

// Set mode autofit
$pdf->SetAutoPageBreak(true, 20);

// Tambahkan halaman
$pdf->AddPage('L');

// Set font
$pdf->SetFont('helvetica', '', 12);

// Tambahkan judul laporan
$pdf->Cell(0, 10, 'Product Report', 0, 1, 'C');

// Tambahkan header tabel
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(30, 7, 'ID', 1, 0, 'C');
$pdf->Cell(60, 7, 'Name', 1, 0, 'C');
$pdf->Cell(80, 7, 'Description', 1, 0, 'C');
$pdf->Cell(30, 7, 'Price', 1, 0, 'C');
$pdf->Cell(30, 7, 'Stock', 1, 1, 'C');

// Tampilkan data produk dalam tabel
$pdf->SetFont('helvetica', '', 10);
$totalPrice = 0; // Variabel untuk menyimpan total harga
while ($row = mysqli_fetch_assoc($result)) {
    // Set gaya untuk baris ganjil
    $pdf->SetFillColor(240, 240, 240);
    // Set gaya untuk baris genap
    $pdf->SetFillColor(255, 255, 255);

    $pdf->Cell(30, 7, $row['id'], 1, 0, 'C', true);
    $pdf->Cell(60, 7, $row['name'], 1, 0, 'C', true);
    $pdf->Cell(80, 7, $row['description'], 1, 0, 'C', true);
    $pdf->Cell(30, 7, $row['price'], 1, 0, 'C', true);
    $pdf->Cell(30, 7, $row['stock'], 1, 1, 'C', true);

    $totalPrice += $row['price']; // Tambahkan harga produk ke total harga
}

// Tambahkan total harga di akhir dokumen
$pdf->Ln(10); // Spasi antara tabel dan total harga
$pdf->Cell(0, 10, 'Total Price: $' . $totalPrice, 0, 1, 'R');

// Output file PDF
$pdf->Output('product_report.pdf', 'I');
?>