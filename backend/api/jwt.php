<?php
require_once __DIR__ . '/../vendor/autoload.php';

use \Firebase\JWT\JWT;

$secret_key = "f7a342ce4d508bc176928ad1f3d4c6ebc99ba772e29aa34db621bc0f93db2b25";

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
