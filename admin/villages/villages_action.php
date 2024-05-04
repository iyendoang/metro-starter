<?php
require("../../config/database.php");
require("../../config/functions.php");
require("../../config/crud_functions.php");
session_start();
if (!isset($_SESSION['id'])) {
    die('Anda tidak diijinkan mengakses langsung');
}
header('Content-Type: application/json');
if ($pg == 'index') {
    $province_id = $_POST['province_id'];
    if (!empty($province_id)) {
        $query = "SELECT 
            villages.id, 
            districts.id AS district_id, 
            districts.district_name, 
            regencies.id AS regency_id, 
            regencies.regency_name, 
            provinces.id AS province_id, 
            provinces.province_name, 
            villages.village_name, 
            villages.village_status
            FROM villages 
            LEFT JOIN districts ON villages.district_id = districts.id
            LEFT JOIN regencies ON districts.regency_id = regencies.id
            LEFT JOIN provinces ON regencies.province_id = provinces.id
            WHERE regencies.province_id = ?"; // tambahkan kondisi WHERE
        $results = $koneksi->prepare($query);
        $results->bind_param("i", $province_id); // bind parameter
    } else {
        // Jika district_id kosong, tampilkan tabel kosong
        echo json_encode(array('data' => []));
        exit; // keluar dari script
    }

    $results->execute();
    $result = $results->get_result();
    $data = array();
    $i = 0;
    while ($row = $result->fetch_assoc()) {
        $data[$i]['id'] = $row['id'];
        $data[$i]['village_name'] = $row['village_name'];
        $data[$i]['district_name'] = $row['district_name'];
        $data[$i]['regency_name'] = $row['regency_name'];
        $data[$i]['province_name'] = $row['province_name'];
        $data[$i]['village_status'] = $row['village_status'] == 1 ? 'Active' : 'Inactive';
        $i++;
    }
    $response = array('data' => $data);
    echo json_encode($response);
    $koneksi->close();
}

if ($pg == 'store') {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = $_POST['id'];
        $province_id = $_POST['province_id'];
        $regency_id = $_POST['regency_id'];
        $district_id = $_POST['district_id'];
        $village_name = $_POST['village_name'];
        $village_status = isset($_POST['village_status']) ? 1 : 0;
        try {
            error_reporting(E_ALL);
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

            if ($koneksi->connect_error) {
                throw new Exception("Failed to connect to database: " . $koneksi->connect_error);
            }

            $checkExistProvince = "SELECT * FROM villages WHERE id = ?";
            $stmt = $koneksi->prepare($checkExistProvince);
            $stmt->bind_param("s", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                echo json_encode([
                    'status' => 400,
                    'label' => "error",
                    'message' => 'Province already exists'
                ]);
                exit;
            }
            $sql = "INSERT INTO villages (id, district_id, village_name, village_status) VALUES (?, ?, ?, ?)";
            $stmt = $koneksi->prepare($sql);
            if (!$stmt) {
                throw new Exception("Query preparation error: " . $koneksi->error);
            }
            $stmt->bind_param("sssi", $id, $district_id, $village_name, $village_status);
            if ($stmt->execute()) {
                echo json_encode([
                    'status' => 200,
                    'label' => "success",
                    'province_id' => $province_id,
                    'message' => 'Province added successfully'
                ]);
            } else {
                throw new Exception("Failed to add regency: " . $stmt->error);
            }
            $stmt->close();
            $koneksi->close();
        } catch (Exception $e) {
            echo json_encode([
                'status' => 500,
                'label' => "error",
                'message' => $e->getMessage()
            ]);
        }
    } else {
        echo json_encode([
            'status' => 400,
            'label' => "error",
            'message' => 'Invalid request'
        ]);
    }
}
if ($pg == 'show') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'];
        $stmt = $koneksi->prepare('SELECT 
        villages.id, 
        districts.id AS district_id, 
        districts.district_name, 
        regencies.id AS regency_id, 
        regencies.regency_name, 
        provinces.id AS province_id, 
        provinces.province_name, 
        villages.village_name, 
        villages.village_status
        FROM villages 
        LEFT JOIN districts ON villages.district_id = districts.id
        LEFT JOIN regencies ON districts.regency_id = regencies.id
        LEFT JOIN provinces ON regencies.province_id = provinces.id WHERE villages.id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            $lembagaData = $result->fetch_assoc();
            echo json_encode($lembagaData);
        } else {
            echo json_encode(['error' => 'Failed to fetch lembaga data']);
        }
        $stmt->close();
        $koneksi->close();
    } else {
        echo json_encode(['error' => 'Invalid request method']);
    }
}
if ($pg == 'update') {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = $_POST['id'];
        $id_old = $_POST['id_old'];
        $province_id = $_POST['province_id'];
        $regency_id = $_POST['regency_id'];
        $district_id = $_POST['district_id'];
        $village_name = $_POST['village_name'];
        $village_status = isset($_POST['village_status']) ? 1 : 0;
        try {
            error_reporting(E_ALL);
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

            if ($koneksi->connect_error) {
                throw new Exception("Failed to connect to database: " . $koneksi->connect_error);
            }
            $check_query = "SELECT * FROM villages WHERE id = ?";
            $check_stmt = $koneksi->prepare($check_query);
            $check_stmt->bind_param("i", $id_old);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();

            if ($check_result->num_rows === 0) {
                throw new Exception("ID not found");
            }

            // Cek apakah ID baru sudah ada atau tidak
            $check_new_id_query = "SELECT * FROM villages WHERE id = ? AND id <> ?";
            $check_new_id_stmt = $koneksi->prepare($check_new_id_query);
            $check_new_id_stmt->bind_param("ii", $id, $id_old);
            $check_new_id_stmt->execute();
            $check_new_id_result = $check_new_id_stmt->get_result();

            if ($check_new_id_result->num_rows > 0) {
                throw new Exception("New ID already exists");
            }

            $sql = "UPDATE villages SET id = ?, district_id = ?, village_name = ?, village_status = ? WHERE id = ?";
            $stmt = $koneksi->prepare($sql);
            if (!$stmt) {
                throw new Exception("Query preparation error: " . $koneksi->error);
            }
            $stmt->bind_param("iissi", $id, $district_id, $village_name, $village_status, $id_old);
            if ($stmt->execute()) {
                echo json_encode([
                    'status' => 200,
                    'label' => "success",
                    'province_id' => $province_id,
                    'message' => 'Province updated successfully'
                ]);
            } else {
                throw new Exception("Failed to update regency: " . $stmt->error);
            }
            $stmt->close();
            $koneksi->close();
        } catch (Exception $e) {
            echo json_encode([
                'status' => 500,
                'label' => "error",
                'message' => $e->getMessage()
            ]);
        }
    } else {
        echo json_encode([
            'status' => 400,
            'label' => "error",
            'message' => 'Invalid request'
        ]);
    }
}
if ($pg == 'delete') {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = $_POST['id'];
        $query = "DELETE FROM villages WHERE id = ?";
        $stmt = $koneksi->prepare($query);
        $stmt->bind_param('s', $id);
        if ($stmt->execute()) {
            echo json_encode(['status' => 200, 'message' => 'Data provinsi berhasil dihapus']);
        } else {
            echo json_encode(['status' => 500, 'message' => 'Gagal menghapus data provinsi']);
        }
    } else {
        echo json_encode(['status' => 400, 'message' => 'Permintaan tidak valid']);
    }
}
