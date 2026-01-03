<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shot extends Model
{
    protected $fillable = [
        'target_id',
        'x_coordinate',
        'y_coordinate',
        'distance_from_center',
        'score'
    ];

    public function target()
    {
        return $this->belongsTo(Target::class);
    }
}
