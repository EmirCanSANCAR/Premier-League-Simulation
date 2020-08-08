<?php

namespace App\Helpers;

use App\Models\FootballClub;
use App\Models\Standing;

class PremierLeagueUtils
{
    public static function matchResultPredictionCalc(FootballClub $homeFC, FootballClub $awayFC)
    {
        $homeFCGoalCount = 0;
        $awayFCGoalCount = 0;

        $HOME_FC_ADVANTAGE_RATE = 1.3;

        $homeFCStraightArr = [
            $homeFC->attack,
            $homeFC->midfield,
            $homeFC->defence,
        ];

        $awayFCStraightArr = [
            $awayFC->attack,
            $awayFC->midfield,
            $awayFC->defence,
        ];

        $homeFCStraight = (array_sum($homeFCStraightArr) / count($homeFCStraightArr) * $HOME_FC_ADVANTAGE_RATE);
        $awayFCStraight = (array_sum($awayFCStraightArr) / count($awayFCStraightArr));

        $probabilityLength = 6000;
        $homeFCProbability = $homeFCStraight / $probabilityLength;
        $awayFCProbability = $awayFCStraight / $probabilityLength;

        for ($i = 0; $i < 90; $i++) {
            if (mt_rand($homeFCProbability, $probabilityLength) <= $homeFCProbability * $probabilityLength) {
                $homeFCGoalCount++;
            }

            if (mt_rand($awayFCProbability, $probabilityLength) <= $awayFCProbability * $probabilityLength) {
                $awayFCGoalCount++;
            }
        }

        return [
            'homeFCGoalCount' => $homeFCGoalCount,
            'awayFCGoalCount' => $awayFCGoalCount,
        ];
    }

    public static function resultProccess($results, $savePermanently = true)
    {
        foreach ($results as $result) {
            if (!empty($result->home_football_club_goal_count) || !empty($result->away_football_club_goal_count)) {
                continue;
            }

            $matchResultPrediction = self::matchResultPredictionCalc($result->homeFootballClub, $result->awayFootballClub);

            $result->home_football_club_goal_count = $matchResultPrediction['homeFCGoalCount'];
            $result->away_football_club_goal_count = $matchResultPrediction['awayFCGoalCount'];
            if ($savePermanently) {
                $result->save();
            }

            $homeFCStanding = $result->homeFootballClub->standing;
            $awayFCStanding = $result->awayFootballClub->standing;

            $homeFCStanding->p += 1;
            $awayFCStanding->p += 1;

            $homeFCStanding->gd += ($matchResultPrediction['homeFCGoalCount'] - $matchResultPrediction['awayFCGoalCount']);
            $awayFCStanding->gd += ($matchResultPrediction['awayFCGoalCount'] - $matchResultPrediction['homeFCGoalCount']);

            if ($matchResultPrediction['homeFCGoalCount'] > $matchResultPrediction['awayFCGoalCount']) {
                $homeFCStanding->pts +=  3;
                $homeFCStanding->w +=  3;
                $awayFCStanding->l += 1;
            } else if ($matchResultPrediction['homeFCGoalCount'] === $matchResultPrediction['awayFCGoalCount']) {
                $homeFCStanding->pts +=  1;
                $awayFCStanding->pts += 1;
                $homeFCStanding->d +=  1;
                $awayFCStanding->d += 1;
            } else {
                $awayFCStanding->pts += 3;
                $awayFCStanding->w += 3;
                $homeFCStanding->l +=  1;
            }

            if ($savePermanently) {
                $homeFCStanding->save();
                $awayFCStanding->save();
            }
        }

        return [
            'results' => $results,
            // TODO:
            // 'standings' => $standings
        ];
    }

    // TODO:
    // public static function championshipPredictionBasedOnTheResults($results, $standings)
    // {
    //     return $standings;
    // }
}
