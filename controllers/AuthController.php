<?php
class AuthController extends Controller {

    public function login(): void {
        if (Auth::check()) {
            $this->redirect('dashboard');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // 1. Validate CSRF (Prevent cross site request forgery)
            if (!$this->validateCSRF()) {
                return;
            }

            // 2. Validate Honeypot (Prevent bots)
            if (!Security::validateHoneypot()) {
                // If it's a bot, just show a silent error or a general one
                $this->setFlash('error', 'Permintaan tidak sah.');
                $this->redirect('login');
                return;
            }

            // 3. Rate Limiting / Throttling (Prevent brute force)
            if (!Security::checkThrottling()) {
                $this->setFlash('error', 'Terlalu banyak percobaan login. Silakan tunggu 15 menit.');
                $this->view('auth/login', $this->getLoginViewData([
                    'error' => 'Terlalu banyak percobaan login. Silakan tunggu 15 menit.',
                ]));
                return;
            }

            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';

            if (empty($username) || empty($password)) {
                $this->setFlash('error', 'Username dan password harus diisi.');
                $this->view('auth/login', $this->getLoginViewData([
                    'error' => 'Username dan password harus diisi.',
                ]));
                return;
            }

            if (Auth::attempt($username, $password)) {
                // 4. Session Hijacking Protection (Regenerate session ID)
                session_regenerate_id(true);
                
                // Success: Clear login attempts for this IP
                Security::clearLoginAttempts();

                $this->setFlash('success', 'Selamat datang kembali!');
                $this->redirect('dashboard');
            } else {
                // Failure: Record attempt
                Security::recordFailedLogin($username);

                // Add artificial delay to slow down brute force
                usleep(500000); // 0.5 sec

                $this->view('auth/login', $this->getLoginViewData([
                    'error' => 'Username atau password salah.',
                ]));
                return;
            }
        }

        $this->view('auth/login', $this->getLoginViewData());
    }

    public function logout(): void {
        Auth::logout();
        if (isset($_GET['idle']) && $_GET['idle'] == 1) {
            $this->setFlash('error', 'Sesi Anda telah berakhir karena tidak ada aktivitas selama 15 menit.');
        } else {
            $this->setFlash('success', 'Anda telah berhasil logout.');
        }
        $this->redirect('login');
    }

    public function index(): void {
        $this->login();
    }

    public function ping(): void {
        if (Auth::check()) {
            echo json_encode(['status' => 'alive']);
        } else {
            http_response_code(401);
            echo json_encode(['status' => 'dead']);
        }
        exit;
    }

    private function getLoginViewData(array $data = []): array {
        require_once BASE_PATH . '/models/Setting.php';

        $settingModel = new Setting();
        $data['appSettings'] = $settingModel->getAllSettings();

        return $data;
    }
}
