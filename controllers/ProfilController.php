<?php
class ProfilController extends Controller {

    public function index(): void {
        $this->requireAuth();
        require_once BASE_PATH . '/models/User.php';
        
        $userModel = new User();
        $currentUser = $userModel->findById(Auth::id());

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();
            $action = $_POST['action'] ?? '';

            try {
                if ($action === 'update_profile') {
                    $namaLengkap = trim($_POST['nama_lengkap'] ?? '');
                    $email = trim($_POST['email'] ?? '');

                    if (empty($namaLengkap)) {
                        throw new RuntimeException('Nama Lengkap tidak boleh kosong.');
                    }

                    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        throw new RuntimeException('Format Email tidak valid.');
                    }

                    // Cek email duplikat
                    if (!empty($email)) {
                        $existingEmail = $userModel->findByEmailExcludingId($email, $currentUser['id']);
                        if ($existingEmail) {
                            throw new RuntimeException('Email sudah digunakan oleh pengguna lain.');
                        }
                    }

                    $userModel->update($currentUser['id'], [
                        'nama_lengkap' => $namaLengkap,
                        'email' => $email
                    ]);

                    // Update session
                    $_SESSION['nama_lengkap'] = $namaLengkap;
                    $_SESSION['email'] = $email;

                    AuditLogger::log('UPDATE', 'users', $currentUser['id'], $currentUser, ['action' => 'update_profile']);
                    $this->setFlash('success', 'Profil dan Data Diri berhasil diperbarui.');

                } elseif ($action === 'update_password') {
                    $passwordLama = $_POST['password_lama'] ?? '';
                    $passwordBaru = $_POST['password_baru'] ?? '';
                    $konfirmasiPassword = $_POST['konfirmasi_password'] ?? '';

                    if (empty($passwordLama) || empty($passwordBaru) || empty($konfirmasiPassword)) {
                        throw new RuntimeException('Semua kolom password wajib diisi.');
                    }

                    if (!password_verify($passwordLama, $currentUser['password'])) {
                        throw new RuntimeException('Password lama tidak sesuai.');
                    }

                    if ($passwordBaru !== $konfirmasiPassword) {
                        throw new RuntimeException('Konfirmasi password baru tidak cocok.');
                    }

                    if (strlen($passwordBaru) < 6) {
                        throw new RuntimeException('Password baru minimal 6 karakter.');
                    }

                    $userModel->updateUser($currentUser['id'], [
                        'password' => $passwordBaru
                    ]);

                    AuditLogger::log('UPDATE', 'users', $currentUser['id'], null, ['action' => 'update_password']);
                    $this->setFlash('success', 'Password berhasil diubah. Silakan gunakan password baru pada login berikutnya.');
                }

            } catch (Throwable $e) {
                $this->setFlash('error', $e->getMessage());
            }

            $this->redirect('profil');
            return;
        }

        $this->renderPage('profil/index', [
            'pageTitle' => 'Ubah Profil & Password',
            'user' => $currentUser
        ]);
    }
}
