<?php
/**
 * Security and private-configuration helpers.
 *
 * Production secrets must be supplied through environment variables or a
 * fescon-private-config.php file one directory above the web root.
 */

function requestUsesHttps(): bool {
    return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
}

function startSecureSession(): void {
    if (session_status() !== PHP_SESSION_NONE) {
        return;
    }

    ini_set('session.use_strict_mode', '1');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => requestUsesHttps(),
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

function getPrivateConfig(): array {
    static $config;

    if ($config !== null) {
        return $config;
    }

    $config = [];
    $privateConfigPath = dirname(__DIR__, 2) . '/fescon-private-config.php';
    if (is_readable($privateConfigPath)) {
        $loadedConfig = require $privateConfigPath;
        if (is_array($loadedConfig)) {
            $config = $loadedConfig;
        }
    }

    return $config;
}

function getAppConfig(string $key): ?string {
    $envKey = 'FESCON_' . strtoupper($key);
    $environmentValue = getenv($envKey);
    if (is_string($environmentValue) && $environmentValue !== '') {
        return $environmentValue;
    }

    $privateConfig = getPrivateConfig();
    $fileValue = $privateConfig[strtolower($key)] ?? null;
    return is_string($fileValue) && $fileValue !== '' ? $fileValue : null;
}
