<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sidoel_api";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Query to insert 300 users
for ($i = 1; $i <= 300; $i++) {
  $username = "client" . $i;
  $fullname = "Client " . $i;
  $phone = "08912345678";
  $role = "member";
  $status = "1";
  $password = "123";
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  $sql = "INSERT INTO `users` (`id`, `username`, `fullname`, `phone`, `role`, `status`, `password`) VALUES (NULL, '$username', '$fullname', '$phone', '$role', '$status', '$hashed_password')";

  if ($conn->query($sql) === TRUE) {
    echo "User $username created successfully<br>";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
}

$conn->close();
?>
