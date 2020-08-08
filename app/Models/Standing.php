<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Standing extends Model
{
    protected $guarded = [];
    protected $primaryKey = 'football_club_id';
    public $incrementing = false;
    
    public function footballClub()
    {
        return $this->belongsTo(FootballClub::class);
    }
}
