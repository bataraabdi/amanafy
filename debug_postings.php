<?php
define('BASE_PATH', __DIR__);
require_once 'config/database.php';
require_once 'core/Auth.php';
require_once 'core/Helpers.php';
require_once 'core/Model.php';
require_once 'core/Controller.php';

$db = Database::getConnection();
echo "--- ALL BANK ACCOUNTS ---\n";
$banks = $db->query("SELECT id, nama_bank, bank_admin_fee, bank_interest, is_active FROM bank_accounts")->fetchAll();
print_r($banks);
