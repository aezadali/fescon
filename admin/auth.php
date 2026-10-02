<?php
/**
 * Fescon Admin Authentication & Data Store Helper
 */

require_once __DIR__ . '/../config/security.php';
startSecureSession();

// UTF-8 settings
ini_set('default_charset', 'UTF-8');
if (function_exists('mb_internal_encoding')) {
    mb_internal_encoding('UTF-8');
}

define('ADMIN_SESSION_TIMEOUT', 1800);
define('ADMIN_LOGIN_MAX_ATTEMPTS', 5);
define('ADMIN_LOGIN_WINDOW', 900);

define('PROJECTS_JSON_PATH', dirname(__DIR__) . '/config/projects.json');
define('CATEGORIES_JSON_PATH', dirname(__DIR__) . '/config/categories.json');
define('IMAGES_UPLOAD_DIR', dirname(__DIR__) . '/assets/images/');

/**
 * Check if admin is authenticated
 */
function isAdminLoggedIn(): bool {
    if (empty($_SESSION['fescon_admin_logged_in']) || $_SESSION['fescon_admin_logged_in'] !== true) {
        return false;
    }

    $lastActivity = (int)($_SESSION['fescon_admin_last_activity'] ?? 0);
    if ($lastActivity === 0 || (time() - $lastActivity) > ADMIN_SESSION_TIMEOUT) {
        $_SESSION = [];
        return false;
    }

    $_SESSION['fescon_admin_last_activity'] = time();
    return true;
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
 * Validate administrator credentials supplied outside the web root.
 */
function verifyAdminCredentials(string $username, string $password): bool {
    $configuredUsername = getAppConfig('ADMIN_USERNAME');
    $configuredPasswordHash = getAppConfig('ADMIN_PASSWORD_HASH');

    return $configuredUsername !== null
        && $configuredPasswordHash !== null
        && hash_equals($configuredUsername, trim($username))
        && password_verify($password, $configuredPasswordHash);
}

function getClientRateLimitKey(): string {
    return hash('sha256', $_SERVER['REMOTE_ADDR'] ?? 'unknown');
}

function withAdminLoginAttempts(callable $callback) {
    $rateLimitFile = dirname(__DIR__) . '/config/admin-login-rate-limit.json';
    $handle = @fopen($rateLimitFile, 'c+');
    if ($handle === false || !flock($handle, LOCK_EX)) {
        if (is_resource($handle)) {
            fclose($handle);
        }
        return null;
    }

    $rawData = stream_get_contents($handle);
    $attempts = json_decode($rawData ?: '[]', true);
    $attempts = is_array($attempts) ? $attempts : [];
    $cutoff = time() - ADMIN_LOGIN_WINDOW;

    foreach ($attempts as $key => $timestamps) {
        $recent = array_values(array_filter((array)$timestamps, static function ($timestamp) use ($cutoff): bool {
            return is_int($timestamp) && $timestamp >= $cutoff;
        }));
        if ($recent) {
            $attempts[$key] = $recent;
        } else {
            unset($attempts[$key]);
        }
    }

    $result = $callback($attempts, getClientRateLimitKey());
    rewind($handle);
    ftruncate($handle, 0);
    fwrite($handle, json_encode($attempts, JSON_UNESCAPED_SLASHES));
    fflush($handle);
    flock($handle, LOCK_UN);
    fclose($handle);

    return $result;
}

function isAdminLoginRateLimited(): bool {
    $result = withAdminLoginAttempts(static function (array &$attempts, string $clientKey): bool {
        return count($attempts[$clientKey] ?? []) >= ADMIN_LOGIN_MAX_ATTEMPTS;
    });

    return $result === true;
}

function recordAdminLoginFailure(): void {
    withAdminLoginAttempts(static function (array &$attempts, string $clientKey): void {
        $attempts[$clientKey][] = time();
    });
}

function clearAdminLoginFailures(): void {
    withAdminLoginAttempts(static function (array &$attempts, string $clientKey): void {
        unset($attempts[$clientKey]);
    });
}

function normalizeCategoryColor(string $color): string {
    $color = trim($color);
    return preg_match('/^#[a-fA-F0-9]{6}$/', $color) ? $color : '#3b82f6';
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
 * Default categories
 */
function getDefaultCategories(): array {
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
            'ar' => 'مشاريع تسليم المفتاح',
            'color' => '#059669'
        ],
        'om' => [
            'en' => 'Operation & Maintenance',
            'ar' => 'التشغيل والصيانة',
            'color' => '#4f46e5'
        ],
        'lagoon' => [
            'en' => 'Lagoon & Waterfront',
            'ar' => 'البحيرات والواجهات البحرية',
            'color' => '#0284c7'
        ]
    ];
}

/**
 * Load categories from JSON file, initialize with defaults if missing
 */
function loadCategories(): array {
    $filePath = CATEGORIES_JSON_PATH;
    if (file_exists($filePath)) {
        $raw = file_get_contents($filePath);
        $data = json_decode($raw, true);
        if (is_array($data) && !empty($data)) {
            return $data;
        }
    }
    $defaults = getDefaultCategories();
    saveCategories($defaults);
    return $defaults;
}

/**
 * Save categories to JSON file with backup
 */
function saveCategories(array $categories): bool {
    $filePath = CATEGORIES_JSON_PATH;
    $dir = dirname($filePath);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    if (file_exists($filePath)) {
        @copy($filePath, $filePath . '.bak');
    }
    $json = json_encode($categories, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    return (file_put_contents($filePath, $json, LOCK_EX) !== false);
}

/**
 * Alias for loadCategories
 */
function getStandardCategories(): array {
    return loadCategories();
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
