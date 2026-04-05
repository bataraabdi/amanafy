<?php
/**
 * Router - Simple URL routing
 */
class Router {
    private array $routes = [];
    private string $controller = 'DashboardController';
    private string $method = 'index';
    private array $params = [];

    public function __construct() {
        $url = $this->parseUrl();
        $this->resolveRoute($url);
    }

    private function parseUrl(): array {
        $url = $_GET['url'] ?? '';
        $url = rtrim($url, '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);
        return $url ? explode('/', $url) : [];
    }

    private function resolveRoute(array $url): void {
        $controllerMap = [
            '' => 'DashboardController',
            'dashboard' => 'DashboardController',
            'auth' => 'AuthController',
            'login' => 'AuthController',
            'logout' => 'AuthController',
            'donatur' => 'DonaturController',
            'pemasukan' => 'PemasukanController',
            'pengeluaran' => 'PengeluaranController',
            'kas-bank' => 'KasBankController',
            'kegiatan' => 'KegiatanController',
            'donasi' => 'DonasiController',
            'laporan' => 'LaporanController',
            'settings' => 'SettingsController',
            'users' => 'UserController',
            'audit' => 'AuditController',
            'backup' => 'BackupController',
            'publik' => 'PublicController',
            'profil' => 'ProfilController',
        ];

        if (!empty($url[0])) {
            $segment = strtolower($url[0]);
            if (isset($controllerMap[$segment])) {
                $this->controller = $controllerMap[$segment];
            } else {
                $this->controller = 'DashboardController';
                $this->method = 'notFound';
                return;
            }
        }

        // Handle special routes
        if (!empty($url[0]) && $url[0] === 'login') {
            $this->method = 'login';
        } elseif (!empty($url[0]) && $url[0] === 'logout') {
            $this->method = 'logout';
        }

        // Method (second segment)
        if (!empty($url[1])) {
            $this->method = $this->sanitizeMethodName($url[1]);
        }

        // Parameters (remaining segments)
        $this->params = array_slice($url, 2);
    }

    private function sanitizeMethodName(string $name): string {
        // Convert kebab-case to camelCase
        $name = str_replace('-', ' ', $name);
        $name = ucwords($name);
        $name = str_replace(' ', '', $name);
        return lcfirst($name);
    }

    public function dispatch(): void {
        $controllerFile = BASE_PATH . '/controllers/' . $this->controller . '.php';

        if (!file_exists($controllerFile)) {
            $this->error404();
            return;
        }

        require_once $controllerFile;

        if (!class_exists($this->controller)) {
            $this->error404();
            return;
        }

        $controllerInstance = new $this->controller();

        if (!method_exists($controllerInstance, $this->method)) {
            $this->error404();
            return;
        }

        call_user_func_array([$controllerInstance, $this->method], $this->params);
    }

    private function error404(): void {
        http_response_code(404);
        require_once BASE_PATH . '/views/errors/404.php';
    }

    public function getController(): string { return $this->controller; }
    public function getMethod(): string { return $this->method; }
    public function getParams(): array { return $this->params; }
}
