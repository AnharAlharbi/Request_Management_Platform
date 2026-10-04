<?php
// login.php - تسجيل الدخول بدون تشفير
header('Content-Type: application/json');
require_once 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
    exit;
}

$email = trim($_POST['loginEmail'] ?? '');
$password = $_POST['loginPass'] ?? '';

if (empty($email) || empty($password)) {
    echo json_encode(["success" => false, "message" => "Please enter email and password."]);
    exit;
}

// البحث عن المستخدم حسب البريد الإلكتروني وكلمة المرور مباشرة
$sql = "SELECT id, name, email, password, department, office_number 
        FROM users 
        WHERE email = :email AND password = :password";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password); 
    $stmt->execute();

    if ($stmt->rowCount() == 1) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // إعداد الجلسة
        $_SESSION['loggedin'] = true;
        $_SESSION['id'] = $user['id'];
        $_SESSION['name'] = $user['name'];  
        $_SESSION['email'] = $user['email'];
        $_SESSION['department'] = $user['department'];
        $_SESSION['office_number'] = $user['office_number'];
        $_SESSION['password'] = $user['password']; // حفظ كلمة المرور في الجلسة

        echo json_encode(["success" => true, "message" => "Login successful!", "redirect" => "services.html"]);
    } else {
        echo json_encode(["success" => false, "message" => "Invalid email or password."]);
    }

} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
}
?>
