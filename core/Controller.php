<?php
/**
 * Base Controller
 */
class Controller {

    protected function view(string $viewPath, array $data = []): void {
        extract($data);
        $viewFile = BASE_PATH . '/views/' . $viewPath . '.php';
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die("View not found: " . htmlspecialchars($viewPath));
        }
    }

    protected function renderPage(string $viewPath, array $data = [], string $layout = 'layouts/main'): void {
        $data['_content'] = $viewPath;
        $data['_csrf_token'] = CSRF::generateToken();
        
        // Get settings for layout
        if (!isset($data['appSettings'])) {
            require_once BASE_PATH . '/models/Setting.php';
            $settingModel = new Setting();
            $data['appSettings'] = $settingModel->getAllSettings();
        }
        
        $this->view($layout, $data);
    }

    protected function redirect(string $url): void {
        header("Location: " . BASE_URL . '/' . ltrim($url, '/'));
        exit;
    }

    protected function redirectBack(): void {
        $referer = $_SERVER['HTTP_REFERER'] ?? BASE_URL;
        header("Location: " . $referer);
        exit;
    }

    protected function json(array $data, int $statusCode = 200): void {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function requireAuth(): void {
        if (!Auth::check()) {
            $_SESSION['flash_error'] = 'Silakan login terlebih dahulu.';
            $this->redirect('login');
        }
    }

    protected function requireRole(array $roles): void {
        $this->requireAuth();
        if (!Auth::hasRole($roles)) {
            $_SESSION['flash_error'] = 'Anda tidak memiliki akses ke halaman ini.';
            $this->redirect('dashboard');
        }
    }

    protected function validateCSRF(): bool {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['csrf_token'] ?? '';
            if (!CSRF::validateToken($token)) {
                $_SESSION['flash_error'] = 'Token keamanan tidak valid. Silakan coba lagi.';
                $this->redirectBack();
                return false;
            }
        }
        return true;
    }

    protected function setFlash(string $type, string $message): void {
        $_SESSION['flash_' . $type] = $message;
    }

    protected function uploadFile(array $file, string $destination, array $allowedTypes = ['jpg', 'jpeg', 'png', 'pdf']): ?string {
        if ($file['error'] !== UPLOAD_ERR_OK || $file['size'] === 0) {
            return null;
        }

        $maxSize = 10 * 1024 * 1024; // 10MB
        if ($file['size'] > $maxSize) {
            $this->setFlash('error', 'Ukuran file terlalu besar. Maksimal 10MB.');
            return null;
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowedTypes)) {
            $this->setFlash('error', 'Tipe file tidak diizinkan.');
            return null;
        }

        // Create directory if not exists
        if (!is_dir($destination)) {
            mkdir($destination, 0755, true);
        }

        $filename = uniqid() . '_' . time() . '.' . $ext;
        $filepath = $destination . $filename;

        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return $filename;
        }

        return null;
    }

    protected function deleteFile(string $filepath): bool {
        if (file_exists($filepath)) {
            return unlink($filepath);
        }
        return false;
    }
}
