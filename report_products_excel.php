<?php
require 'PhpSpreadsheet-1.28.0/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Kode koneksi ke database
require 'koneksi.php';

// Query untuk mendapatkan data produk
$query = "SELECT * FROM Products";
$result = mysqli_query($koneksi, $query);

// Inisialisasi objek Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Tambahkan judul laporan
$sheet->setCellValue('A1', 'Product Report');

// Tambahkan header tabel
$sheet->setCellValue('A3', 'ID');
$sheet->setCellValue('B3', 'Name');
$sheet->setCellValue('C3', 'Description');
$sheet->setCellValue('D3', 'Price');
$sheet->setCellValue('E3', 'Stock');

// Tampilkan data produk dalam tabel
$rowNumber = 4;
while ($row = mysqli_fetch_assoc($result)) {
    $sheet->setCellValue('A' . $rowNumber, $row['id']);
    $sheet->setCellValue('B' . $rowNumber, $row['name']);
    $sheet->setCellValue('C' . $rowNumber, $row['description']);
    $sheet->setCellValue('D' . $rowNumber, $row['price']);
    $sheet->setCellValue('E' . $rowNumber, $row['stock']);
    $rowNumber++;
}

// Set auto width untuk kolom
foreach (range('A', 'E') as $column) {
    $sheet->getColumnDimension($column)->setAutoSize(true);
}

// Buat objek Writer untuk menulis ke file Excel
$writer = new Xlsx($spreadsheet);

// Simpan file Excel
$writer->save('product_report.xlsx');
