<?php
/**
 * Base Controller
 * 
 * Menyediakan method umum untuk semua controller
 * Mengikuti prinsip Single Responsibility (SRP)
 */

namespace Core;

class Controller
{
    protected Session $session;

    public function __construct()
    {
        $this->session = new Session();
    }

    /**
     * Render view dengan data
     * 
     * @param string $view Path ke view file
     * @param array $data Data untuk view
     * @return void
     */
    protected function view(string $view, array $data = []): void
    {
        // Extract data untuk digunakan di view
        extract($data);
        
        // Path ke view file
        $viewPath = __DIR__ . '/../views/' . $view . '.php';
        
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            die("View not found: " . $view);
        }
    }

    /**
     * Render view dengan layout
     * 
     * @param string $view Path ke view file
     * @param array $data Data untuk view
     * @param string $layout Layout name
     * @return void
     */
    protected function render(string $view, array $data = [], string $layout = 'main'): void
    {
        $data['session'] = $this->session;
        $data['content'] = $view;
        
        extract($data);
        
        // Start output buffering untuk content
        ob_start();
        $viewPath = __DIR__ . '/../views/' . $view . '.php';
        if (file_exists($viewPath)) {
            require_once $viewPath;
        }
        $content = ob_get_clean();
        
        // Include header
        require_once __DIR__ . '/../views/layouts/header.php';
        
        // Include navbar
        require_once __DIR__ . '/../views/layouts/navbar.php';
        
        // Echo content
        echo '<div class="main-container">';
        if ($this->session->get('user') && $this->session->get('user')['role'] === 'admin') {
            require_once __DIR__ . '/../views/layouts/sidebar.php';
        }
        echo '<main class="main-content">' . $content . '</main>';
        echo '</div>';
        
        // Include footer
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    /**
     * Redirect ke URL lain
     * 
     * @param string $url URL tujuan
     * @return void
     */
    protected function redirect(string $url): void
    {
        header("Location: " . BASE_URL . $url);
        exit;
    }

    /**
     * Return JSON response
     * 
     * @param array $data
     * @param int $statusCode
     * @return void
     */
    protected function json(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /**
     * Set flash message
     * 
     * @param string $type success|error|warning|info
     * @param string $message
     * @return void
     */
    protected function setFlash(string $type, string $message): void
    {
        $this->session->set('flash', [
            'type' => $type,
            'message' => $message
        ]);
    }

    /**
     * Check if user is authenticated
     * 
     * @return bool
     */
    protected function isAuthenticated(): bool
    {
        return $this->session->get('user') !== null;
    }

    /**
     * Require authentication
     * 
     * @return void
     */
    protected function requireAuth(): void
    {
        if (!$this->isAuthenticated()) {
            $this->setFlash('error', 'Silakan login terlebih dahulu');
            $this->redirect('/auth/login');
        }
    }

    /**
     * Require specific role
     * 
     * @param string $role
     * @return void
     */
    protected function requireRole(string $role): void
    {
        $this->requireAuth();
        
        $user = $this->session->get('user');
        if ($user['role'] !== $role) {
            $this->setFlash('error', 'Anda tidak memiliki akses ke halaman ini');
            $this->redirect('/dashboard');
        }
    }

    /**
     * Get POST data
     * 
     * @param string|null $key
     * @return mixed
     */
    protected function getPost(?string $key = null)
    {
        if ($key === null) {
            return $_POST;
        }
        return $_POST[$key] ?? null;
    }

    /**
     * Get GET data
     * 
     * @param string|null $key
     * @return mixed
     */
    protected function getQuery(?string $key = null)
    {
        if ($key === null) {
            return $_GET;
        }
        return $_GET[$key] ?? null;
    }
}
