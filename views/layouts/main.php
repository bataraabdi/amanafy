<?php
/**
 * Main Admin Layout
 */
$appSettings = $appSettings ?? [];
$namaApp = $appSettings['nama_masjid'] ?? APP_NAME;
$pageTitle = $pageTitle ?? 'Dashboard';
$flashSuccess = getFlash('success');
$flashError = getFlash('error');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistem Manajemen Keuangan <?= e($namaApp) ?>">
    <title><?= e($pageTitle) ?> - <?= e($namaApp) ?></title>
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/assets/img/favicon.png">


    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            corePlugins: { preflight: false } // Prevent conflict with Bootstrap
        };
        window.APP_BASE_URL = '<?= BASE_URL ?>';
    </script>
    <!-- Custom CSS -->
    <link href="<?= BASE_URL ?>/assets/css/style.css" rel="stylesheet">
</head>
<body>
    <!-- Flash Messages (hidden inputs for JS) -->
    <?php if ($flashSuccess): ?>
        <input type="hidden" id="flash-success" value="<?= e($flashSuccess) ?>">
    <?php endif; ?>
    <?php if ($flashError): ?>
        <input type="hidden" id="flash-error" value="<?= e($flashError) ?>">
    <?php endif; ?>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay"></div>

    <!-- Sidebar -->
    <?php include BASE_PATH . '/views/layouts/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Header -->
        <?php include BASE_PATH . '/views/layouts/header.php'; ?>

        <!-- Content -->
        <div class="content-wrapper">
            <?php
            if (isset($_content)) {
                include BASE_PATH . '/views/' . $_content . '.php';
            }
            ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Custom JS -->
    <script src="<?= BASE_URL ?>/assets/js/app.js"></script>

    <!-- Export Libraries (PDF + Excel) — served locally for reliability -->
    <script src="<?= BASE_URL ?>/assets/js/html2pdf.bundle.min.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/xlsx.full.min.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/report-export.js"></script>

    <?php if (isset($extraJs)): ?>
        <?= $extraJs ?>
    <?php endif; ?>
</body>
</html>
