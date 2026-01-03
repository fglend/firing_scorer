<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Target extends Model
{
    public function session()
    {
        return $this->belongsTo(ShootingSession::class, 'shooting_session_id');
    }

    public function shots()
    {
        return $this->hasMany(Shot::class);
    }
}
