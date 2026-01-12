<?php
/**
 * QR Code Service Interface
 * 
 * Interface untuk layanan QR Code
 */

namespace Core\Interfaces;

interface QRServiceInterface
{
    /**
     * Generate QR code image
     * 
     * @param string $data Data to encode
     * @param string $filename Output filename
     * @return bool True on success
     */
    public function generate(string $data, string $filename): bool;

    /**
     * Validate QR code data
     * 
     * @param string $data Scanned QR data
     * @return array Validation result
     */
    public function validate(string $data): array;

    /**
     * Create new attendance session
     * 
     * @param string $waktuMulai Start time
     * @param string $waktuSelesai End time
     * @return array|null Session data or null on failure
     */
    public function createSession(string $waktuMulai, string $waktuSelesai): ?array;
}
