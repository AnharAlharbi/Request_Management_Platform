<?php
// db.php - ملف الاتصال بقاعدة البيانات

// إعدادات الاتصال (يجب تغييرها لتناسب بيئة الاستضافة الخاصة بك)
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root'); // اسم المستخدم لقاعدة البيانات
define('DB_PASSWORD', ''); // كلمة المرور لقاعدة البيانات
define('DB_NAME', 'municipality_services'); // اسم قاعدة البيانات

// محاولة الاتصال بقاعدة البيانات
try {
    $pdo = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
    // تعيين وضع الخطأ في PDO إلى استثناء
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // تعيين ترميز الأحرف إلى UTF8
    $pdo->exec("set names utf8");
} catch (PDOException $e) {
    // في حالة فشل الاتصال، أوقف التنفيذ وأظهر رسالة خطأ
    die("ERROR: Could not connect. " . $e->getMessage());
}
?>
