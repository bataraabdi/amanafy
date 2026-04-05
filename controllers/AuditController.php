<?php
class AuditController extends Controller {

    public function index(): void {
        $this->requireRole(['Super Admin']);
        require_once BASE_PATH . '/models/AuditLogModel.php';

        $model = new AuditLogModel();
        $filters = [
            'action' => $_GET['action'] ?? '',
            'tanggal_dari' => $_GET['tanggal_dari'] ?? '',
            'tanggal_sampai' => $_GET['tanggal_sampai'] ?? '',
        ];

        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 50;
        $total = $model->countFiltered($filters);
        $logs = $model->getAll($perPage, ($page - 1) * $perPage, $filters);

        $this->renderPage('audit/index', [
            'pageTitle' => 'Audit Log',
            'logs' => $logs,
            'filters' => $filters,
            'total' => $total,
            'page' => $page,
            'perPage' => $perPage,
        ]);
    }
}
