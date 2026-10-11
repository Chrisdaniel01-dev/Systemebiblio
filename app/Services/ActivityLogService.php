<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class ActivityLogService
{
    /**
     * Log a general activity
     */
    public static function log($actionType, $description = null, $model = null, $oldValues = null, $newValues = null, $status = 'success')
    {
        try {
            return ActivityLog::logActivity($actionType, $description, $model, $oldValues, $newValues, $status);
        } catch (\Exception $e) {
            // Log to Laravel's log file if activity logging fails
            logger('Activity logging failed: ' . $e->getMessage(), [
                'action_type' => $actionType,
                'description' => $description,
                'user_id' => Auth::id(),
                'ip_address' => Request::ip(),
            ]);
            return null;
        }
    }

    /**
     * Log model creation
     */
    public static function logCreate($model, $description = null)
    {
        $description = $description ?: "Création de {$model->getTable()} #{$model->getKey()}";
        return self::log('create', $description, $model, null, $model->toArray());
    }

    /**
     * Log model update
     */
    public static function logUpdate($model, $oldValues = null, $description = null)
    {
        $description = $description ?: "Modification de {$model->getTable()} #{$model->getKey()}";
        return self::log('update', $description, $model, $oldValues, $model->toArray());
    }

    /**
     * Log model deletion
     */
    public static function logDelete($model, $description = null)
    {
        $description = $description ?: "Suppression de {$model->getTable()} #{$model->getKey()}";
        return self::log('delete', $description, $model, $model->toArray(), null);
    }

    /**
     * Log model view
     */
    public static function logView($model, $description = null)
    {
        $description = $description ?: "Consultation de {$model->getTable()} #{$model->getKey()}";
        return self::log('view', $description, $model);
    }

    /**
     * Log user login
     */
    public static function logLogin($user = null)
    {
        $user = $user ?: Auth::user();
        $description = "Connexion au système depuis " . Request::ip();
        return self::log('login', $description);
    }

    /**
     * Log user logout
     */
    public static function logLogout($user = null)
    {
        $user = $user ?: Auth::user();
        $description = "Déconnexion du système";
        return self::log('logout', $description);
    }

    /**
     * Log bulk operations
     */
    public static function logBulkDelete($modelType, $count, $description = null)
    {
        $description = $description ?: "Suppression en masse de {$count} {$modelType}";
        return self::log('bulk_delete', $description, null, null, ['count' => $count, 'model' => $modelType]);
    }

    public static function logBulkUpdate($modelType, $count, $description = null)
    {
        $description = $description ?: "Modification en masse de {$count} {$modelType}";
        return self::log('bulk_update', $description, null, null, ['count' => $count, 'model' => $modelType]);
    }

    /**
     * Log export operations
     */
    public static function logExport($modelType, $count = null, $format = 'csv')
    {
        $description = "Export de {$modelType}" . ($count ? " ({$count} éléments)" : "") . " en {$format}";
        return self::log('export', $description, null, null, ['model' => $modelType, 'count' => $count, 'format' => $format]);
    }

    /**
     * Log import operations
     */
    public static function logImport($modelType, $count, $description = null)
    {
        $description = $description ?: "Import de {$count} {$modelType}";
        return self::log('import', $description, null, null, ['model' => $modelType, 'count' => $count]);
    }

    /**
     * Log errors
     */
    public static function logError($description, $model = null)
    {
        return self::log('error', $description, $model, null, null, 'error');
    }

    /**
     * Log warnings
     */
    public static function logWarning($description, $model = null)
    {
        return self::log('warning', $description, $model, null, null, 'warning');
    }

    /**
     * Log system events
     */
    public static function logSystem($event, $description = null)
    {
        $description = $description ?: "Événement système: {$event}";
        return self::log('system', $description);
    }

    /**
     * Log permission changes
     */
    public static function logPermissionChange($user, $permission, $oldValue, $newValue)
    {
        $description = "Modification de permission '{$permission}' de '{$oldValue}' vers '{$newValue}'";
        return self::log('permission_change', $description, $user, ['permission' => $permission, 'old' => $oldValue], ['permission' => $permission, 'new' => $newValue]);
    }

    /**
     * Log role changes
     */
    public static function logRoleChange($user, $oldRole, $newRole)
    {
        $description = "Changement de rôle de '{$oldRole}' vers '{$newRole}'";
        return self::log('role_change', $description, $user, ['role' => $oldRole], ['role' => $newRole]);
    }

    /**
     * Log password changes
     */
    public static function logPasswordChange($user)
    {
        $description = "Modification du mot de passe";
        return self::log('password_change', $description, $user);
    }

    /**
     * Log profile updates
     */
    public static function logProfileUpdate($user, $oldValues = null)
    {
        $description = "Modification du profil utilisateur";
        return self::log('profile_update', $description, $user, $oldValues, $user->toArray());
    }

    /**
     * Log settings changes
     */
    public static function logSettingChange($setting, $oldValue, $newValue)
    {
        $description = "Modification du paramètre '{$setting}'";
        return self::log('setting_change', $description, null, ['setting' => $setting, 'old' => $oldValue], ['setting' => $setting, 'new' => $newValue]);
    }

    /**
     * Log database operations
     */
    public static function logDatabaseOperation($operation, $table, $description = null)
    {
        $description = $description ?: "Opération '{$operation}' sur la table '{$table}'";
        return self::log('database', $description, null, null, ['operation' => $operation, 'table' => $table]);
    }

    /**
     * Log API calls
     */
    public static function logApiCall($endpoint, $method = 'GET', $statusCode = 200)
    {
        $description = "Appel API {$method} {$endpoint} - Status: {$statusCode}";
        return self::log('api_call', $description, null, null, ['endpoint' => $endpoint, 'method' => $method, 'status_code' => $statusCode]);
    }

    /**
     * Log file operations
     */
    public static function logFileOperation($operation, $filePath, $description = null)
    {
        $description = $description ?: "Opération '{$operation}' sur le fichier '{$filePath}'";
        return self::log('file_operation', $description, null, null, ['operation' => $operation, 'file_path' => $filePath]);
    }

    /**
     * Log email operations
     */
    public static function logEmail($recipient, $subject, $status = 'sent')
    {
        $description = "Email '{$subject}' à {$recipient} - Status: {$status}";
        return self::log('email', $description, null, null, ['recipient' => $recipient, 'subject' => $subject, 'status' => $status]);
    }

    /**
     * Log security events
     */
    public static function logSecurityEvent($event, $description = null)
    {
        $description = $description ?: "Événement de sécurité: {$event}";
        return self::log('security', $description, null, null, ['event' => $event], 'warning');
    }

    /**
     * Log maintenance operations
     */
    public static function logMaintenance($operation, $description = null)
    {
        $description = $description ?: "Opération de maintenance: {$operation}";
        return self::log('maintenance', $description, null, null, ['operation' => $operation]);
    }

    /**
     * Log backup operations
     */
    public static function logBackup($type, $description = null)
    {
        $description = $description ?: "Sauvegarde de type: {$type}";
        return self::log('backup', $description, null, null, ['type' => $type]);
    }

    /**
     * Log cleanup operations
     */
    public static function logCleanup($description, $recordsDeleted = null)
    {
        $description = $description . ($recordsDeleted ? " ({$recordsDeleted} enregistrements supprimés)" : "");
        return self::log('cleanup', $description, null, null, ['records_deleted' => $recordsDeleted]);
    }

    /**
     * Get activity statistics
     */
    public static function getStatistics($startDate = null, $endDate = null)
    {
        $query = ActivityLog::query();

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        return [
            'total_activities' => $query->count(),
            'activities_by_type' => $query->selectRaw('action_type, COUNT(*) as count')
                ->groupBy('action_type')
                ->pluck('count', 'action_type')
                ->toArray(),
            'activities_by_user' => $query->with('user')
                ->selectRaw('user_id, COUNT(*) as count')
                ->whereNotNull('user_id')
                ->groupBy('user_id')
                ->with('user:id,name,email')
                ->get()
                ->mapWithKeys(function ($item) {
                    return [$item->user->name => $item->count];
                })
                ->toArray(),
            'recent_activities' => $query->with('user')
                ->latest()
                ->limit(10)
                ->get(),
        ];
    }

    /**
     * Clean up old activity logs
     */
    public static function cleanupOldLogs($daysToKeep = 30)
    {
        $cutoffDate = now()->subDays($daysToKeep);

        $deletedCount = ActivityLog::where('created_at', '<', $cutoffDate)->delete();

        self::logCleanup("Nettoyage des logs antérieurs à {$daysToKeep} jours", $deletedCount);

        return $deletedCount;
    }

    /**
     * Export activity logs
     */
    public static function exportLogs($format = 'csv', $filters = [])
    {
        $query = ActivityLog::with('user')->latest();

        // Apply filters
        if (isset($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (isset($filters['action_type'])) {
            $query->where('action_type', $filters['action_type']);
        }

        if (isset($filters['start_date']) && isset($filters['end_date'])) {
            $query->whereBetween('created_at', [$filters['start_date'], $filters['end_date']]);
        }

        $logs = $query->get();

        if ($format === 'csv') {
            return self::exportToCsv($logs);
        }

        return $logs;
    }

    /**
     * Export to CSV format
     */
    private static function exportToCsv($logs)
    {
        $filename = 'activity_logs_' . date('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($logs) {
            $file = fopen('php://output', 'w');

            // CSV headers
            fputcsv($file, [
                'ID',
                'Utilisateur',
                'Type d\'action',
                'Modèle',
                'ID Modèle',
                'Description',
                'Adresse IP',
                'Agent utilisateur',
                'Route',
                'Méthode',
                'Statut',
                'Date de création',
            ]);

            // CSV data
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->user ? $log->user->name : 'Système',
                    $log->action_type_label,
                    $log->model_name,
                    $log->model_id,
                    $log->description,
                    $log->ip_address,
                    $log->user_agent,
                    $log->route,
                    $log->method,
                    $log->status,
                    $log->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return ['callback' => $callback, 'headers' => $headers, 'filename' => $filename];
    }
}
