<?php
include 'config.php';
include 'jwt.php';

$data = json_decode(file_get_contents("php://input"));
$email = $data->email;
$password = $data->password;

$result = $conn->query("SELECT * FROM users WHERE email='$email'");
$user = $result->fetch_assoc();

if ($user && password_verify($password, $user['password'])) {
    $token = generateJWT($user['id']);
    echo json_encode(["status" => "success", "token" => $token, "user" => $user]);
} else {
    echo json_encode(["status" => "error", "msg" => "Invalid credentials"]);
}
?>
