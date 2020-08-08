<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use Traits\HasCompositePrimaryKey;
    
    protected $guarded = [];
    protected $primaryKey = ['home_football_club_id', 'away_football_club_id'];
    public $incrementing = false;

    public function homeFootballClub()
    {
        return $this->belongsTo(FootballClub::class, 'home_football_club_id');
    }

    public function awayFootballClub()
    {
        return $this->belongsTo(FootballClub::class, 'away_football_club_id');
    }
}
