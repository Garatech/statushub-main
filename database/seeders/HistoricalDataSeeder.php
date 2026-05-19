<?php

namespace Database\Seeders;

use App\Models\Incident;
use App\Models\IncidentUpdate;
use App\Models\Service;
use App\Models\ServiceStatusSnapshot;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class HistoricalDataSeeder extends Seeder
{
    public function run(): void
    {
        $adminId = User::query()->where('email', 'admin@statushub.com')->value('id');
        $services = Service::query()->get()->keyBy('slug');

        $definitions = [
            [
                'service_slug' => 'api-usuarios',
                'title' => 'Latencia elevada en autenticación',
                'description' => 'Se detectó un aumento notable en los tiempos de respuesta del flujo de login.',
                'impact' => 'minor',
                'started_at' => now()->subDays(27)->setTime(9, 20),
                'updates' => [
                    ['minutes' => 0, 'status' => 'investigating', 'message' => 'Estamos investigando el incremento de latencia en autenticación.'],
                    ['minutes' => 45, 'status' => 'identified', 'message' => 'Hemos localizado una consulta ineficiente en el proveedor de sesiones.'],
                    ['minutes' => 135, 'status' => 'monitoring', 'message' => 'Aplicado el ajuste en caché. Monitorizamos la recuperación.'],
                    ['minutes' => 220, 'status' => 'resolved', 'message' => 'El servicio ha recuperado sus tiempos habituales de respuesta.'],
                ],
            ],
            [
                'service_slug' => 'api-usuarios',
                'title' => 'Errores intermitentes en restablecimiento de contraseña',
                'description' => 'Una parte de los tokens de reseteo no se estaban validando correctamente.',
                'impact' => 'major',
                'started_at' => now()->subDays(4)->setTime(16, 5),
                'updates' => [
                    ['minutes' => 0, 'status' => 'investigating', 'message' => 'Analizamos el fallo reportado en el flujo de recuperación.'],
                    ['minutes' => 35, 'status' => 'identified', 'message' => 'El problema está ligado a la expiración adelantada de tokens firmados.'],
                    ['minutes' => 150, 'status' => 'monitoring', 'message' => 'Se ha desplegado una corrección y estamos verificando el ratio de éxito.'],
                    ['minutes' => 260, 'status' => 'resolved', 'message' => 'Flujo de recuperación restablecido y estable.'],
                ],
            ],
            [
                'service_slug' => 'base-de-datos',
                'title' => 'Saturación puntual del clúster principal',
                'description' => 'Se alcanzó el límite de conexiones concurrentes durante una ventana de carga.',
                'impact' => 'critical',
                'started_at' => now()->subDays(19)->setTime(11, 40),
                'updates' => [
                    ['minutes' => 0, 'status' => 'investigating', 'message' => 'Detectamos saturación de conexiones en el clúster principal.'],
                    ['minutes' => 20, 'status' => 'identified', 'message' => 'Una tarea de reporting intensiva ha provocado el cuello de botella.'],
                    ['minutes' => 185, 'status' => 'monitoring', 'message' => 'Capacidad ampliada temporalmente y jobs reprogramados.'],
                    ['minutes' => 370, 'status' => 'resolved', 'message' => 'Clúster estabilizado sin nuevas alertas de saturación.'],
                ],
            ],
            [
                'service_slug' => 'cdn-assets',
                'title' => 'Purgado incompleto de recursos estáticos',
                'description' => 'Algunos nodos de borde tardaron más de lo previsto en invalidar caché.',
                'impact' => 'minor',
                'started_at' => now()->subDays(23)->setTime(7, 55),
                'updates' => [
                    ['minutes' => 0, 'status' => 'investigating', 'message' => 'Investigamos contenido obsoleto servido desde varios nodos edge.'],
                    ['minutes' => 70, 'status' => 'monitoring', 'message' => 'La invalidación manual ya se ha propagado y verificamos consistencia.'],
                    ['minutes' => 155, 'status' => 'resolved', 'message' => 'Todos los nodos están sirviendo la última versión de los assets.'],
                ],
            ],
            [
                'service_slug' => 'cdn-assets',
                'title' => 'Retraso en distribución de imágenes promocionales',
                'description' => 'La replicación internacional de un lote de assets fue más lenta de lo esperado.',
                'impact' => 'major',
                'started_at' => now()->subDays(2)->setTime(13, 10),
                'updates' => [
                    ['minutes' => 0, 'status' => 'investigating', 'message' => 'Estamos analizando retrasos en propagación hacia nodos internacionales.'],
                    ['minutes' => 50, 'status' => 'identified', 'message' => 'El problema proviene de una cola de replicación congestionada.'],
                    ['minutes' => 165, 'status' => 'monitoring', 'message' => 'La cola se ha vaciado y verificamos la disponibilidad por región.'],
                    ['minutes' => 290, 'status' => 'resolved', 'message' => 'Distribución normalizada en todas las regiones monitorizadas.'],
                ],
            ],
            [
                'service_slug' => 'cache',
                'title' => 'Invalidación lenta de caché de catálogos',
                'description' => 'El vaciado de claves derivó en inconsistencias temporales en lecturas críticas.',
                'impact' => 'major',
                'started_at' => now()->subDays(10)->setTime(18, 15),
                'updates' => [
                    ['minutes' => 0, 'status' => 'investigating', 'message' => 'Analizamos retardos en invalidación de catálogos calientes.'],
                    ['minutes' => 55, 'status' => 'identified', 'message' => 'La cola de invalidación está siendo procesada por debajo de lo esperado.'],
                    ['minutes' => 190, 'status' => 'monitoring', 'message' => 'El throughput de invalidación se ha recuperado y observamos coherencia.'],
                    ['minutes' => 300, 'status' => 'resolved', 'message' => 'Sincronización de caché restablecida sin inconsistencias pendientes.'],
                ],
            ],
            [
                'service_slug' => 'terminales-pago',
                'title' => 'Interrupción breve en autorización de pagos',
                'description' => 'La pasarela externa rechazó autorizaciones durante varios minutos.',
                'impact' => 'critical',
                'started_at' => now()->subDays(29)->setTime(14, 35),
                'updates' => [
                    ['minutes' => 0, 'status' => 'investigating', 'message' => 'Detectamos un incremento súbito de fallos en autorizaciones.'],
                    ['minutes' => 15, 'status' => 'identified', 'message' => 'El proveedor de pagos presenta una degradación regional.'],
                    ['minutes' => 110, 'status' => 'monitoring', 'message' => 'Tráfico reenrutado y caída de errores confirmada.'],
                    ['minutes' => 240, 'status' => 'resolved', 'message' => 'Las autorizaciones han vuelto a su comportamiento nominal.'],
                ],
            ],
            [
                'service_slug' => 'terminales-pago',
                'title' => 'Reintentos elevados en conciliación',
                'description' => 'Se observó una subida de reintentos en la conciliación nocturna.',
                'impact' => 'minor',
                'started_at' => now()->subDays(6)->setTime(3, 25),
                'updates' => [
                    ['minutes' => 0, 'status' => 'investigating', 'message' => 'Monitorizamos reintentos superiores a lo normal en conciliación.'],
                    ['minutes' => 85, 'status' => 'monitoring', 'message' => 'Ajustados los timeouts y en seguimiento del proceso nocturno.'],
                    ['minutes' => 160, 'status' => 'resolved', 'message' => 'Conciliación completada sin nuevos reintentos fuera de umbral.'],
                ],
            ],
            [
                'service_slug' => 'email',
                'title' => 'Retraso en notificaciones transaccionales',
                'description' => 'Una parte del correo saliente se quedó retenida en cola.',
                'impact' => 'minor',
                'started_at' => now()->subDays(15)->setTime(8, 50),
                'updates' => [
                    ['minutes' => 0, 'status' => 'investigating', 'message' => 'Analizamos colas de salida con retardo por encima del SLA.'],
                    ['minutes' => 65, 'status' => 'identified', 'message' => 'Un worker secundario dejó de consumir la cola de prioridad media.'],
                    ['minutes' => 140, 'status' => 'resolved', 'message' => 'Colas vaciadas y latencia de notificación recuperada.'],
                ],
            ],
            [
                'service_slug' => 'email',
                'title' => 'Bloqueo temporal en proveedor SMTP de respaldo',
                'description' => 'El canal de respaldo aplicó un throttle más agresivo de lo habitual.',
                'impact' => 'major',
                'started_at' => now()->subDays(1)->setTime(19, 5),
                'updates' => [
                    ['minutes' => 0, 'status' => 'investigating', 'message' => 'Estamos revisando el incremento de rechazos en el proveedor de respaldo.'],
                    ['minutes' => 25, 'status' => 'identified', 'message' => 'Confirmado throttle temporal sobre uno de los pools SMTP.'],
                    ['minutes' => 135, 'status' => 'monitoring', 'message' => 'El desvío de tráfico ha reducido la cola y vigilamos la estabilización.'],
                    ['minutes' => 250, 'status' => 'resolved', 'message' => 'Entrega normalizada y sin rechazos fuera del patrón habitual.'],
                ],
            ],
        ];

        foreach ($definitions as $definition) {
            $this->seedIncident(
                $services[$definition['service_slug']],
                $adminId,
                $definition['title'],
                $definition['description'],
                $definition['impact'],
                $definition['started_at'],
                $definition['updates']
            );
        }

        Service::query()->update(['status' => 'operational']);

        foreach ($services as $service) {
            $this->seedSnapshotsForService($service);
        }
    }

    private function seedIncident(
        Service $service,
        int $adminId,
        string $title,
        string $description,
        string $impact,
        Carbon $startedAt,
        array $updatesPlan
    ): void {
        $lastUpdate = end($updatesPlan);
        $resolvedAt = (clone $startedAt)->addMinutes($lastUpdate['minutes']);

        $incident = Incident::forceCreate([
            'service_id' => $service->id,
            'user_id' => $adminId,
            'title' => $title,
            'description' => $description,
            'status' => 'resolved',
            'impact' => $impact,
            'resolved_at' => $resolvedAt,
            'created_at' => $startedAt,
            'updated_at' => $resolvedAt,
        ]);

        foreach ($updatesPlan as $updateData) {
            $createdAt = (clone $startedAt)->addMinutes($updateData['minutes']);

            IncidentUpdate::forceCreate([
                'incident_id' => $incident->id,
                'user_id' => $adminId,
                'message' => $updateData['message'],
                'status' => $updateData['status'],
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }
    }

    private function seedSnapshotsForService(Service $service): void
    {
        $incidents = $service->incidents()->get();
        $baseLatency = $this->baseLatencyForService($service->slug);

        for ($daysAgo = 29; $daysAgo >= 0; $daysAgo--) {
            $date = now()->subDays($daysAgo)->startOfDay();
            $dayStart = $date->copy();
            $dayEnd = $date->copy()->endOfDay();

            $incidentsOnDay = $incidents->filter(function ($incident) use ($dayStart, $dayEnd) {
                return $incident->created_at <= $dayEnd
                    && ($incident->resolved_at === null || $incident->resolved_at >= $dayStart);
            })->values();

            $rawStatus = $this->snapshotStatusFromIncidents($incidentsOnDay);
            $uptime = $this->uptimeForSnapshot($service->slug, $date->toDateString(), $rawStatus, $incidentsOnDay->count());
            $responseTime = $this->responseTimeForSnapshot($service->slug, $date->toDateString(), $rawStatus, $baseLatency);

            ServiceStatusSnapshot::forceCreate([
                'service_id' => $service->id,
                'recorded_on' => $date->toDateString(),
                'status' => $rawStatus,
                'uptime_percentage' => $uptime,
                'response_time_ms' => $responseTime,
                'incidents_count' => $incidentsOnDay->count(),
                'created_at' => $date->copy()->endOfDay(),
                'updated_at' => $date->copy()->endOfDay(),
            ]);
        }
    }

    private function snapshotStatusFromIncidents($incidents): string
    {
        if ($incidents->isEmpty()) {
            return 'operational';
        }

        if ($incidents->contains(fn ($incident) => $incident->impact === 'critical')) {
            return 'major_outage';
        }

        if ($incidents->contains(fn ($incident) => $incident->impact === 'major')) {
            return 'partial_outage';
        }

        return 'degraded';
    }

    private function uptimeForSnapshot(string $serviceSlug, string $dateKey, string $status, int $incidentsCount): float
    {
        $variance = $this->seededNumber($serviceSlug . $dateKey . 'uptime', 0, 18) / 100;

        return match ($status) {
            'major_outage' => max(88.10, 93.60 - ($incidentsCount * 0.7) - $variance),
            'partial_outage' => max(95.00, 97.85 - ($incidentsCount * 0.35) - $variance),
            'degraded' => max(98.10, 99.45 - ($incidentsCount * 0.18) - $variance),
            default => min(100.0, 99.82 + $variance),
        };
    }

    private function responseTimeForSnapshot(string $serviceSlug, string $dateKey, string $status, int $baseLatency): int
    {
        $variance = $this->seededNumber($serviceSlug . $dateKey . 'rt', 0, 22);

        return match ($status) {
            'major_outage' => $baseLatency + 260 + $variance,
            'partial_outage' => $baseLatency + 150 + $variance,
            'degraded' => $baseLatency + 70 + $variance,
            default => $baseLatency + $variance,
        };
    }

    private function baseLatencyForService(string $serviceSlug): int
    {
        return match ($serviceSlug) {
            'base-de-datos' => 42,
            'cache' => 36,
            'cdn-assets' => 74,
            'terminales-pago' => 118,
            'email' => 132,
            default => 86,
        };
    }

    private function seededNumber(string $seed, int $min, int $max): int
    {
        $hash = abs(crc32($seed));

        return $min + ($hash % (($max - $min) + 1));
    }
}
