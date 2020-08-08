<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FootballClub extends Model
{
    protected $guarded = ['id'];

    public function standing()
    {
        return $this->hasOne(Standing::class);
    }
}
