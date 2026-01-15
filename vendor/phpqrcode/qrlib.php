<?php
/*
 * PHP QR Code encoder
 *
 * Wrapper to include the main merged library file.
 */

if (file_exists(__DIR__ . '/phpqrcode.php')) {
    require_once __DIR__ . '/phpqrcode.php';
}
