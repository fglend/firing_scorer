<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recommendation extends Model
{

    protected $fillable = [
        'shooting_session_id',
        'recommendation_type',
        'message'
    ];
    public function session()
    {
        return $this->belongsTo(ShootingSession::class, 'shooting_session_id');
    }
}
