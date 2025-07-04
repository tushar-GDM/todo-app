<?php
include 'jwt.php';
$headers = getallheaders();
$token = $headers['Authorization'] ?? '';
$userId = verifyJWT(str_replace("Bearer ", "", $token));

if (!$userId) {
    http_response_code(401);
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}
?>
