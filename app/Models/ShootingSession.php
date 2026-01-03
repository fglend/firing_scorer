<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShootingSession extends Model
{

    protected $fillable = [
        'trainee_name'
    ];
    public function target()
    {
        return $this->hasOne(Target::class);
    }

    public function recommendations()
    {
        return $this->hasMany(Recommendation::class);
    }
    public function iotReadings()
    {
        return $this->hasMany(IotReading::class);
    }
}
