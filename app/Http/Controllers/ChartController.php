<?php

namespace App\Http\Controllers;

use App\Advert;
use App\Theme;
use App\Video;
use Cache;
use DB;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ChartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $video = Video::inRandomOrder()->first();

        return view('charts', compact('video'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $this->validate(request(), [
            'time' => ['required', Rule::in(['week', 'month', 'all'])]
        ]);

        $time = request('time', 'all');

        $mostDownloaded = Cache::remember('chart_downloaded_' . $time, 600, function () use($time) {
                            return Theme::with(['author'])
                                            ->withCount([
                                                'downloads' => function($q) use($time){
                                                    if($time == 'week'){
                                                        $q->where('created_at', '>=', \Carbon\Carbon::today()->subWeek() );
                                                    } else if($time == 'month'){
                                                        $q->where('created_at', '>=', \Carbon\Carbon::today()->subMonth() );
                                                    }
                                                }
                                            ])
                                            ->where('public', 1)
                                            ->orderBy('downloads_count', 'desc')
                                            ->orderBy('created_at', 'desc')
                                            ->take(10)
                                            ->get();
                        });

        $highestRated = Cache::remember('chart_rated_' . $time, 600, function () use($time) {
                            return Theme::with(['author'])
                                            ->withCount(['ratings as average_rating' => function($q) use($time){
                                                $q->select(DB::raw('ROUND(coalesce(avg(value),0),2)'));
                                                if($time == 'week'){
                                                    $q->where('created_at', '>=', \Carbon\Carbon::today()->subWeek() );
                                                } else if($time == 'month'){
                                                    $q->where('created_at', '>=', \Carbon\Carbon::today()->subMonth() );
                                                }
                                            }])
                                            ->withCount(['ratings' => function($q) use($time){
                                                    if($time == 'week'){
                                                        $q->where('created_at', '>=', \Carbon\Carbon::today()->subWeek() );
                                                    } else if($time == 'month'){
                                                        $q->where('created_at', '>=', \Carbon\Carbon::today()->subMonth() );
                                                    }
                                                }
                                            ])
                                            ->where('public', 1)
                                            ->whereHas('ratings', function($q) use($time){
                                                if($time == 'week'){
                                                    $q->where('created_at', '>=', \Carbon\Carbon::today()->subWeek() );
                                                } else if($time == 'month'){
                                                    $q->where('created_at', '>=', \Carbon\Carbon::today()->subMonth() );
                                                }
                                            })
                                            ->orderByDesc('average_rating')
                                            ->orderByDesc('ratings_count')
                                            ->take(10)
                                            ->get();
                        });

        $now = \Carbon\Carbon::now();

        $products1 = Advert::where('from', '<=', $now)
                            ->where('to', '>', $now)
                            ->inRandomOrder()
                            ->limit(5)
                            ->get();

        $products2 = Advert::where('from', '<=', $now)
                            ->where('to', '>', $now)
                            ->inRandomOrder()
                            ->limit(5)
                            ->get();

        return [
            'mostDownloaded' => $mostDownloaded,
            'highestRated'   => $highestRated,
            'products1'      => $products1,
            'products2'      => $products2
        ];
    }
}
