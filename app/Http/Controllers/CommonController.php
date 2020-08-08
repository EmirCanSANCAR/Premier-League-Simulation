<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\PremierLeagueUtils;
use App\Models\FootballClub;
use App\Models\Result;
use App\Models\Standing;

class CommonController extends Controller
{
    public function postResetLeague(Request $request)
    {
        \Artisan::call('migrate:fresh');
        \Artisan::call('db:seed');
    }

    public function getStandings(Request $request)
    {
        $payload = Standing::with('footballClub')
            ->orderBy('pts', 'DESC')
            ->orderBy('gd', 'DESC')
            ->get();

        return response()->json($payload);
    }

    public function getResults(Request $request)
    {
        $weekCount = Result::max('week');
        $latestWeek = Result::whereNotNull('home_football_club_goal_count')->orderBy('week', 'DESC')->pluck('week')->first();
        if (empty($latestWeek)) {
            $latestWeek = 1;
        }

        $query = Result::with('homeFootballClub', 'awayFootballClub');

        if ($request->filled('week')) {
            $filteredWeek = $request->week;
            if ($request->week === 'latest') {
                $filteredWeek = $latestWeek;
            }
            $query->where('week', $filteredWeek);
        }

        $payload = $query->orderBy('week')->get();


        // TODO:
        // $results = Result::orderBy('week')->get();
        // $estimatedResultsAndStandings = PremierLeagueUtils::resultProccess($results, false);
        // $championshipPrediction = PremierLeagueUtils::championshipPredictionBasedOnTheResults(
        //     $estimatedResultsAndStandings['results'],
        //     $estimatedResultsAndStandings['standings']
        // );

        // TODO:
        // $footballClubs = FootballClub::all();
        // foreach ($footballClubs as $footballClub) {
        //     $playedResult = Result::orWhere('home_football_club_id', $footballClub->id)
        //         ->orWhere('home_football_club_id', $footballClub->id)
        //         ->whereNotNull('home_football_club_goal_count')
        //         ->orderBy('week', 'DESC')->get();
        // }

        // TODO:
        $championshipPrediction = FootballClub::all();

        return response()->json([
            'results' => $payload,
            'latest_week' => $latestWeek,
            'week_count' => $weekCount,
            'championship_prediction' => $championshipPrediction,
        ]);
    }

    public function postPlayAll(Request $request)
    {
        $results = Result::all();
        $processedResults = PremierLeagueUtils::resultProccess($results);

        return response()->json($processedResults);
    }

    public function postNextWeek(Request $request)
    {
        $latestWeek = Result::whereNull('home_football_club_goal_count')->orderBy('week')->pluck('week')->first();

        $results = Result::where('week', $latestWeek)->get();
        $processedResults = PremierLeagueUtils::resultProccess($results);

        return response()->json($processedResults);
    }
}
