<?php

use Illuminate\Database\Seeder;
use App\Models\FootballClub;
use App\Models\Result;

class FootballClubSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $footballClubs = [
            [
                'name' => 'Chelsea',
                'attack' => 80,
                'midfield' => 84,
                'defence' => 81,
            ],
            [
                'name' => 'Arsenal',
                'attack' => 84,
                'midfield' => 82,
                'defence' => 78,
            ],
            [
                'name' => 'Manchester City',
                'attack' => 87,
                'midfield' => 86,
                'defence' => 83,
            ],
            [
                'name' => 'Liverpool',
                'attack' => 87,
                'midfield' => 83,
                'defence' => 85,
            ],
        ];

        foreach ($footballClubs as $fc) {
            $insertedFc = FootballClub::create($fc);
            $insertedFc->standing()->create();
        }

        // BEGIN --- Create Fixture --- 
        $footballClubs = FootballClub::all()->shuffle()->toArray();
        $awayFootballClubs = array_splice($footballClubs, (count($footballClubs) / 2));
        $homeFootballClubs = $footballClubs;
        for ($weekRoundKey = 1; $weekRoundKey < count($homeFootballClubs) + count($homeFootballClubs); $weekRoundKey++) {    
            for ($z = 0; $z < count($homeFootballClubs); $z++) {
                $weekRound[$weekRoundKey][] = [
                    'home_football_club_id' => $homeFootballClubs[$z]['id'],
                    'away_football_club_id' => $awayFootballClubs[$z]['id'],
                ];
            }
            if (count($homeFootballClubs) + count($awayFootballClubs) - 1 > 2) {
                array_unshift($awayFootballClubs, array_splice($homeFootballClubs, 1, 1)[0]);
                array_push($homeFootballClubs, array_pop($awayFootballClubs));
            }
        }

        for ($i = 1; $i <= count($weekRound); $i++) {
            foreach ($weekRound[$i] as $weeklyMatch) {
                Result::create([
                    'week' => $i,
                    'home_football_club_id' => $weeklyMatch['home_football_club_id'],
                    'away_football_club_id' => $weeklyMatch['away_football_club_id'],
                ]);

                Result::create([
                    'week' => count($weekRound) + $i,
                    'home_football_club_id' => $weeklyMatch['away_football_club_id'],
                    'away_football_club_id' => $weeklyMatch['home_football_club_id'],
                ]);
            }
        }
        // END --- Create Fixture --- 

    }
}
