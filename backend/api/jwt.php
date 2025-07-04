<?php
use \Firebase\JWT\JWT;

$secret_key = "YOUR_SECRET_KEY";

function generateJWT($userId) {
    global $secret_key;
    $payload = [
        "iss" => "localhost",
        "iat" => time(),
        "exp" => time() + (3600 * 24),
        "sub" => $userId
    ];
    return JWT::encode($payload, $secret_key, 'HS256');
}

function verifyJWT($token) {
    global $secret_key;
    try {
        $decoded = JWT::decode($token, $secret_key, ['HS256']);
        return $decoded->sub;
    } catch (Exception $e) {
        return false;
    }
}
?>
