<?php

/**
 * Small view/helper utilities shared across pages.
 */

declare(strict_types=1);

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
