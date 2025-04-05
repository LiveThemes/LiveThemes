<?php

namespace App\Http\Controllers;

use App\Advert;
use App\Theme;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $themes = Theme::select('id', 'author_user_id', 'name', 'elements')
                        ->with(['author' => function($q){
                            $q->select('id', 'name');
                        }])
                        ->where('featured', 1)
                        ->inRandomOrder()
                        ->limit(5)
                        ->get();

        foreach($themes as $t){
            $t->css = $t->css();
        }

        //
        // Get Adverts
        //
        $now = Carbon::now();

        $adverts = Advert::where('from', '<=', $now)
                            ->where('to', '>', $now)
                            ->inRandomOrder()
                            ->limit(4)
                            ->get();

        return view('home', compact('themes', 'adverts'));
    }
}
