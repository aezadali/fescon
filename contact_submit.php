<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($message)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Please fill in all required fields (Name, Email, and Message).'
        ]);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Please provide a valid email address.'
        ]);
        exit;
    }

    // In production environment, mail() or SMTP dispatch can be executed here.
    echo json_encode([
        'status' => 'success',
        'message' => 'Thank you ' . htmlspecialchars($name) . '! Your project inquiry has been logged successfully. Our Muscat engineering team will contact you shortly.'
    ]);
    exit;
}

echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
