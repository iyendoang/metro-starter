<?php
require("../../config/database.php");
require("../../config/functions.php");
require("../../config/crud_functions.php");
require "../../../vendor/autoload.php";


session_start();
if (!isset($_SESSION['id'])) {
    die('Anda tidak diijinkan mengakses langsung');
}

use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

if ($pg == 'import') {
    $berhasil = 0;
    $gagal = 0;
    $insert = 0;
    $update = 0;
    $response = []; // Initialize the response array
    $allowedExtensions = ['xlsx'];
    $fileExtension = pathinfo($_FILES['shcool_import_file']['name'], PATHINFO_EXTENSION);

    if (!in_array($fileExtension, $allowedExtensions)) {
        echo json_encode([
            'status' => 400,
            'label' => 'error',
            'message' => 'Harap pilih file excel .xlsx'
        ]);
        exit; // Keluar dari skrip jika ekstensi tidak valid
    }

    try {
        // Load the uploaded file
        $file = $_FILES['shcool_import_file']['tmp_name'];
        $spreadsheet = IOFactory::load($file);
        $worksheet = $spreadsheet->getActiveSheet();

        // Initialize counts
        $berhasil = 0;
        $gagal = 0;
        $insert = 0;
        $update = 0;

        // Loop through each row in the Excel file
        foreach ($worksheet->getRowIterator() as $key => $row) {
            // Skip the first two rows (headers)
            if ($key <= 2) {
                continue;
            }

            $rowData = [];
            foreach ($row->getCellIterator() as $cell) {
                $rowData[] = $cell->getValue();
            }

            // Extract data from the row
            $school_name = $rowData[0];
            $school_nsm = $rowData[1];
            $school_npsn = $rowData[2] ?? ''; // Default to empty string if NPSN is not provided
            $jenjang_id = $rowData[3];
            $school_status = $rowData[4];
            $school_phone = $rowData[5];
            $province_id = $rowData[6];
            $regency_id = $rowData[7];
            $district_id = $rowData[8];
            $village_id = $rowData[9];
            $school_address = $rowData[10];
            $school_active = $rowData[11];

            // Check if school NPSN already exists
            $checkExistSchool = "SELECT * FROM schools WHERE school_npsn = ?";
            $stmt = $koneksi->prepare($checkExistSchool);
            $stmt->bind_param("s", $school_npsn);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            if ($result->num_rows > 0) {
                // School NPSN already exists, update the existing record
                $updateSql = "UPDATE schools SET school_name=?, school_status=?, jenjang_id=?, school_nsm=?, school_phone=?, province_id=?, regency_id=?, district_id=?, village_id=?, school_address=?, school_active=? WHERE school_npsn=?";
                $stmt = $koneksi->prepare($updateSql);
                $stmt->bind_param("ssssssssssis", $school_name, $school_status, $jenjang_id, $school_nsm, $school_phone, $province_id, $regency_id, $district_id, $village_id, $school_address, $school_active, $school_npsn);
                $update++;
            } else {
                // School NPSN does not exist, insert a new record
                $insertSql = "INSERT INTO schools (school_name, school_status, jenjang_id, school_npsn, school_nsm, school_phone, province_id, regency_id, district_id, village_id, school_address, school_active) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $koneksi->prepare($insertSql);
                $stmt->bind_param("sssssssssssi", $school_name, $school_status, $jenjang_id, $school_npsn, $school_nsm, $school_phone, $province_id, $regency_id, $district_id, $village_id, $school_address, $school_active);
                $insert++;
            }

            // Execute the statement
            if ($stmt->execute()) {
                $berhasil++;
            } else {
                $gagal++;
            }
            $stmt->close();
        }

        echo json_encode([
            'status' => 200,
            'label' => "success",
            'message' => 'Data imported successfully',
            'inserted' => $insert,
            'updated' => $update,
            'failed' => $gagal
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 500,
            'label' => "error",
            'message' => $e->getMessage()
        ]);
    }
}