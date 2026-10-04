<?php
// signup.php - معالجة إنشاء حساب جديد
// تعيين رأس الاستجابة لـ JSON
header('Content-Type: application/json');
// تضمين ملف الاتصال بقاعدة البيانات
require_once 'db.php';

// يجب أن يكون الطلب من نوع POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405); // Method Not Allowed
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
    exit;
}

// استلام البيانات من نموذج التسجيل
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';  // هنا لم يتم تشفير كلمة المرور
$department = trim($_POST['department'] ?? '');
$office_number = trim($_POST['office_number'] ?? '');

// التحقق من صحة البيانات
if (empty($name) || empty($email) || empty($password) || empty($department) || empty($office_number)) {
    echo json_encode(["success" => false, "message" => "Please fill all required fields."]);
    exit;
}

// إعداد استعلام الإدخال
$sql = "INSERT INTO users (name, email, password, department, office_number) VALUES (:name, :email, :password, :department, :office_number)";

try {
    $stmt = $pdo->prepare($sql);

    // ربط المتغيرات
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);  // هنا يتم تخزين كلمة المرور كما هي
    $stmt->bindParam(':department', $department);
    $stmt->bindParam(':office_number', $office_number);

    // تنفيذ الاستعلام
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Account created successfully!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Something went wrong. Please try again later."]);
    }

} catch (PDOException $e) {
    // التحقق من خطأ تكرار البريد الإلكتروني (عادةً ما يكون رمز الخطأ 23000)
    if ($e->getCode() == 23000) {
        echo json_encode(["success" => false, "message" => "This email is already registered."]);
    } else {
        // رسالة خطأ عامة في حالة وجود مشكلة أخرى
        // يمكنك إظهار $e->getMessage() للتصحيح، ولكن يفضل إخفاؤها في بيئة الإنتاج
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    }
}
?>
