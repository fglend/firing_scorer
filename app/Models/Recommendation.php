<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recommendation extends Model
{
    public function session()
    {
        return $this->belongsTo(ShootingSession::class, 'shooting_session_id');
    }
}
