<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IotReading extends Model
{
    /** @use HasFactory<\Database\Factories\IotReadingFactory> */
    use HasFactory;

    protected $fillable = [
        'shooting_session_id',
        'device_id',
        'captured_at',
        'distance_m',
        'temperature_c',
        'humidity_percent',
        'light_lux',
        'imu_json',
        'raw_payload',
    ];

    protected $casts = [
        'imu_json' => 'array',
        'raw_payload' => 'array',
        'captured_at' => 'datetime',
    ];

    public function session()
    {
        return $this->belongsTo(ShootingSession::class, 'shooting_session_id');
    }
}
