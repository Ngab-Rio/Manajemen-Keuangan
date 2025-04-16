<?php
require 'vendor/autoload.php'; // Pastikan Anda menggunakan Composer
include "../config/koneksi.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Buat Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Header Kolom
$sheet->setCellValue('A1', 'ID Transaksi');
$sheet->setCellValue('B1', 'Tanggal');
$sheet->setCellValue('C1', 'Jenis');
$sheet->setCellValue('D1', 'Kategori');
$sheet->setCellValue('E1', 'Jumlah');
$sheet->setCellValue('F1', 'Deskripsi');

// Ambil Data dari Database
$query = "SELECT * FROM transactions ORDER BY id DESC";
$result = mysqli_query($conn, $query);
$row = 2; // Mulai dari baris ke-2

while ($data = mysqli_fetch_assoc($result)) {
    $sheet->setCellValue('A' . $row, $data['id_transaction']);
    $sheet->setCellValue('B' . $row, date("d-m-Y H:i", strtotime($data['date'])));
    $sheet->setCellValue('C' . $row, $data['type'] == 'income' ? 'Pemasukan' : 'Pengeluaran');
    $sheet->setCellValue('D' . $row, $data['category']);
    $sheet->setCellValue('E' . $row, $data['amount']);
    $sheet->setCellValue('F' . $row, $data['description']);
    $row++;
}

// Set Header untuk Download
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="transaksi.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit();
