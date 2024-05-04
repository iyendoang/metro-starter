<?php
require("../../config/database.php");
require("../../config/functions.php");
require("../../config/crud_functions.php");
require("../../vendor/autoload.php");

session_start();
if (!isset($_SESSION['id'])) {
    die('Anda tidak diijinkan mengakses langsung');
}

use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

if ($pg == 'export') {
    try {
        // Create new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $sheet->setCellValue('A1', 'School Name');
        $sheet->setCellValue('B1', 'School NSM');
        $sheet->setCellValue('C1', 'School NPSN');
        // Add more headers as needed

        // Query data from database starting from row 3
        $query = "SELECT * FROM schools";
        $result = $koneksi->query($query);
        $row = 3; // Start from row 3
        while ($data = $result->fetch_assoc()) {
            $sheet->setCellValue('A' . $row, $data['school_name']);
            $sheet->setCellValue('B' . $row, $data['school_nsm']);
            $sheet->setCellValue('C' . $row, $data['school_npsn']);
            // Add more data as needed
            $row++;
        }

        // Save the spreadsheet to a file
        $filename = 'schools_export.xlsx';
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($filename);

        // Atur header response
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
        
        // Tampilkan file Excel ke output
        readfile($filename);
        
        // Hapus file Excel dari server
        unlink($filename);
        exit;
        
    } catch (Exception $e) {
        echo json_encode([
            'status' => 500,
            'label' => "error",
            'message' => $e->getMessage()
        ]);
    }
}
?>
