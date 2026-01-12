<?php
require_once 'config/app.php';
require_once 'helpers/functions.php';

// Test Date Format
echo "Test Date: " . date('Y-m-d') . "\n";
echo "Formatted: " . formatDate(date('Y-m-d')) . "\n";

// Test QR Generation
require_once 'app/Services/QRCodeService.php';
// Mock dependencies (QRCodeService requires them to be autoloaded or included)
// Since we don't have full autoload in this snippet, let's just test the raw generation logic if possible, 
// or simpler: create a test file in public/test_qr.php that has access to full app environment.
