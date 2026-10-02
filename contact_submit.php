<?php
require_once __DIR__ . '/config/security.php';
startSecureSession();

header('Content-Type: application/json; charset=UTF-8');

function contactResponse(string $status, string $message, int $statusCode = 200): void {
    http_response_code($statusCode);
    echo json_encode(['status' => $status, 'message' => $message], JSON_UNESCAPED_UNICODE);
    exit;
}

function contactRateLimitExceeded(string $ipAddress): bool {
    $rateLimitFile = __DIR__ . '/config/contact-rate-limit.json';
    $handle = @fopen($rateLimitFile, 'c+');
    if ($handle === false) {
        return false;
    }

    if (!flock($handle, LOCK_EX)) {
        fclose($handle);
        return false;
    }

    $rawData = stream_get_contents($handle);
    $entries = json_decode($rawData ?: '[]', true);
    $entries = is_array($entries) ? $entries : [];
    $now = time();
    $cutoff = $now - 900;
    $clientKey = hash('sha256', $ipAddress);

    foreach ($entries as $key => $timestamps) {
        $recent = array_values(array_filter((array)$timestamps, static function ($timestamp) use ($cutoff): bool {
            return is_int($timestamp) && $timestamp >= $cutoff;
        }));
        if ($recent) {
            $entries[$key] = $recent;
        } else {
            unset($entries[$key]);
        }
    }

    $tooManyRequests = count($entries[$clientKey] ?? []) >= 3;
    if (!$tooManyRequests) {
        $entries[$clientKey][] = $now;
        rewind($handle);
        ftruncate($handle, 0);
        fwrite($handle, json_encode($entries, JSON_UNESCAPED_SLASHES));
    }

    fflush($handle);
    flock($handle, LOCK_UN);
    fclose($handle);

    return $tooManyRequests;
}

function contactTextLength(string $value): int {
    return function_exists('mb_strlen') ? mb_strlen($value) : strlen($value);
}

$lang = ($_POST['lang'] ?? 'en') === 'ar' ? 'ar' : 'en';
$isArabic = $lang === 'ar';
$messages = [
    'invalid_request' => $isArabic ? 'طلب غير صالح.' : 'Invalid request.',
    'session_expired' => $isArabic ? 'انتهت صلاحية الجلسة. يرجى تحديث الصفحة والمحاولة مرة أخرى.' : 'Your session has expired. Please refresh the page and try again.',
    'required' => $isArabic ? 'يرجى ملء جميع الحقول الإلزامية.' : 'Please fill in all required fields.',
    'invalid_email' => $isArabic ? 'يرجى إدخال عنوان بريد إلكتروني صحيح.' : 'Please provide a valid email address.',
    'too_long' => $isArabic ? 'أحد الحقول يتجاوز الحد المسموح به. يرجى اختصار رسالتك.' : 'One or more fields exceed the allowed length. Please shorten your message.',
    'consent' => $isArabic ? 'يرجى الموافقة على إشعار الخصوصية قبل الإرسال.' : 'Please agree to the privacy notice before submitting.',
    'rate_limit' => $isArabic ? 'تم إرسال عدد كبير من الطلبات. يرجى المحاولة مرة أخرى بعد 15 دقيقة.' : 'Too many requests were sent. Please try again in 15 minutes.',
    'unavailable' => $isArabic ? 'تعذر إرسال الاستفسار حالياً. يرجى الاتصال بنا مباشرة.' : 'We could not send your inquiry at this time. Please contact us directly.',
    'success' => $isArabic ? 'شكراً لك! تم إرسال استفسارك بنجاح وسيتواصل معك فريقنا قريباً.' : 'Thank you! Your inquiry has been sent successfully. Our team will contact you shortly.',
];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    contactResponse('error', $messages['invalid_request'], 405);
}

$csrfToken = $_POST['csrf_token'] ?? '';
if (empty($_SESSION['fescon_contact_csrf_token']) || !is_string($csrfToken) || !hash_equals($_SESSION['fescon_contact_csrf_token'], $csrfToken)) {
    contactResponse('error', $messages['session_expired'], 419);
}

// Honeypot field: real visitors never see or complete this input.
if (!empty(trim((string)($_POST['website'] ?? '')))) {
    contactResponse('success', $messages['success']);
}

$name = trim((string)($_POST['name'] ?? ''));
$email = trim((string)($_POST['email'] ?? ''));
$phone = trim((string)($_POST['phone'] ?? ''));
$subject = trim((string)($_POST['subject'] ?? ''));
$message = trim((string)($_POST['message'] ?? ''));

if ($name === '' || $email === '' || $message === '') {
    contactResponse('error', $messages['required'], 422);
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    contactResponse('error', $messages['invalid_email'], 422);
}

if (contactTextLength($name) > 120 || contactTextLength($email) > 254 || contactTextLength($phone) > 40 || contactTextLength($subject) > 180 || contactTextLength($message) > 5000) {
    contactResponse('error', $messages['too_long'], 422);
}

if (($_POST['privacy_consent'] ?? '') !== '1') {
    contactResponse('error', $messages['consent'], 422);
}

if (contactRateLimitExceeded($_SERVER['REMOTE_ADDR'] ?? 'unknown')) {
    contactResponse('error', $messages['rate_limit'], 429);
}

$recipient = getAppConfig('CONTACT_RECIPIENT');
$from = getAppConfig('CONTACT_FROM');
if ($recipient === null || $from === null || !filter_var($recipient, FILTER_VALIDATE_EMAIL) || !filter_var($from, FILTER_VALIDATE_EMAIL)) {
    error_log('Fescon contact form is missing valid mail configuration.');
    contactResponse('error', $messages['unavailable'], 503);
}

$mailSubject = 'New Fescon website inquiry';
$mailBody = "Name: {$name}\n"
    . "Email: {$email}\n"
    . "Phone: {$phone}\n"
    . "Subject: {$subject}\n"
    . "Language: {$lang}\n\n"
    . "Message:\n{$message}\n";
$headers = [
    'MIME-Version: 1.0',
    'Content-Type: text/plain; charset=UTF-8',
    "From: Fescon Website <{$from}>",
    "Reply-To: {$email}",
];

if (!@mail($recipient, $mailSubject, $mailBody, implode("\r\n", $headers))) {
    error_log('Fescon contact form email delivery failed.');
    contactResponse('error', $messages['unavailable'], 503);
}

contactResponse('success', $messages['success']);
