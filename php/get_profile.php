<?php
require_once 'db.php';
session_start();
header('Content-Type: application/json');

// التحقق من تسجيل الدخول
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo json_encode(["success" => false, "message" => "User not logged in."]);
    exit;
}

// كلمة المرور آمنة حتى لو SESSION فارغ
$password = $_SESSION['password'] ?? "";

echo json_encode([
    "success" => true,
    "user" => [
        "id" => $_SESSION['id'],
        "name" => $_SESSION['name'],
        "email" => $_SESSION['email'],
        "password" => $password,
        "department" => $_SESSION['department'],
        "office_number" => $_SESSION['office_number']
    ]
]);
?>
