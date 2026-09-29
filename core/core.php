<?php

if (ob_get_level() === 0) {
    ob_start();
}

if (session_status() !== PHP_SESSION_ACTIVE) {
    $isHttps = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== '' && strtolower($_SERVER['HTTPS']) !== 'off';

    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');
    ini_set('session.cookie_httponly', '1');
    ini_set('session.cookie_samesite', 'Lax');
    ini_set('session.cookie_secure', $isHttps ? '1' : '0');

    session_start();
}

if (!defined('CORE_SESSION_TIMEOUT')) {
    define('CORE_SESSION_TIMEOUT', 1800);
}

function is_logged_in()
{
    return isset($_SESSION['customer_id']) && (int) $_SESSION['customer_id'] > 0;
}

function is_admin()
{
    return is_logged_in() && isset($_SESSION['user_role']) && (int) $_SESSION['user_role'] === 1;
}

function require_admin($redirectUrl = 'index.php')
{
    if (is_admin()) {
        return;
    }

    $_SESSION['error'] = 'You do not have permission to access this page.';
    header('Location: ' . $redirectUrl);
    exit;
}

function core_is_admin()
{
    return is_admin();
}

function core_require_admin($redirectUrl = '../index.php')
{
    require_admin($redirectUrl);
}

function core_get_user_id()
{
    return is_logged_in() ? (int) $_SESSION['customer_id'] : null;
}

function core_get_user_role()
{
    return is_logged_in() ? ($_SESSION['user_role'] ?? null) : null;
}

function core_login($customer)
{
    if (!is_array($customer) || empty($customer['customer_id'])) {
        return false;
    }

    session_regenerate_id(true);
    $_SESSION['customer_id'] = (int) $customer['customer_id'];
    $_SESSION['customer_name'] = $customer['customer_name'] ?? '';
    $_SESSION['customer_email'] = $customer['customer_email'] ?? '';
    $_SESSION['user_role'] = $customer['user_role'] ?? null;
    $_SESSION['auth_ip'] = $_SERVER['REMOTE_ADDR'] ?? '';
    $_SESSION['auth_user_agent'] = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $_SESSION['last_activity'] = time();

    return true;
}

function core_logout()
{
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $cookie = session_get_cookie_params();
        setcookie(session_name(), '', [
            'expires' => time() - 42000,
            'path' => $cookie['path'],
            'domain' => $cookie['domain'],
            'secure' => $cookie['secure'],
            'httponly' => $cookie['httponly'],
            'samesite' => $cookie['samesite'] ?? 'Lax',
        ]);
    }

    if (session_status() === PHP_SESSION_ACTIVE) {
        session_destroy();
    }

    session_start();
    session_regenerate_id(true);
}

function core_require_login($loginUrl = '../view/login.php')
{
    if (is_logged_in()) {
        return;
    }

    $requestedUri = $_SERVER['REQUEST_URI'] ?? '';
    if ($requestedUri !== '' && $requestedUri[0] === '/' && substr($requestedUri, 0, 2) !== '//') {
        $_SESSION['redirect_after_login'] = $requestedUri;
    }

    header('Location: ' . $loginUrl);
    exit;
}

function core_get_redirect_after_login($fallback = '../index.php')
{
    $redirect = $_SESSION['redirect_after_login'] ?? '';
    unset($_SESSION['redirect_after_login']);

    if ($redirect !== '' && $redirect[0] === '/' && substr($redirect, 0, 2) !== '//' && strpos($redirect, '\\') === false) {
        return $redirect;
    }

    return $fallback;
}

function core_session_security()
{
    if (!is_logged_in()) {
        return;
    }

    $now = time();
    $timedOut = isset($_SESSION['last_activity']) && ($now - (int) $_SESSION['last_activity']) > CORE_SESSION_TIMEOUT;
    $ipChanged = !isset($_SESSION['auth_ip']) || !hash_equals((string) $_SESSION['auth_ip'], (string) ($_SERVER['REMOTE_ADDR'] ?? ''));
    $agentChanged = !isset($_SESSION['auth_user_agent']) || !hash_equals((string) $_SESSION['auth_user_agent'], (string) ($_SERVER['HTTP_USER_AGENT'] ?? ''));

    if ($timedOut || $ipChanged || $agentChanged) {
        core_logout();
        $_SESSION['login_error'] = $timedOut
            ? 'Your session expired. Please log in again.'
            : 'Your session could not be verified. Please log in again.';
        return;
    }

    $_SESSION['last_activity'] = $now;
}

core_session_security();