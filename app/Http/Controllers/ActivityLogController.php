<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ActivityLogController extends Controller
{
    public function __construct()
    {
        // Middleware is handled at the route level
    }

    /**
     * Display a listing of activity logs
     */
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->latest();

        // Apply filters
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('action_type')) {
            $query->where('action_type', $request->action_type);
        }

        if ($request->filled('model_type')) {
            $query->where('model_type', $request->model_type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay()
            ]);
        }

        // Apply search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('action_type', 'like', "%{$search}%")
                  ->orWhere('model_type', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $logs = $query->paginate(50)->appends($request->query());

        // Get filter options
        $users = User::where('role', 'admin')->orWhere('role', 'user')->get();
        $actionTypes = ActivityLog::select('action_type')->distinct()->pluck('action_type');
        $modelTypes = ActivityLog::select('model_type')->distinct()->whereNotNull('model_type')->pluck('model_type');

        // Get statistics
        $stats = [
            'total_logs' => ActivityLog::count(),
            'today_logs' => ActivityLog::whereDate('created_at', today())->count(),
            'this_week_logs' => ActivityLog::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month_logs' => ActivityLog::whereMonth('created_at', now()->month)->count(),
        ];

        return view('activity-logs.index', compact('logs', 'users', 'actionTypes', 'modelTypes', 'stats'));
    }

    /**
     * Display the specified activity log
     */
    public function show(ActivityLog $activityLog)
    {
        $activityLog->load('user', 'model');

        return view('activity-logs.show', compact('activityLog'));
    }

    /**
     * Export activity logs
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'csv');

        if (!in_array($format, ['csv', 'json', 'xml'])) {
            $format = 'csv';
        }

        $filters = $request->only(['user_id', 'action_type', 'model_type', 'status', 'start_date', 'end_date', 'search']);

        $exportData = ActivityLogService::exportLogs($format, $filters);

        if ($format === 'csv') {
            return response()->stream($exportData['callback'], 200, $exportData['headers']);
        }

        $filename = 'activity_logs_' . date('Y-m-d_H-i-s') . '.' . $format;

        return response($exportData, 200, [
            'Content-Type' => $format === 'json' ? 'application/json' : 'application/xml',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Clean up old activity logs (Admin only)
     */
    public function cleanup(Request $request)
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Accès refusé. Cette fonctionnalité est réservée aux administrateurs.');
        }

        $request->validate([
            'days_to_keep' => 'required|integer|min:1|max:365',
        ]);

        $daysToKeep = $request->days_to_keep;
        $deletedCount = ActivityLogService::cleanupOldLogs($daysToKeep);

        return redirect()->back()->with('success', "{$deletedCount} anciens logs ont été supprimés avec succès.");
    }

    /**
     * Get activity statistics
     */
    public function statistics(Request $request)
    {
        $startDate = $request->get('start_date') ? Carbon::parse($request->start_date) : now()->startOfMonth();
        $endDate = $request->get('end_date') ? Carbon::parse($request->end_date) : now()->endOfMonth();

        $statistics = ActivityLogService::getStatistics($startDate, $endDate);

        return view('activity-logs.statistics', compact('statistics', 'startDate', 'endDate'));
    }

    /**
     * Bulk delete activity logs (Admin only)
     */
    public function bulkDelete(Request $request)
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Accès refusé. Cette fonctionnalité est réservée aux administrateurs.');
        }

        $request->validate([
            'log_ids' => 'required|array',
            'log_ids.*' => 'exists:activity_logs,id',
        ]);

        $logIds = $request->log_ids;
        $deletedCount = ActivityLog::whereIn('id', $logIds)->delete();

        ActivityLogService::logBulkDelete('ActivityLog', $deletedCount, 'Suppression en masse des logs d\'activité');

        return redirect()->back()->with('success', "{$deletedCount} logs ont été supprimés avec succès.");
    }

    /**
     * Get logs by user
     */
    public function getByUser(User $user)
    {
        $logs = ActivityLog::where('user_id', $user->id)
            ->with('user')
            ->latest()
            ->paginate(25);

        return view('activity-logs.by-user', compact('logs', 'user'));
    }

    /**
     * Get logs by action type
     */
    public function getByActionType($actionType)
    {
        $logs = ActivityLog::where('action_type', $actionType)
            ->with('user')
            ->latest()
            ->paginate(25);

        return view('activity-logs.by-action-type', compact('logs', 'actionType'));
    }

    /**
     * Get logs by model
     */
    public function getByModel($modelType, $modelId = null)
    {
        $query = ActivityLog::where('model_type', $modelType);

        if ($modelId) {
            $query->where('model_id', $modelId);
        }

        $logs = $query->with('user', 'model')->latest()->paginate(25);

        return view('activity-logs.by-model', compact('logs', 'modelType', 'modelId'));
    }

    /**
     * Show activity log details in modal
     */
    public function details(ActivityLog $activityLog)
    {
        $activityLog->load('user', 'model');

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $activityLog->id,
                'user' => $activityLog->user ? $activityLog->user->name : 'Système',
                'action_type' => $activityLog->action_type_label,
                'model' => $activityLog->model_name,
                'model_id' => $activityLog->model_id,
                'description' => $activityLog->description,
                'ip_address' => $activityLog->ip_address,
                'user_agent' => $activityLog->user_agent,
                'route' => $activityLog->route,
                'method' => $activityLog->method,
                'status' => $activityLog->status_label,
                'old_values' => $activityLog->old_values,
                'new_values' => $activityLog->new_values,
                'changes' => $activityLog->changes,
                'has_changes' => $activityLog->hasTrackedChanges(),
                'created_at' => $activityLog->created_at->format('d/m/Y H:i:s'),
            ]
        ]);
    }

    /**
     * Get real-time activity count
     */
    public function getRealTimeCount()
    {
        $count = ActivityLog::whereDate('created_at', today())->count();

        return response()->json([
            'count' => $count,
            'formatted' => number_format($count)
        ]);
    }

    /**
     * Get recent activities for dashboard
     */
    public function getRecentActivities($limit = 10)
    {
        $activities = ActivityLog::with('user')
            ->latest()
            ->limit($limit)
            ->get();

        return response()->json($activities);
    }
}
