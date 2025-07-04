<?php
include 'config.php';

$data = json_decode(file_get_contents("php://input"));
$name = $data->name;
$email = $data->email;
$password = password_hash($data->password, PASSWORD_BCRYPT);

$stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $email, $password);
if ($stmt->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "msg" => $stmt->error]);
}
?>
