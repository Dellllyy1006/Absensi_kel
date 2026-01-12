<?php
/**
 * QR Session Model
 * 
 * Model untuk tabel qr_sessions
 */

namespace App\Models;

use Core\Model;

class QRSession extends Model
{
    protected string $table = 'qr_sessions';
    
    protected array $fillable = [
        'session_code',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'is_active',
        'created_by'
    ];

    /**
     * Find session by code
     * 
     * @param string $code
     * @return array|null
     */
    public function findByCode(string $code): ?array
    {
        return $this->findBy('session_code', $code);
    }

    /**
     * Get active session for today
     * 
     * @return array|null
     */
    public function getActiveSession(): ?array
    {
        $now = date('H:i:s');
        $today = date('Y-m-d');
        
        $sql = "SELECT * FROM {$this->table} 
                WHERE tanggal = ? 
                AND waktu_mulai <= ? 
                AND waktu_selesai >= ? 
                AND is_active = 1 
                LIMIT 1";
        $stmt = $this->db->query($sql, [$today, $now, $now]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Get all sessions for today
     * 
     * @return array
     */
    public function getTodaySessions(): array
    {
        $today = date('Y-m-d');
        $sql = "SELECT qs.*, u.username as created_by_name 
                FROM {$this->table} qs 
                JOIN users u ON qs.created_by = u.id 
                WHERE qs.tanggal = ? 
                ORDER BY qs.waktu_mulai ASC";
        $stmt = $this->db->query($sql, [$today]);
        return $stmt->fetchAll();
    }

    /**
     * Deactivate session
     * 
     * @param int $id
     * @return bool
     */
    public function deactivate(int $id): bool
    {
        return $this->update($id, ['is_active' => 0]);
    }

    /**
     * Validate session code
     * 
     * @param string $code
     * @return bool
     */
    public function isValidSession(string $code): bool
    {
        $session = $this->findByCode($code);
        
        if (!$session) {
            return false;
        }
        
        $now = date('H:i:s');
        $today = date('Y-m-d');
        
        return $session['tanggal'] === $today 
            && $session['waktu_mulai'] <= $now 
            && $session['waktu_selesai'] >= $now 
            && $session['is_active'] == 1;
    }
}
