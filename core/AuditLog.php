<?php
/**
 * Audit Logger - Records all data changes
 */
class AuditLogger {

    public static function log(string $action, ?string $tableName = null, ?int $recordId = null, $oldData = null, $newData = null): void {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("INSERT INTO `audit_log` (`user_id`, `username`, `action`, `table_name`, `record_id`, `old_data`, `new_data`, `ip_address`, `user_agent`) VALUES (:user_id, :username, :action, :table_name, :record_id, :old_data, :new_data, :ip_address, :user_agent)");
            $stmt->execute([
                ':user_id' => $_SESSION['user_id'] ?? null,
                ':username' => $_SESSION['username'] ?? 'system',
                ':action' => $action,
                ':table_name' => $tableName,
                ':record_id' => $recordId,
                ':old_data' => $oldData ? json_encode($oldData) : null,
                ':new_data' => $newData ? json_encode($newData) : null,
                ':ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
                ':user_agent' => substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 500),
            ]);
        } catch (Exception $e) {
            error_log("Audit log error: " . $e->getMessage());
        }
    }
}
