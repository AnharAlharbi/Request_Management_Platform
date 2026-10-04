<?php
// services.php - معالجة طلبات الخدمات
// تعيين رأس الاستجابة لـ JSON
header('Content-Type: application/json');
// تضمين ملف الاتصال بقاعدة البيانات
require_once 'db.php';
// بدء جلسة العمل (Session)
session_start();


// يجب أن يكون الطلب من نوع POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405); // Method Not Allowed
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
    exit;
}


// التحقق مما إذا كان المستخدم مسجلاً للدخول
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // إذا لم يكن مسجلاً للدخول، يمكننا السماح بتقديم الطلب ولكن يجب أن نطلب بياناته
    // أو يمكننا إيقاف العملية وطلب تسجيل الدخول أولاً. سنفترض أنه يجب أن يكون مسجلاً للدخول.
    // لتسهيل الأمر، سنستخدم user_id افتراضي إذا لم يكن مسجلاً للدخول، ولكن يفضل فرض تسجيل الدخول.
    $user_id = $_SESSION['id'] ?? 0; // 0 يعني مستخدم غير مسجل (يجب تعديل هذا لاحقًا)
} else {
    $user_id = $_SESSION['id'];
}


// استلام البيانات من نموذج طلب الخدمة
$service_type = trim($_POST['service_type'] ?? ''); // نوع الخدمة (من data-service)
$requester_name = trim($_POST['name'] ?? '');
$office_number = trim($_POST['office'] ?? '');
$request_details = trim($_POST['options'] ?? ''); // تفاصيل الطلب (من حقل options)

// التحقق من صحة البيانات
if (empty($service_type) || empty($requester_name) || empty($office_number) || empty($request_details)) {
    echo json_encode(["success" => false, "message" => "Please fill all required fields for the service request."]);
    exit;
}

// إعداد استعلام الإدخال
$sql = "INSERT INTO service_requests (user_id, service_type, requester_name, office_number, request_details) VALUES (:user_id, :service_type, :requester_name, :office_number, :request_details)";

try {
    $stmt = $pdo->prepare($sql);

    // ربط المتغيرات
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':service_type', $service_type);
    $stmt->bindParam(':requester_name', $requester_name);
    $stmt->bindParam(':office_number', $office_number);
    $stmt->bindParam(':request_details', $request_details);

    // تنفيذ الاستعلام
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Service request submitted successfully!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Something went wrong. Please try again later."]);
    }

} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
}
?>
