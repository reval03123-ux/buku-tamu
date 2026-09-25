<?php
include('koneksi.php');
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// 1. Title / Judul Laporan di Atas
$sheet->mergeCells('A1:G1');
$sheet->setCellValue('A1', 'LAPORAN DATA BUKU TAMU');
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
$sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// Sub-judul Periode jika dipreview/filter
if (isset($_GET['p_awal']) && isset($_GET['p_akhir'])) {
    $p_awal = $_GET['p_awal'];
    $p_akhir = $_GET['p_akhir'];
    $sheet->mergeCells('A2:G2');
    $sheet->setCellValue('A2', "Periode: $p_awal s.d $p_akhir");
    $sheet->getStyle('A2')->getFont()->setItalic(true)->setSize(11);
    $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    $data = mysqli_query($koneksi, "SELECT * FROM buku_tamu WHERE tanggal BETWEEN '$p_awal' AND '$p_akhir' ORDER BY tanggal DESC");
    $startRow = 4; // Baris header tabel jika ada sub-judul
} else {
    $data = mysqli_query($koneksi, "SELECT * FROM buku_tamu ORDER BY tanggal DESC");
    $startRow = 3; // Baris header tabel
}

// 2. Header Tabel
$sheet->setCellValue('A' . $startRow, 'NO');
$sheet->setCellValue('B' . $startRow, 'TANGGAL');
$sheet->setCellValue('C' . $startRow, 'NAMA TAMU');
$sheet->setCellValue('D' . $startRow, 'ALAMAT');
$sheet->setCellValue('E' . $startRow, 'NO TELEPON/HP');
$sheet->setCellValue('F' . $startRow, 'BERTEMU DENGAN');
$sheet->setCellValue('G' . $startRow, 'KEPENTINGAN');

// Styling Header Tabel (Background Hijau Soft, Font Bold, Text Center)
$headerStyle = [
    'font' => [
        'bold' => true,
        'color' => ['rgb' => 'FFFFFF'],
        'size' => 11
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => '1E7E34'] // Warna Hijau SB Admin / Excel
    ]
];
$sheet->getStyle("A{$startRow}:G{$startRow}")->applyFromArray($headerStyle);
$sheet->getRowDimension($startRow)->setRowHeight(25);

// 3. Populate Data Body
$i = $startRow + 1;
$no = 1;

while ($d = mysqli_fetch_array($data)) {
    $sheet->setCellValue('A' . $i, $no++);
    $sheet->setCellValue('B' . $i, $d['tanggal']);
    $sheet->setCellValue('C' . $i, $d['nama_tamu']);
    $sheet->setCellValue('D' . $i, $d['alamat']);
    $sheet->setCellValue('E' . $i, $d['no_hp']);
    $sheet->setCellValue('F' . $i, $d['bertemu']);
    $sheet->setCellValue('G' . $i, $d['kepentingan']);

    // Format Text untuk Nomor HP agar angka 0 di depan tidak hilang
    $sheet->getStyle('E' . $i)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);

    // Perataan Kolom (Alignment)
    $sheet->getStyle('A' . $i)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('B' . $i)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('E' . $i)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    // Vertical align center untuk semua baris
    $sheet->getStyle("A{$i}:G{$i}")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

    $i++;
}

$endRow = $i - 1;

// 4. Styling Border untuk Seluruh Tabel
$borderStyle = [
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['rgb' => '000000'],
        ],
    ],
];

if ($endRow >= ($startRow + 1)) {
    $sheet->getStyle("A{$startRow}:G{$endRow}")->applyFromArray($borderStyle);
}

// 5. Auto-size Lebar Kolom (Otomatis menyesuaikan isi data)
foreach (range('A', 'G') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}

// 6. Header Response untuk Auto Download
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Laporan_Buku_Tamu_' . date('Ymd_His') . '.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
