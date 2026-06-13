<?php

/**
 * Small view/helper utilities shared across pages.
 */

declare(strict_types=1);

/*
 * Harden session cookies before any page starts its session. This file is
 * always required before session_start(), so keeping the policy here applies
 * it everywhere: cookies are HTTP-only, scoped with SameSite=Lax, and only
 * server-issued session IDs are accepted.
 */
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    ini_set('session.use_strict_mode', '1');
}

/**
 * Escape a value for safe output inside HTML.
 * Guards against XSS when echoing database or user-supplied content.
 */
function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

/**
 * Send an HTTP redirect and stop execution.
 */
function redirect(string $url): never
{
    header('Location: ' . $url);
    exit;
}
