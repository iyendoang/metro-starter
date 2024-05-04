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
    $query = "SELECT 
    districts.id, 
    regencies.id AS regency_id, 
    regencies.regency_name, 
    provinces.id AS province_id, 
    provinces.province_name, 
    districts.district_name, 
    districts.district_status
    FROM districts 
    LEFT JOIN regencies ON districts.regency_id = regencies.id
    LEFT JOIN provinces ON regencies.province_id = provinces.id;";
    $results = $koneksi->prepare($query);
    $results->execute();
    $result = $results->get_result();
    $data = array();
    $i = 0;
    while ($row = $result->fetch_assoc()) {
        $data[$i]['id'] = $row['id'];
        $data[$i]['district_name'] = $row['district_name'];
        $data[$i]['regency_name'] = $row['regency_name'];
        $data[$i]['province_name'] = $row['province_name'];
        $data[$i]['district_status'] = $row['district_status'] == 1 ? 'Active' : 'Inactive';
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
        $district_name = $_POST['district_name'];
        $district_status = isset($_POST['district_status']) ? 1 : 0;
        try {
            error_reporting(E_ALL);
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

            if ($koneksi->connect_error) {
                throw new Exception("Failed to connect to database: " . $koneksi->connect_error);
            }

            $checkExistProvince = "SELECT * FROM districts WHERE id = ?";
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
            $sql = "INSERT INTO districts (id, regency_id, district_name, district_status) VALUES (?, ?, ?, ?)";
            $stmt = $koneksi->prepare($sql);
            if (!$stmt) {
                throw new Exception("Query preparation error: " . $koneksi->error);
            }
            $stmt->bind_param("sssi", $id, $regency_id, $district_name, $district_status);
            if ($stmt->execute()) {
                echo json_encode([
                    'status' => 200,
                    'label' => "success",
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
        districts.id, 
        regencies.id AS regency_id, 
        regencies.regency_name, 
        provinces.id AS province_id, 
        provinces.province_name, 
        districts.district_name, 
        districts.district_status
        FROM districts 
        LEFT JOIN regencies ON districts.regency_id = regencies.id
        LEFT JOIN provinces ON regencies.province_id = provinces.id WHERE districts.id = ?');
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
        $regency_id = $_POST['regency_id'];
        $district_name = $_POST['district_name'];
        $district_status = isset($_POST['district_status']) ? 1 : 0;
        try {
            error_reporting(E_ALL);
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

            if ($koneksi->connect_error) {
                throw new Exception("Failed to connect to database: " . $koneksi->connect_error);
            }
            $check_query = "SELECT * FROM districts WHERE id = ?";
            $check_stmt = $koneksi->prepare($check_query);
            $check_stmt->bind_param("i", $id_old);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();

            if ($check_result->num_rows === 0) {
                throw new Exception("ID not found");
            }

            // Cek apakah ID baru sudah ada atau tidak
            $check_new_id_query = "SELECT * FROM districts WHERE id = ? AND id <> ?";
            $check_new_id_stmt = $koneksi->prepare($check_new_id_query);
            $check_new_id_stmt->bind_param("ii", $id, $id_old);
            $check_new_id_stmt->execute();
            $check_new_id_result = $check_new_id_stmt->get_result();

            if ($check_new_id_result->num_rows > 0) {
                throw new Exception("New ID already exists");
            }

            $sql = "UPDATE districts SET id = ?, regency_id = ?, district_name = ?, district_status = ? WHERE id = ?";
            $stmt = $koneksi->prepare($sql);
            if (!$stmt) {
                throw new Exception("Query preparation error: " . $koneksi->error);
            }
            $stmt->bind_param("iissi", $id, $regency_id, $district_name, $district_status, $id_old);
            if ($stmt->execute()) {
                echo json_encode([
                    'status' => 200,
                    'label' => "success",
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
        $query = "DELETE FROM districts WHERE id = ?";
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
