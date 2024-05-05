<?php
require("../../config/database.php");
require("../../config/functions.php");
require("../../config/crud_functions.php");
header('Content-Type: application/json');
if ($pg == 'getProvince') {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['province'])) {
        $province_id = $_POST['province'];
        if ($province_id === "ok") {
            $query = "SELECT * FROM provinces";
            $stmt = $koneksi->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();
            $districts = array();
            while ($row = $result->fetch_assoc()) {
                $districts[] = $row;
            }
            echo json_encode($districts);
        } else {
            echo json_encode([]);
        }
    }
}
if ($pg == 'getRegency') {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['province_id'])) {
        $province_id = $_POST['province_id'];
        $query = "SELECT * FROM regencies WHERE province_id = ?";
        $stmt = $koneksi->prepare($query);
        $stmt->bind_param("i", $province_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $regencies = array();
        while ($row = $result->fetch_assoc()) {
            $regencies[] = $row;
        }
        echo json_encode($regencies);
    } else {
        echo json_encode([]);
    }
}
if ($pg == 'getDistrict') {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['regency_id'])) {
        $regency_id = $_POST['regency_id'];
        $query = "SELECT * FROM districts WHERE regency_id = ?";
        $stmt = $koneksi->prepare($query);
        $stmt->bind_param("i", $regency_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $districts = array();
        while ($row = $result->fetch_assoc()) {
            $districts[] = $row;
        }
        echo json_encode($districts);
    } else {
        echo json_encode([]);
    }
}
if ($pg == 'getVillage') {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['district_id'])) {
        $district_id = $_POST['district_id'];
        $query = "SELECT * FROM villages WHERE district_id = ?";
        $stmt = $koneksi->prepare($query);
        $stmt->bind_param("i", $district_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $villages = array();
        while ($row = $result->fetch_assoc()) {
            $villages[] = $row;
        }
        echo json_encode($villages);
    } else {
        echo json_encode([]);
    }
}
if ($pg == 'getSchools') {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['district_id'])) {
        $district_id = $_POST['district_id'];
        $query = "SELECT * FROM schools WHERE district_id = ?";
        $stmt = $koneksi->prepare($query);
        $stmt->bind_param("i", $district_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $schools = array();
        while ($row = $result->fetch_assoc()) {
            $schools[] = $row;
        }
        echo json_encode($schools);
    } else {
        echo json_encode([]);
    }
}
