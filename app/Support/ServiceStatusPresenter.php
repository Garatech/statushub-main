<?php

namespace App\Support;

use App\Models\Service;
use Illuminate\Support\Collection;

class ServiceStatusPresenter
{
    public static function toUiStatus(?string $status): string
    {
        return match ($status) {
            'partial_outage' => 'degraded',
            'major_outage' => 'outage',
            default => $status ?: 'operational',
        };
    }

    public static function toLabel(?string $status): string
    {
        return match ($status) {
            'operational' => 'Operacional',
            'degraded' => 'Degradado',
            'partial_outage' => 'Interrupción parcial',
            'major_outage' => 'Caído',
            'maintenance' => 'Mantenimiento',
            default => ucfirst((string) $status),
        };
    }

    public static function presentService(Service $service, int $historyDays = 30): array
    {
        $snapshots = $service->relationLoaded('snapshots')
            ? $service->snapshots->sortBy('recorded_on')->values()
            : collect();

        $incidents = $service->relationLoaded('incidents')
            ? $service->incidents->sortByDesc('created_at')->values()
            : collect();

        $recentSnapshots = $snapshots->take(-$historyDays)->values();
        $latestSnapshot = $recentSnapshots->last();
        $recentIncidents = $incidents->filter(
            fn ($incident) => $incident->created_at && $incident->created_at->gte(now()->subDays($historyDays))
        )->values();

        $metrics = [
            'uptime_24h' => self::averageUptime($recentSnapshots->take(-1)),
            'uptime_7d' => self::averageUptime($recentSnapshots->take(-7)),
            'uptime_30d' => self::averageUptime($recentSnapshots),
            'avg_response_time_ms' => (int) round($recentSnapshots->avg('response_time_ms') ?? 0),
            'incidents_total' => $incidents->count(),
            'incidents_30d' => $recentIncidents->count(),
            'active_incidents' => $incidents->where('status', '!=', 'resolved')->count(),
            'last_incident_at' => $incidents->first()?->created_at?->toIso8601String(),
            'last_snapshot_on' => $latestSnapshot?->recorded_on?->toDateString(),
        ];

        $latestIncident = $incidents->first();

        return array_merge($service->toArray(), [
            'ui_status' => self::toUiStatus($service->status),
            'status_label' => self::toLabel($service->status),
            'metrics' => $metrics,
            'status_history' => $recentSnapshots->map(function ($snapshot) {
                return [
                    'date' => $snapshot->recorded_on->toDateString(),
                    'status' => $snapshot->status,
                    'ui_status' => self::toUiStatus($snapshot->status),
                    'status_label' => self::toLabel($snapshot->status),
                    'uptime_percentage' => round((float) $snapshot->uptime_percentage, 2),
                    'response_time_ms' => $snapshot->response_time_ms,
                    'incidents_count' => $snapshot->incidents_count,
                ];
            })->values()->all(),
            'latest_incident' => $latestIncident ? [
                'id' => $latestIncident->id,
                'title' => $latestIncident->title,
                'status' => $latestIncident->status,
                'impact' => $latestIncident->impact,
                'created_at' => $latestIncident->created_at?->toIso8601String(),
                'resolved_at' => $latestIncident->resolved_at?->toIso8601String(),
            ] : null,
        ]);
    }

    public static function presentGlobalMetrics(Collection $services): array
    {
        $servicesArray = $services->all();

        return [
            'uptime_global_30d' => round(collect($servicesArray)->avg('metrics.uptime_30d') ?? 0, 2),
            'avg_response_time_ms' => (int) round(collect($servicesArray)->avg('metrics.avg_response_time_ms') ?? 0),
            'services_operational' => collect($servicesArray)->where('ui_status', 'operational')->count(),
            'services_degraded' => collect($servicesArray)->where('ui_status', 'degraded')->count(),
            'services_outage' => collect($servicesArray)->where('ui_status', 'outage')->count(),
        ];
    }

    private static function averageUptime(Collection $snapshots): float
    {
        if ($snapshots->isEmpty()) {
            return 0.0;
        }

        return round((float) $snapshots->avg('uptime_percentage'), 2);
    }
}
