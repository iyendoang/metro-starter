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
    $query = "SELECT * FROM lembagas ";
    $results = $koneksi->prepare($query);
    $results->execute();
    $result = $results->get_result();
    $data = array();
    $i = 0;
    while ($row = $result->fetch_assoc()) {
        $data[$i]['lembaga_npsn'] = $row['lembaga_npsn'];
        $data[$i]['lembaga_nsm'] = $row['lembaga_nsm'];
        $data[$i]['lembaga_name'] = $row['lembaga_name'];
        $data[$i]['jenjang_id'] = $row['jenjang_id'];
        $data[$i]['lembaga_status'] = $row['lembaga_status'];
        $data[$i]['lembaga_prov'] = $row['lembaga_prov'];
        $data[$i]['lembaga_kota'] = $row['lembaga_kota'];
        $data[$i]['lembaga_kec'] = $row['lembaga_kec'];
        $data[$i]['lembaga_kel'] = $row['lembaga_kel'];
        $data[$i]['lembaga_alamat'] = $row['lembaga_alamat'];
        $i++;
    }
    $response = array('data' => $data);
    echo json_encode($response);
    $koneksi->close();
}
if ($pg == 'store') {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $fullname = $_POST['fullname'];
        $lembaganame = $_POST['lembaganame'];
        $phone = $_POST['phone'];
        $role = $_POST['role'];
        $gender = $_POST['gender'];
        $update_at = date('Y-m-d');
        $create_by = $_SESSION['id'];
        $update_by = $_SESSION['id'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $status = isset($_POST['status']) ? 1 : 0;

        // Pastikan variabel $koneksi berisi objek koneksi yang valid
        if (!$koneksi) {
            echo json_encode([
                'status' => 500,
                'label' => "error",
                'message' => 'Failed to connect to database'
            ]);
            exit;
        }

        $sql = "INSERT INTO lembagas (fullname, gender, lembaganame, phone, role, update_at, password, status, create_by, update_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $koneksi->prepare($sql);

        if (!$stmt) {
            echo json_encode([
                'status' => 500,
                'label' => "error",
                'message' => 'Query preparation error',
                'error' => $koneksi->error
            ]);
            exit;
        }

        $stmt->bind_param("sssssssiii", $fullname, $gender, $lembaganame, $phone, $role, $update_at, $password, $status, $create_by, $update_by);

        if ($stmt->execute()) {
            echo json_encode([
                'status' => 200,
                'label' => "success",
                'message' => 'User added successfully'
            ]);
        } else {
            echo json_encode([
                'status' => 500,
                'label' => "error",
                'message' => 'Failed to add lembaga',
                'error' => $stmt->error
            ]);
        }

        $stmt->close();
        $koneksi->close();
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
        $stmt = $koneksi->prepare('SELECT * FROM lembagas WHERE id = ?');
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
        $fullname = $_POST['fullname'];
        $lembaganame = $_POST['lembaganame'];
        $phone = $_POST['phone'];
        $role = $_POST['role'];
        $gender = $_POST['gender'];
        $update_at = date('Y-m-d');
        $update_by = $_SESSION['id'];
        $status = isset($_POST['status']) ? 1 : 0;
        if (!$koneksi) {
            echo json_encode([
                'status' => 500,
                'label' => "error",
                'message' => 'Failed to connect to database'
            ]);
            exit;
        }
        $password = "";
        if (!empty($_POST['password'])) {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        }
        $sql = "UPDATE lembagas SET fullname=?, gender=?, lembaganame=?, phone=?, role=?, update_at=?, update_by=?";
        $params = "ssssssi";
        $values = [$fullname, $gender, $lembaganame, $phone, $role, $update_at, $update_by];
        if (!empty($password)) {
            $sql .= ", password=?";
            $params .= "s";
            $values[] = $password;
        }
        $sql .= " WHERE id=?";
        $params .= "i";
        $values[] = $id;
        $stmt = $koneksi->prepare($sql);
        if (!$stmt) {
            echo json_encode([
                'status' => 500,
                'label' => "error",
                'message' => 'Query preparation error',
                'error' => $koneksi->error
            ]);
            exit;
        }
        $stmt->bind_param($params, ...$values);
        if ($stmt->execute()) {
            echo json_encode([
                'status' => 200,
                'label' => "success",
                'message' => 'User updated successfully'
            ]);
        } else {
            echo json_encode([
                'status' => 500,
                'label' => "error",
                'message' => 'Failed to update lembaga',
                'error' => $stmt->error
            ]);
        }
        $stmt->close();
        $koneksi->close();
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
        $query = "DELETE FROM lembagas WHERE id = ?";
        $stmt = $koneksi->prepare($query);
        $stmt->bind_param('i', $id); // 'i' menunjukkan bahwa $id adalah integer
        if ($stmt->execute()) {
            echo json_encode(['status' => 200, 'message' => 'Data pengguna berhasil dihapus']);
        } else {
            echo json_encode(['status' => 500, 'message' => 'Gagal menghapus data pengguna']);
        }
    } else {
        echo json_encode(['status' => 400, 'message' => 'Permintaan tidak valid']);
    }
}
if ($pg == 'bulk_delete') {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $ids = $_POST['ids'];
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $query = "DELETE FROM lembagas WHERE id IN ($placeholders)";
        $stmt = $koneksi->prepare($query);
        if (!$stmt) {
            echo json_encode(['status' => 500, 'message' => 'Failed to prepare statement']);
            exit;
        }
        $types = str_repeat('i', count($ids));
        $stmt->bind_param($types, ...$ids);
        if ($stmt->execute()) {
            echo json_encode(['status' => 200, 'message' => 'Selected lembagas deleted successfully']);
        } else {
            echo json_encode(['status' => 500, 'message' => 'Failed to delete selected lembagas']);
        }
    } else {
        echo json_encode(['status' => 400, 'message' => 'Invalid request']);
    }
}
