<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action_type',
        'model_type',
        'model_id',
        'description',
        'old_values',
        'new_values',
        'status',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function model()
    {
        return $this->morphTo();
    }

    // Scopes for filtering
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByActionType($query, $actionType)
    {
        return $query->where('action_type', $actionType);
    }

    public function scopeByModel($query, $modelType, $modelId = null)
    {
        $query->where('model_type', $modelType);
        if ($modelId) {
            $query->where('model_id', $modelId);
        }
        return $query;
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Helper methods
    public function getActionTypeLabelAttribute()
    {
        return match($this->action_type) {
            'create' => 'Création',
            'update' => 'Modification',
            'delete' => 'Suppression',
            'emprunt' => 'Emprunt',
            'retour' => 'Retour',
            'login' => 'Connexion',
            'logout' => 'Déconnexion',
            'view' => 'Consultation',
            'export' => 'Export',
            'import' => 'Import',
            'bulk_delete' => 'Suppression en masse',
            'bulk_update' => 'Modification en masse',
            default => ucfirst($this->action_type)
        };
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'success' => '<span class="badge badge-success">Succès</span>',
            'error' => '<span class="badge badge-danger">Erreur</span>',
            'warning' => '<span class="badge badge-warning">Attention</span>',
            default => '<span class="badge badge-secondary">' . ucfirst($this->status) . '</span>'
        };
    }

    public function getModelNameAttribute()
    {
        if (!$this->model_type) {
            return null;
        }

        return class_basename($this->model_type);
    }

    public function getChangesAttribute()
    {
        $changes = [];

        if ($this->old_values && $this->new_values) {
            foreach ($this->new_values as $key => $newValue) {
                $oldValue = $this->old_values[$key] ?? null;

                if ($oldValue != $newValue) {
                    $changes[$key] = [
                        'old' => $oldValue,
                        'new' => $newValue,
                    ];
                }
            }
        }

        return $changes;
    }

    public function hasTrackedChanges()
    {
        return !empty($this->changes);
    }

    // Static methods for logging
    public static function logActivity($actionType, $description = null, $model = null, $oldValues = null, $newValues = null, $status = 'success')
    {
        $data = [
            'user_id' => Auth::id(),
            'action_type' => $actionType,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'route' => request()->route() ? request()->route()->getName() : null,
            'method' => request()->method(),
            'status' => $status,
            'old_values' => $oldValues,
            'new_values' => $newValues,
        ];

        if ($model) {
            $data['model_type'] = get_class($model);
            $data['model_id'] = $model->getKey();
        }

        return static::create($data);
    }

    public static function logCreate($model, $description = null)
    {
        return static::logActivity('create', $description, $model, null, $model->toArray());
    }

    public static function logUpdate($model, $oldValues = null, $description = null)
    {
        return static::logActivity('update', $description, $model, $oldValues, $model->toArray());
    }

    public static function logDelete($model, $oldValues = null, $description = null)
    {
        return static::logActivity('delete', $description, $model, $oldValues, null);
    }

    public static function logLogin($user, $description = null)
    {
        return static::logActivity('login', $description ?: 'Connexion au système', null, null, null);
    }

    public static function logLogout($user, $description = null)
    {
        return static::logActivity('logout', $description ?: 'Déconnexion du système', null, null, null);
    }

    public static function logView($model, $description = null)
    {
        return static::logActivity('view', $description, $model);
    }

    public static function logError($description, $model = null)
    {
        return static::logActivity('error', $description, $model, null, null, 'error');
    }
}
