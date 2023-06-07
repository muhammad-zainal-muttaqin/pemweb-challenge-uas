<?php
require_once 'TCPDF-6.6.2/tcpdf.php';

// Kode koneksi ke database
require_once 'koneksi.php';

// Query untuk mendapatkan data pesanan
$query = "SELECT O.order_number, O.order_date, P.name AS product_name, OD.quantity, P.price, O.status 
          FROM orders AS O
          INNER JOIN orderdetails AS OD ON O.order_number = OD.order_id
          INNER JOIN products AS P ON OD.product_id = P.id
          INNER JOIN users AS U ON O.user_id = U.id";

$result = mysqli_query($koneksi, $query);

// Inisialisasi objek TCPDF
$pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8');

// Set informasi dokumen
$pdf->SetCreator('Muhammad Zainal Muttaqin');
$pdf->SetAuthor('Muhammad Zainal Muttaqin');
$pdf->SetTitle('Order Report Zainal');
$pdf->SetSubject('Order Report');
$pdf->SetKeywords('Order, Report');

// Set header halaman
$pdf->setHeaderData('', 0, 'Order Report Zainal', '');

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
$pdf->Cell(0, 10, 'Order Report', 0, 1, 'C');

// Tambahkan header tabel
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(20, 7, 'No.', 1, 0, 'C');
$pdf->Cell(40, 7, 'Order Number', 1, 0, 'C');
$pdf->Cell(30, 7, 'Date', 1, 0, 'C');
$pdf->Cell(80, 7, 'Product Details', 1, 0, 'C');
$pdf->Cell(30, 7, 'Quantity', 1, 0, 'C');
$pdf->Cell(30, 7, 'Price', 1, 0, 'C');
$pdf->Cell(40, 7, 'Delivery Status', 1, 1, 'C');

// Tampilkan data pesanan dalam tabel
$pdf->SetFont('helvetica', '', 10);
$totalPrice = 0; // Variabel untuk menyimpan total harga
$number = 1; // Variabel untuk nomor urut
while ($row = mysqli_fetch_assoc($result)) {
    // Set gaya untuk baris ganjil
    $pdf->SetFillColor(240, 240, 240);
    // Set gaya untuk baris genap
    $pdf->SetFillColor(255, 255, 255);

    $pdf->Cell(20, 7, $number, 1, 0, 'C', true);
    $pdf->Cell(40, 7, $row['order_number'], 1, 0, 'C', true);
    $pdf->Cell(30, 7, $row['order_date'], 1, 0, 'C', true);
    $pdf->Cell(80, 7, $row['product_name'], 1, 0, 'C', true);
    $pdf->Cell(30, 7, $row['quantity'], 1, 0, 'C', true);
    $pdf->Cell(30, 7, $row['price'], 1, 0, 'C', true);
    $pdf->Cell(40, 7, $row['status'], 1, 1, 'C', true);

    $totalPrice += ($row['quantity'] * $row['price']); // Tambahkan harga produk (quantity * price) ke total harga
    $number++;
}

// Tambahkan total harga di akhir dokumen
$pdf->Ln(10); // Spasi antara tabel dan total harga
$pdf->Cell(0, 10, 'Total Price: $' . $totalPrice, 0, 1, 'R');

// Output file PDF
$pdf->Output('order_report.pdf', 'I');
?>