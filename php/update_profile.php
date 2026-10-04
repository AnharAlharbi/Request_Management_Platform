<?php
ob_clean();
header("Content-Type: application/json");
session_start();

require_once "db.php"; // ملف الاتصال (PDO)

// التحقق من تسجيل الدخول
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo json_encode(["success" => false, "message" => "User not logged in"]);
    exit;
}

// استقبال البيانات من fetch
$data = json_decode(file_get_contents("php://input"), true);

$name     = trim($data["name"]);
$email    = trim($data["email"]);
$office   = trim($data["office"]);
$password = trim($data["password"]);

$user_id = $_SESSION["id"];

try {
    // إذا تم تغيير كلمة المرور
    if ($password !== "******" && !empty($password)) {
        $sql = "UPDATE users 
                SET name = :name, email = :email, office_number = :office, password = :pass
                WHERE id = :id";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":office", $office);
        $stmt->bindParam(":pass", $password); // بدون تشفير
        $stmt->bindParam(":id", $user_id);
        
        $_SESSION['password'] = $password; // تحديث كلمة المرور في SESSION
    } else {
        // بدون تحديث كلمة المرور
        $sql = "UPDATE users 
                SET name = :name, email = :email, office_number = :office
                WHERE id = :id";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":office", $office);
        $stmt->bindParam(":id", $user_id);
    }

    $stmt->execute();

    // تحديث بيانات الـ SESSION
    $_SESSION["name"] = $name;
    $_SESSION["email"] = $email;
    $_SESSION["office_number"] = $office;
    $_SESSION['password'] = $password;


    // جلب البيانات لإرسالها للواجهة
    $stmt = $pdo->prepare("SELECT id, name, email, office_number, department FROM users WHERE id = :id");
    $stmt->bindParam(":id", $user_id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // إضافة كلمة المرور إلى JSON
    $user['password'] = $_SESSION['password'];

    echo json_encode(["success" => true, "user" => $user]);

} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
