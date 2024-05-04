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
    $query = "SELECT * FROM provinces";
    $results = $koneksi->prepare($query);
    $results->execute();
    $result = $results->get_result();
    $data = array();
    $i = 0;
    while ($row = $result->fetch_assoc()) {
        $data[$i]['id'] = $row['id'];
        $data[$i]['province_name'] = $row['province_name'];
        $data[$i]['province_status'] = $row['province_status'] == 1 ? 'Active' : 'Inactive';
        $i++;
    }
    $response = array('data' => $data);
    echo json_encode($response);
    $koneksi->close();
}
if ($pg == 'store') {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = $_POST['id'];
        $province_name = $_POST['province_name'];
        $province_status = isset($_POST['province_status']) ? 1 : 0;
        try {
            error_reporting(E_ALL);
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

            if ($koneksi->connect_error) {
                throw new Exception("Failed to connect to database: " . $koneksi->connect_error);
            }

            $checkExistProvince = "SELECT * FROM provinces WHERE id = ?";
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
            $sql = "INSERT INTO provinces (id, province_name, province_status) VALUES (?, ?, ?)";
            $stmt = $koneksi->prepare($sql);
            if (!$stmt) {
                throw new Exception("Query preparation error: " . $koneksi->error);
            }
            $stmt->bind_param("ssi", $id, $province_name, $province_status);
            if ($stmt->execute()) {
                echo json_encode([
                    'status' => 200,
                    'label' => "success",
                    'message' => 'Province added successfully'
                ]);
            } else {
                throw new Exception("Failed to add province: " . $stmt->error);
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
        $stmt = $koneksi->prepare('SELECT * FROM provinces WHERE id = ?');
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
        $province_name = $_POST['province_name'];
        $province_status = isset($_POST['province_status']) ? 1 : 0;
        try {
            error_reporting(E_ALL);
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

            if ($koneksi->connect_error) {
                throw new Exception("Failed to connect to database: " . $koneksi->connect_error);
            }

            $sql = "UPDATE provinces SET province_name = ?, province_status = ? WHERE id = ?";
            $stmt = $koneksi->prepare($sql);
            if (!$stmt) {
                throw new Exception("Query preparation error: " . $koneksi->error);
            }
            $stmt->bind_param("sii", $province_name, $province_status, $id);
            if ($stmt->execute()) {
                echo json_encode([
                    'status' => 200,
                    'label' => "success",
                    'message' => 'Province updated successfully'
                ]);
            } else {
                throw new Exception("Failed to update province: " . $stmt->error);
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
        $query = "DELETE FROM provinces WHERE id = ?";
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