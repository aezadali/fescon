<?php
header('Content-Type: application/json; charset=UTF-8');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$lang = $_POST['lang'] ?? $_SESSION['lang'] ?? 'en';
$isArabic = ($lang === 'ar');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($message)) {
        echo json_encode([
            'status' => 'error',
            'message' => $isArabic
                ? 'يرجى ملء جميع الحقول الإلزامية (الاسم، البريد الإلكتروني، والرسالة).'
                : 'Please fill in all required fields (Name, Email, and Message).'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            'status' => 'error',
            'message' => $isArabic
                ? 'يرجى إدخال عنوان بريد إلكتروني صحيح.'
                : 'Please provide a valid email address.'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    echo json_encode([
        'status' => 'success',
        'message' => $isArabic
            ? 'شكراً لك ' . htmlspecialchars($name) . '! تم استلام استفسارك الهندسي بنجاح. سيتواصل معك فريقنا الهندسي بمسقط قريباً.'
            : 'Thank you ' . htmlspecialchars($name) . '! Your project inquiry has been logged successfully. Our Muscat engineering team will contact you shortly.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

echo json_encode([
    'status' => 'error',
    'message' => $isArabic ? 'طلب غير صالح.' : 'Invalid request method.'
], JSON_UNESCAPED_UNICODE);
