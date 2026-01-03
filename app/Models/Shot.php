<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shot extends Model
{
    public function target()
    {
        return $this->belongsTo(Target::class);
    }
}
