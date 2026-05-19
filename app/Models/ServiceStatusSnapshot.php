<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceStatusSnapshot extends Model
{
    protected $fillable = [
        'service_id',
        'recorded_on',
        'status',
        'uptime_percentage',
        'response_time_ms',
        'incidents_count',
    ];

    protected function casts(): array
    {
        return [
            'recorded_on' => 'date',
            'uptime_percentage' => 'float',
            'response_time_ms' => 'integer',
            'incidents_count' => 'integer',
        ];
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
