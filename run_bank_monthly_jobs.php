<?php
if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    echo 'CLI only';
    exit(1);
}

require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/core/Helpers.php';
require_once __DIR__ . '/core/Model.php';
require_once __DIR__ . '/core/FinancialService.php';

$period = $argv[1] ?? date('Y-m');
$userId = isset($argv[2]) ? (int)$argv[2] : 1;

try {
    $service = new FinancialService();
    $result = $service->runMonthlyBankAutomation($period, $userId);

    echo "Periode: {$period}" . PHP_EOL;
    echo "Biaya admin dibuat: " . (int)$result['fees_created'] . PHP_EOL;
    echo "Jasa giro dibuat: " . (int)$result['interest_created'] . PHP_EOL;

    if (!empty($result['errors'])) {
        echo "Error:" . PHP_EOL;
        foreach ($result['errors'] as $error) {
            echo "- {$error}" . PHP_EOL;
        }
        exit(1);
    }

    exit(0);
} catch (Throwable $e) {
    fwrite(STDERR, $e->getMessage() . PHP_EOL);
    exit(1);
}
