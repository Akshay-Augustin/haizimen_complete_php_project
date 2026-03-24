<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function base_path(string $path = ''): string
{
    $base = __DIR__ . '/../../';
    return $path ? $base . ltrim($path, '/') : $base;
}

function redirect_to(string $path): void
{
    header('Location: ' . $path);
    exit;
}

function e(?string $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function old(string $key, string $default = ''): string
{
    return $_SESSION['old'][$key] ?? $default;
}

function flash_set(string $key, string $message): void
{
    $_SESSION['flash'][$key] = $message;
}

function flash_get(string $key): ?string
{
    if (!isset($_SESSION['flash'][$key])) {
        return null;
    }

    $message = $_SESSION['flash'][$key];
    unset($_SESSION['flash'][$key]);
    return $message;
}

function remember_old_input(array $data): void
{
    $_SESSION['old'] = $data;
}

function clear_old_input(): void
{
    unset($_SESSION['old']);
}

function is_post(): bool
{
    return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
}

function ensure_auth(): void
{
    if (empty($_SESSION['auth'])) {
        redirect_to('login_cgt.php');
    }
}
