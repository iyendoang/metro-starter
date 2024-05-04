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
    $query = "SELECT s.*, 
    COALESCE(p.province_name, '') as province_name, 
    COALESCE(r.regency_name, '') as regency_name, 
    COALESCE(d.district_name, '') as district_name, 
    COALESCE(j.jenjang_alias, '') as jenjang_alias
        FROM schools s
        LEFT JOIN provinces p ON s.province_id = p.id
        LEFT JOIN regencies r ON s.regency_id = r.id
        LEFT JOIN districts d ON s.district_id = d.id
        LEFT JOIN jenjangs j ON s.jenjang_id = j.jenjang_id
        ORDER BY s.school_name";
    $results = $koneksi->prepare($query);
    $results->execute();
    $result = $results->get_result();
    $data = array();
    $i = 0;
    $no = 1;
    while ($row = $result->fetch_assoc()) {
        $data[$i]['no'] = $no++;
        $data[$i]['school_id'] = $row['school_id'];
        $data[$i]['school_name'] = $row['school_name'];
        $data[$i]['school_npsn'] = $row['school_npsn'];
        $data[$i]['school_nsm'] = $row['school_nsm'];
        $data[$i]['province_name'] = $row['province_name'];
        $data[$i]['school_status'] = $row['school_status'];
        $data[$i]['regency_name'] = $row['regency_name'];
        $data[$i]['district_name'] = $row['district_name'];
        $data[$i]['jenjang_alias'] = $row['jenjang_alias'];
        $data[$i]['school_active'] = $row['school_status'] == 1 ? 'Active' : 'Inactive';
        $i++;
    }
    $response = array('data' => $data);
    echo json_encode($response);
    $koneksi->close();
}
if ($pg == 'store') {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $school_name = $_POST['school_name'];
        $jenjang_id = $_POST['jenjang_id'];
        $school_status = $_POST['school_status'];
        $school_npsn = $_POST['school_npsn'];
        $school_nsm = $_POST['school_nsm'];
        $school_phone = $_POST['school_phone'];
        $province_id = $_POST['province_id'];
        $regency_id = $_POST['regency_id'];
        $district_id = $_POST['district_id'];
        $village_id = $_POST['village_id'];
        $school_address = $_POST['school_address'];
        $school_active = isset($_POST['school_active']) ? 1 : 0;

        try {
            error_reporting(E_ALL);
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

            if ($koneksi->connect_error) {
                throw new Exception("Failed to connect to database: " . $koneksi->connect_error);
            }

            $checkExistSchool = "SELECT * FROM schools WHERE school_npsn = ?";
            $stmt = $koneksi->prepare($checkExistSchool);
            $stmt->bind_param("s", $school_npsn);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                echo json_encode([
                    'status' => 400,
                    'label' => "error",
                    'message' => 'School NPSN already exists'
                ]);
                exit;
            }

            $sql = "INSERT INTO schools (school_name, school_status, jenjang_id, school_npsn, school_nsm, school_phone, province_id, regency_id, district_id, village_id, school_address, school_active) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $koneksi->prepare($sql);
            if (!$stmt) {
                throw new Exception("Query preparation error: " . $koneksi->error);
            }
            $stmt->bind_param("sssssssssssi", $school_name, $school_status, $jenjang_id, $school_npsn, $school_nsm, $school_phone, $province_id, $regency_id, $district_id, $village_id, $school_address, $school_active);

            if ($stmt->execute()) {
                echo json_encode([
                    'status' => 200,
                    'label' => "success",
                    'message' => 'School added successfully'
                ]);
            } else {
                throw new Exception("Failed to add school: " . $stmt->error);
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
        $school_id = $_POST['school_id'];
        $sql = 'SELECT s.*, 
        COALESCE(p.province_name, "") as province_name, 
        COALESCE(r.regency_name, "") as regency_name, 
        COALESCE(d.district_name, "") as district_name, 
        COALESCE(v.village_name, "") as village_name, 
        COALESCE(j.jenjang_name, "") as jenjang_name
        FROM schools s
        LEFT JOIN provinces p ON s.province_id = p.id
        LEFT JOIN regencies r ON s.regency_id = r.id
        LEFT JOIN districts d ON s.district_id = d.id
        LEFT JOIN villages v ON s.village_id = v.id
        LEFT JOIN jenjangs j ON s.jenjang_id = j.jenjang_id
        WHERE s.school_id = ?';
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param('s', $school_id);
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
        $school_id = $_POST['school_id'];
        $school_name = $_POST['school_name'];
        $jenjang_id = $_POST['jenjang_id'];
        $school_status = $_POST['school_status'];
        $school_npsn = $_POST['school_npsn'];
        $school_nsm = $_POST['school_nsm'];
        $school_phone = $_POST['school_phone'];
        $province_id = $_POST['province_id'];
        $regency_id = $_POST['regency_id'];
        $district_id = $_POST['district_id'];
        $village_id = $_POST['village_id'];
        $school_address = $_POST['school_address'];
        $school_active = isset($_POST['school_active']) ? 1 : 0;
        try {
            error_reporting(E_ALL);
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

            if ($koneksi->connect_error) {
                throw new Exception("Failed to connect to database: " . $koneksi->connect_error);
            }

            $checkExistSchool = "SELECT * FROM schools WHERE school_npsn = ? AND school_id != ?";
            $stmt = $koneksi->prepare($checkExistSchool);
            $stmt->bind_param("si", $school_npsn, $school_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                echo json_encode([
                    'status' => 400,
                    'label' => "error",
                    'message' => 'School NPSN already exists'
                ]);
                exit;
            }
            

            $sql = "UPDATE schools SET school_name=?, school_status=?, jenjang_id=?, school_npsn=?, school_nsm=?, school_phone=?, province_id=?, regency_id=?, district_id=?, village_id=?, school_address=?, school_active=? WHERE school_id=?";
            $stmt = $koneksi->prepare($sql);
            if (!$stmt) {
                throw new Exception("Query preparation error: " . $koneksi->error);
            }
            $stmt->bind_param("ssssssssssssi", $school_name, $school_status, $jenjang_id, $school_npsn, $school_nsm, $school_phone, $province_id, $regency_id, $district_id, $village_id, $school_address, $school_active, $school_id);

            if ($stmt->execute()) {
                echo json_encode([
                    'status' => 200,
                    'label' => "success",
                    'message' => 'School updated successfully'
                ]);
            } else {
                throw new Exception("Failed to update school: " . $stmt->error);
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
        $school_id = $_POST['school_id'];
        $query = "DELETE FROM schools WHERE school_id = ?";
        $stmt = $koneksi->prepare($query);
        $stmt->bind_param('s', $school_id);
        if ($stmt->execute()) {
            echo json_encode(['status' => 200, 'message' => 'Data provinsi berhasil dihapus']);
        } else {
            echo json_encode(['status' => 500, 'message' => 'Gagal menghapus data provinsi']);
        }
    } else {
        echo json_encode(['status' => 400, 'message' => 'Permintaan tidak valid']);
    }
}
