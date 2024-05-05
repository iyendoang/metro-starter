<?php
session_start();
require "../database.php";
header('Content-Type: application/json');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $gender = $_POST['gender'];
    $phone = $_POST['phone'];
    $school_npsn = $_POST['school_npsn'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];
    $role = 'member';
    $status = 3;
    $userId = $_SESSION['id'] ?? '';
    $updatedAt = date('Y-m-d H:i:s');

    // Cek apakah school_npsn sudah ada di database
    $stmt = $koneksi->prepare("SELECT * FROM users WHERE school_npsn = ?");
    $stmt->bind_param("s", $school_npsn);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo json_encode([
            "success" => false,
            "message" => "School NPSN already exists"
        ]);
        exit;
    }
    // Cek apakah username sudah ada di database
    $stmt = $koneksi->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo json_encode([
            "success" => false,
            "message" => "Username already exists"
        ]);
        exit;
    }

    if ($password != $confirmPassword) {
        echo json_encode([
            "success" => false,
            "message" => "Password and confirm password do not match"
        ]);
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $koneksi->prepare("INSERT INTO users (fullname, username, gender, phone, school_npsn, password, role, status, create_by, update_by, update_at, create_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssssss", $fullname, $username, $gender, $phone, $school_npsn, $hashedPassword, $role, $status, $userId, $userId, $updatedAt, $updatedAt);
    $stmt->execute();
    $stmt->close();
    echo json_encode([
        "success" => true,
        "message" => "User registered successfully"
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Invalid request method"
    ]);
}
