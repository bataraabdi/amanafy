<?php
class DonaturController extends Controller {

    public function index(): void {
        $this->requireRole(['Super Admin', 'Bendahara']);
        require_once BASE_PATH . '/models/Donatur.php';
        $model = new Donatur();
        $donaturList = $model->getAll();
        $this->renderPage('donatur/index', ['pageTitle' => 'Manajemen Donatur', 'donaturList' => $donaturList]);
    }

    public function create(): void {
        $this->requireRole(['Super Admin', 'Bendahara']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();
            require_once BASE_PATH . '/models/Donatur.php';
            $model = new Donatur();
            $data = [
                'nama_donatur' => trim($_POST['nama_donatur'] ?? ''),
                'no_hp' => trim($_POST['no_hp'] ?? ''),
                'alamat' => trim($_POST['alamat'] ?? ''),
                'jenis_donatur' => $_POST['jenis_donatur'] ?? 'tidak_tetap',
                'catatan' => trim($_POST['catatan'] ?? ''),
            ];
            $id = $model->create($data);
            AuditLogger::log('CREATE', 'donatur', $id, null, $data);
            $this->setFlash('success', 'Donatur berhasil ditambahkan.');
            $this->redirect('donatur');
            return;
        }
        $this->renderPage('donatur/create', ['pageTitle' => 'Tambah Donatur']);
    }

    public function edit(string $id = ''): void {
        $this->requireRole(['Super Admin', 'Bendahara']);
        require_once BASE_PATH . '/models/Donatur.php';
        $model = new Donatur();
        $donatur = $model->findById((int)$id);
        if (!$donatur) { $this->redirect('donatur'); return; }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();
            $oldData = $donatur;
            $data = [
                'nama_donatur' => trim($_POST['nama_donatur'] ?? ''),
                'no_hp' => trim($_POST['no_hp'] ?? ''),
                'alamat' => trim($_POST['alamat'] ?? ''),
                'jenis_donatur' => $_POST['jenis_donatur'] ?? 'tidak_tetap',
                'catatan' => trim($_POST['catatan'] ?? ''),
            ];
            $model->update((int)$id, $data);
            AuditLogger::log('UPDATE', 'donatur', (int)$id, $oldData, $data);
            $this->setFlash('success', 'Donatur berhasil diperbarui.');
            $this->redirect('donatur');
            return;
        }
        $this->renderPage('donatur/edit', ['pageTitle' => 'Edit Donatur', 'donatur' => $donatur]);
    }

    public function delete(string $id = ''): void {
        $this->requireRole(['Super Admin', 'Bendahara']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();
            require_once BASE_PATH . '/models/Donatur.php';
            $model = new Donatur();
            $old = $model->findById((int)$id);
            $model->delete((int)$id);
            AuditLogger::log('DELETE', 'donatur', (int)$id, $old, null);
            $this->setFlash('success', 'Donatur berhasil dihapus.');
        }
        $this->redirect('donatur');
    }
}
