<?php
/**
 * Fescon Admin Authentication & Data Store Helper
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Configuration
define('ADMIN_USER', 'admin');
// Default password: fescon@2026 (also accepts admin123 as fallback)
define('ADMIN_PASS_PRIMARY', 'fescon@2026');
define('ADMIN_PASS_SECONDARY', 'admin123');

define('PROJECTS_JSON_PATH', dirname(__DIR__) . '/config/projects.json');
define('IMAGES_UPLOAD_DIR', dirname(__DIR__) . '/assets/images/');

/**
 * Check if admin is authenticated
 */
function isAdminLoggedIn(): bool {
    return !empty($_SESSION['fescon_admin_logged_in']) && $_SESSION['fescon_admin_logged_in'] === true;
}

/**
 * Enforce admin login, redirect if not authenticated
 */
function requireAdminLogin(): void {
    if (!isAdminLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

/**
 * Validate admin credentials
 */
function verifyAdminCredentials(string $username, string $password): bool {
    $userMatch = (trim($username) === ADMIN_USER);
    $passMatch = ($password === ADMIN_PASS_PRIMARY || $password === ADMIN_PASS_SECONDARY);
    return $userMatch && $passMatch;
}

/**
 * CSRF token generation & validation
 */
function getCsrfToken(): string {
    if (empty($_SESSION['fescon_csrf_token'])) {
        $_SESSION['fescon_csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['fescon_csrf_token'];
}

function verifyCsrfToken(?string $token): bool {
    if (empty($token) || empty($_SESSION['fescon_csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['fescon_csrf_token'], $token);
}

/**
 * Load all projects from JSON file
 */
function loadProjects(): array {
    $filePath = PROJECTS_JSON_PATH;
    if (!file_exists($filePath)) {
        return [];
    }
    $raw = file_get_contents($filePath);
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

/**
 * Save all projects to JSON file with automatic backup
 */
function saveProjects(array $projects): bool {
    $filePath = PROJECTS_JSON_PATH;
    $dir = dirname($filePath);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    
    // Create backup if existing
    if (file_exists($filePath)) {
        @copy($filePath, $filePath . '.bak');
    }
    
    $json = json_encode(array_values($projects), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    return (file_put_contents($filePath, $json, LOCK_EX) !== false);
}

/**
 * Get standard categories list with display labels
 */
function getStandardCategories(): array {
    return [
        'grid' => [
            'en' => 'Grid Stations',
            'ar' => 'محطات المحولات',
            'color' => '#1e40af'
        ],
        'dist' => [
            'en' => 'Distribution Networks',
            'ar' => 'شبكات التوزيع',
            'color' => '#7c3aed'
        ],
        'lighting' => [
            'en' => 'Street Lighting',
            'ar' => 'إنارة الشوارع',
            'color' => '#d97706'
        ],
        'civil' => [
            'en' => 'Civil Infrastructure',
            'ar' => 'البنية التحتية المدنية',
            'color' => '#ea580c'
        ],
        'mep' => [
            'en' => 'MEP Works',
            'ar' => 'الأعمال الكهروميكانيكية',
            'color' => '#0d9488'
        ],
        'oilgas' => [
            'en' => 'Oil & Gas',
            'ar' => 'النفط والغاز',
            'color' => '#dc2626'
        ],
        'epc' => [
            'en' => 'Turnkey EPC',
            'ar' => 'مشاريع تسليم المفتاح EPC',
            'color' => '#059669'
        ],
        'om' => [
            'en' => 'Operation & Maintenance',
            'ar' => 'التشغيل والصيانة',
            'color' => '#4f46e5'
        ]
    ];
}
