<?php
class UserController extends Controller {

    public function index(): void {
        $this->requireRole(['Super Admin']);
        require_once BASE_PATH . '/models/User.php';
        $model = new User();
        $users = $model->getAllWithRole();
        $this->renderPage('users/index', ['pageTitle' => 'Kelola User', 'users' => $users]);
    }

    public function create(): void {
        $this->requireRole(['Super Admin']);
        require_once BASE_PATH . '/models/User.php';
        require_once BASE_PATH . '/models/Role.php';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();
            $model = new User();
            $data = [
                'nama_lengkap' => trim($_POST['nama_lengkap'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'no_hp' => trim($_POST['no_hp'] ?? ''),
                'username' => trim($_POST['username'] ?? ''),
                'password' => $_POST['password'] ?? '',
                'role_id' => (int)($_POST['role_id'] ?? 2),
                'status' => $_POST['status'] ?? 'aktif',
            ];

            $validationError = $this->validateUserData($data, $model);
            if ($validationError !== null) {
                $this->setFlash('error', $validationError);
                $this->redirect('users/create');
                return;
            }

            $id = $model->createUser($data);
            AuditLogger::log('CREATE', 'users', $id, null, ['username' => $data['username'], 'role_id' => $data['role_id']]);
            $this->setFlash('success', 'User berhasil ditambahkan.');
            $this->redirect('users');
            return;
        }

        $roleModel = new Role();
        $this->renderPage('users/create', ['pageTitle' => 'Tambah User', 'roles' => $roleModel->getAllRoles()]);
    }

    public function edit(string $id = ''): void {
        $this->requireRole(['Super Admin']);
        require_once BASE_PATH . '/models/User.php';
        require_once BASE_PATH . '/models/Role.php';

        $model = new User();
        $editUser = $model->findWithRoleById((int)$id);
        if (!$editUser) { $this->redirect('users'); return; }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();
            $data = [
                'nama_lengkap' => trim($_POST['nama_lengkap'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'no_hp' => trim($_POST['no_hp'] ?? ''),
                'username' => trim($_POST['username'] ?? ''),
                'password' => $_POST['password'] ?? '',
                'role_id' => (int)($_POST['role_id'] ?? $editUser['role_id']),
                'status' => $_POST['status'] ?? 'aktif',
            ];

            $validationError = $this->validateUserData($data, $model, (int)$id);
            if ($validationError !== null) {
                $this->setFlash('error', $validationError);
                $this->redirect('users/edit/' . (int)$id);
                return;
            }

            if ((int)$id === Auth::id() && $data['status'] !== 'aktif') {
                $this->setFlash('error', 'Akun yang sedang digunakan tidak dapat dinonaktifkan.');
                $this->redirect('users/edit/' . (int)$id);
                return;
            }

            $model->updateUser((int)$id, $data);
            $updatedUser = $model->findWithRoleById((int)$id);

            if ((int)$id === Auth::id() && $updatedUser) {
                $this->syncAuthenticatedUserSession($updatedUser);
            }

            AuditLogger::log('UPDATE', 'users', (int)$id, $editUser, ['username' => $data['username'], 'role_id' => $data['role_id'], 'status' => $data['status']]);
            $this->setFlash('success', 'User berhasil diperbarui.');

            if ((int)$id === Auth::id() && $updatedUser && ($updatedUser['nama_role'] ?? '') !== 'Super Admin') {
                $this->redirect('dashboard');
            }

            $this->redirect('users');
            return;
        }

        $roleModel = new Role();
        $this->renderPage('users/edit', ['pageTitle' => 'Edit User', 'editUser' => $editUser, 'roles' => $roleModel->getAllRoles()]);
    }

    public function delete(string $id = ''): void {
        $this->requireRole(['Super Admin']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();
            if ((int)$id === Auth::id()) { $this->setFlash('error', 'Tidak bisa menghapus akun sendiri.'); $this->redirect('users'); return; }
            require_once BASE_PATH . '/models/User.php';
            $model = new User();
            $old = $model->findById((int)$id);
            $model->delete((int)$id);
            AuditLogger::log('DELETE', 'users', (int)$id, $old, null);
            $this->setFlash('success', 'User berhasil dihapus.');
        }
        $this->redirect('users');
    }

    private function validateUserData(array $data, User $model, ?int $excludeId = null): ?string {
        if ($data['nama_lengkap'] === '' || $data['email'] === '' || $data['username'] === '') {
            return 'Nama lengkap, email, dan username wajib diisi.';
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return 'Format email tidak valid.';
        }

        if ($excludeId === null && $data['password'] === '') {
            return 'Password wajib diisi untuk user baru.';
        }

        if ($data['password'] !== '' && strlen($data['password']) < 6) {
            return 'Password minimal 6 karakter.';
        }

        $usernameExists = $excludeId === null
            ? $model->findByUsername($data['username'])
            : $model->findByUsernameExcludingId($data['username'], $excludeId);
        if ($usernameExists) {
            return 'Username sudah digunakan.';
        }

        $emailExists = $excludeId === null
            ? $model->findByEmail($data['email'])
            : $model->findByEmailExcludingId($data['email'], $excludeId);
        if ($emailExists) {
            return 'Email sudah digunakan.';
        }

        if (!in_array($data['status'], ['aktif', 'nonaktif'], true)) {
            return 'Status user tidak valid.';
        }

        return null;
    }

    private function syncAuthenticatedUserSession(array $user): void {
        $_SESSION['username'] = $user['username'];
        $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role_id'] = $user['role_id'];
        $_SESSION['role_name'] = $user['nama_role'] ?? $_SESSION['role_name'] ?? '';
        $_SESSION['hak_akses'] = json_decode($user['hak_akses'] ?? '[]', true) ?? [];
    }
}
