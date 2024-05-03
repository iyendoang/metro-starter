<?php
session_start();
require "../database.php";
header('Content-Type: application/json');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);
    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username' AND status='1'");
    $ceklogin = mysqli_num_rows($query);
    if ($ceklogin == 1) {
        $user = mysqli_fetch_array($query);
        if (!password_verify($password, $user['password'])) {
            echo json_encode([
                "success" => false,
                "message" => "Invalid username or password"
            ]);
        } else {
            $_SESSION["id"] = $user['id'];
            $_SESSION["username"] = $user['username'];
            $_SESSION["role"] = $user['role'];
            echo json_encode([
                "success" => true,
                "message" => "Login successful",
                "route" => "admin",
                "data" => $user
            ]);
        }
    } else {
        echo json_encode([
            "success" => false,
            "message" => "User not found"
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "Invalid request method"
    ]);
}
?>
