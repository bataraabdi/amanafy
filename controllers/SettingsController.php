<?php
class SettingsController extends Controller {

    public function index(): void {
        $this->requireRole(['Super Admin']);
        require_once BASE_PATH . '/models/Setting.php';
        require_once BASE_PATH . '/models/Kategori.php';

        $settingModel = new Setting();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();
            $action = $_POST['action'] ?? '';

            if ($action === 'update_umum') {
                $settingModel->updateSettings([
                    'nama_masjid' => trim($_POST['nama_masjid'] ?? ''),
                    'jenis_lembaga' => $_POST['jenis_lembaga'] ?? 'masjid',
                    'alamat' => trim($_POST['alamat'] ?? ''),
                    'no_telepon' => trim($_POST['no_telepon'] ?? ''),
                    'status_lembaga' => $_POST['status_lembaga'] ?? 'aktif',
                ]);
                if (!empty($_FILES['logo']['name'])) {
                    $filename = $this->uploadFile($_FILES['logo'], UPLOAD_LOGO, ['jpg','jpeg','png','webp']);
                    if ($filename) $settingModel->setValue('logo', $filename);
                }
                $this->setFlash('success', 'Pengaturan umum berhasil diperbarui.');
            } elseif ($action === 'update_pengurus') {
                $settingModel->updateSettings([
                    'ketua_jabatan' => trim($_POST['ketua_jabatan'] ?? ''),
                    'ketua_nama' => trim($_POST['ketua_nama'] ?? ''),
                    'sekretaris_jabatan' => trim($_POST['sekretaris_jabatan'] ?? ''),
                    'sekretaris_nama' => trim($_POST['sekretaris_nama'] ?? ''),
                    'bendahara_jabatan' => trim($_POST['bendahara_jabatan'] ?? ''),
                    'bendahara_nama' => trim($_POST['bendahara_nama'] ?? ''),
                ]);
                $this->setFlash('success', 'Data pengurus berhasil diperbarui.');
            } elseif ($action === 'add_kategori_pemasukan') {
                $katModel = new Kategori('pemasukan');
                $katModel->create([
                    'nama_kategori' => trim($_POST['nama_kategori'] ?? ''),
                    'fund_category' => $_POST['fund_category'] ?? 'Tidak Terikat',
                    'keterangan' => trim($_POST['keterangan'] ?? '')
                ]);
                $this->setFlash('success', 'Kategori pemasukan berhasil ditambahkan.');
            } elseif ($action === 'add_kategori_pengeluaran') {
                $katModel = new Kategori('pengeluaran');
                $katModel->create([
                    'nama_kategori' => trim($_POST['nama_kategori'] ?? ''),
                    'fund_category' => $_POST['fund_category'] ?? 'Tidak Terikat',
                    'keterangan' => trim($_POST['keterangan'] ?? '')
                ]);
                $this->setFlash('success', 'Kategori pengeluaran berhasil ditambahkan.');
            } elseif ($action === 'edit_kategori_pemasukan') {
                $katModel = new Kategori('pemasukan');
                $katModel->update((int)($_POST['id'] ?? 0), [
                    'nama_kategori' => trim($_POST['nama_kategori'] ?? ''),
                    'fund_category' => $_POST['fund_category'] ?? 'Tidak Terikat',
                    'keterangan' => trim($_POST['keterangan'] ?? '')
                ]);
                $this->setFlash('success', 'Kategori pemasukan berhasil diperbarui.');
            } elseif ($action === 'edit_kategori_pengeluaran') {
                $katModel = new Kategori('pengeluaran');
                $katModel->update((int)($_POST['id'] ?? 0), [
                    'nama_kategori' => trim($_POST['nama_kategori'] ?? ''),
                    'fund_category' => $_POST['fund_category'] ?? 'Tidak Terikat',
                    'keterangan' => trim($_POST['keterangan'] ?? '')
                ]);
                $this->setFlash('success', 'Kategori pengeluaran berhasil diperbarui.');
            } elseif ($action === 'delete_kategori_pemasukan') {
                try {
                    $katModel = new Kategori('pemasukan');
                    $katModel->delete((int)($_POST['id'] ?? 0));
                    $this->setFlash('success', 'Kategori pemasukan berhasil dihapus.');
                } catch (PDOException $e) {
                    $this->setFlash('error', 'Gagal: Kategori pemasukan sedang digunakan dalam transaksi.');
                }
            } elseif ($action === 'delete_kategori_pengeluaran') {
                try {
                    $katModel = new Kategori('pengeluaran');
                    $katModel->delete((int)($_POST['id'] ?? 0));
                    $this->setFlash('success', 'Kategori pengeluaran berhasil dihapus.');
                } catch (PDOException $e) {
                    $this->setFlash('error', 'Gagal: Kategori pengeluaran sedang digunakan dalam transaksi.');
                }
            }

            AuditLogger::log('UPDATE', 'settings', null, null, ['action' => $action]);
            $this->redirect('settings');
            return;
        }

        $settings = $settingModel->getAllSettings();
        $katPemasukan = (new Kategori('pemasukan'))->getAll();
        $katPengeluaran = (new Kategori('pengeluaran'))->getAll();

        $this->renderPage('settings/index', [
            'pageTitle' => 'Pengaturan',
            'settings' => $settings,
            'katPemasukan' => $katPemasukan,
            'katPengeluaran' => $katPengeluaran,
        ]);
    }
}
