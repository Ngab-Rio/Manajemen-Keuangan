<?php
require 'vendor/autoload.php'; // Pastikan Anda menggunakan Composer
include "../config/koneksi.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Buat Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Header Kolom
$sheet->setCellValue('A1', 'Tanggal');
$sheet->setCellValue('B1', 'Aksi');
$sheet->setCellValue('C1', 'Deskripsi');

// Ambil Data dari Database
$query = "SELECT * FROM logs ORDER BY id DESC";
$result = mysqli_query($conn, $query);
$row = 2; // Mulai dari baris ke-2

while ($data = mysqli_fetch_assoc($result)) {
    $sheet->setCellValue('A' . $row, date("d-m-Y H:i", strtotime($data['timestamp'])));
    $sheet->setCellValue('B' . $row, $data['action']);
    $sheet->setCellValue('C' . $row, $data['description']);
    $row++;
}

// Set Header untuk Download
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="history_transaksi.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit();
