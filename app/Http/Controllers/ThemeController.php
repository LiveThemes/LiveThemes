<?php

namespace App\Http\Controllers;

use App\Advert;
use App\Theme;
use App\ThemeColour;
use App\ThemeDownload;
use App\ThemeElement;
use App\ThemeRating;
use App\Video;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Response;
use Session;
use SimpleXMLElement;
use Storage;

class ThemeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($brightness = 'all', $color = 'all')
    {
        $title = [];

        if( $brightness && $brightness != 'all' ){
            $title[] = ucwords($brightness);
        }

        if( $color && $color != 'all' ){
            $title[] = ucwords($color);
        }

        if( count($title) ){
            $pageTitle = 'Free ' . implode(' ', $title) . ' Ableton 10 Themes';
        } else {
            $pageTitle = 'Browse Ableton 10 Themes';
        }

        $video = Video::inRandomOrder()->first();

        return view('browse', compact('pageTitle', 'video'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function ajax(Request $request)
    {
        $colours = ThemeColour::colours();

        $themes = Theme::with(['author'])
                        ->withCount('downloads')
                        ->withCount(['ratings as average_rating' => function($q){
                            $q->select(DB::raw('ROUND(coalesce(avg(value),0),2)'));
                        }])
                        ->where('public', 1);

        //
        // Search
        //
        if( request('search') ){

            $search = request('search');

            $themes->where('name', 'like', '%' . $search . '%');

        }

        //
        // Lightness
        //
        switch (request('brightness')) {
            case 'light':
                $themes->where('lightness', '>', 70);
                break;

            case 'dark':
                $themes->where('lightness', '<', 30);
                break;

            case 'mid':
                $themes->whereBetween('lightness', [30,70]);
                break;

            default:
                // No Filter
                break;
        }

        //
        // Color
        //
        if( request('color') && request('color') != 'all' ){

            $color = request('color');

            // Red
            if( $color == 'red' ){

                $themes->where(function($q){

                    $q->where(function($q){
                        $q->where('colour_1', '<=', 10)
                          ->orWhere('colour_1', '>=', 350);
                    })

                    ->orWhere(function($q){
                        $q->where('colour_2', '<=', 10)
                          ->orWhere('colour_2', '>=', 350);
                    });

                });

            // All Other Colours
            } else {
                $range = $colours[ $color ]['range'];
                $from = $colours[ $color ]['hue'] + $range;
                $to = $colours[ $color ]['hue'] - $range;

                $themes->where(function($q) use($from, $to){

                    $q->where(function($q) use($from, $to){
                        $q->where('colour_1', '<=', $from)
                          ->where('colour_1', '>=', $to);
                    })

                    ->orWhere(function($q) use($from, $to){
                        $q->where('colour_2', '<=', $from)
                          ->where('colour_2', '>=', $to);
                    });

                });
            }

        }

        //
        // Sorting
        //
        switch (request('sort')) {
            case 'featured':
                $themes->where('featured', 1)->orderBy('created_at', 'desc');
                break;

            case 'oldest':
                $themes->orderBy('created_at');
                break;

            case 'most-downloaded':
                $themes->orderBy('downloads_count', 'desc');
                break;

            case 'least-downloaded':
                $themes->orderBy('downloads_count', 'asc');
                break;

            case 'highest-rated':
                $themes->orderByDesc('average_rating');
                break;

            case 'lowest-rated':
                $themes->orderBy('average_rating');
                break;

            default:
                $themes->orderBy('created_at', 'desc');
                break;
        }

        //
        // Get Adverts
        //
        $now = Carbon::now();

        $adverts = Advert::where('from', '<=', $now)
                            ->where('to', '>', $now)
                            ->inRandomOrder()
                            ->limit(5)
                            ->get();

        return [
            'themes' => $themes->paginate(16),
            'products' => $adverts
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function user()
    {
        if( !Auth::check() ){
            return redirect('/register')->with('error', 'You need to create an account to create your own themes!');
        }

        $user = auth()->user();

        $themes = Theme::with(['author'])
                        ->withCount('downloads')
                        ->withCount(['ratings as average_rating' => function($q){
                            $q->select(DB::raw('ROUND(coalesce(avg(value),0),2)'));
                        }])
                        ->where('author_user_id', $user->id)
                        ->orderBy('created_at', 'desc')
                        ->get();

        $now = Carbon::now();

        $adverts = Advert::where('from', '<=', $now)
                            ->where('to', '>', $now)
                            ->inRandomOrder()
                            ->limit(5)
                            ->get();

        return view('my-themes', compact('user', 'themes', 'adverts'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function userAjax(Request $request)
    {
        $user = auth()->user();

        $themes = Theme::with(['author'])
                        ->withCount('downloads')
                        ->withCount(['ratings as average_rating' => function($q){
                            $q->select(DB::raw('ROUND(coalesce(avg(value),0),2)'));
                        }])
                        ->where('author_user_id', $user->id);

        //
        // Search
        //
        if( request('search') ){

            $search = request('search');

            $themes->where('name', 'like', '%' . $search . '%');

        }

        //
        // Visibility
        //
        switch (request('visibility')) {
            case 'public':
                $themes->where('public', 1);
                break;

            case 'private':
                $themes->where('public', 0);
                break;

            default:
                // No Filter
                break;
        }

        //
        // Sorting
        //
        switch (request('sort')) {
            case 'featured':
                $themes->where('featured', 1)->orderBy('created_at', 'desc');
                break;

            case 'oldest':
                $themes->orderBy('created_at');
                break;

            case 'most-downloaded':
                $themes->orderBy('downloads_count', 'desc');
                break;

            case 'least-downloaded':
                $themes->orderBy('downloads_count', 'asc');
                break;

            case 'highest-rated':
                $themes->orderByDesc('average_rating');
                break;

            case 'lowest-rated':
                $themes->orderBy('average_rating');
                break;

            default:
                $themes->orderBy('created_at', 'desc');
                break;
        }

        //
        // Get Adverts
        //
        $now = Carbon::now();

        $adverts = Advert::where('from', '<=', $now)
                            ->where('to', '>', $now)
                            ->inRandomOrder()
                            ->limit(5)
                            ->get();

        return [
            'themes' => $themes->paginate(16),
            'products' => $adverts
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Theme  $theme
     * @return \Illuminate\Http\Response
     */
    public function show(Theme $theme, $slug, $theme_id)
    {
        $user = auth()->user();

        $theme = Theme::with([
                            'comments' => function($q){
                                $q->orderBy('created_at', 'desc');
                            },
                            'comments.user',
                            'remixedFrom'
                        ])
                        ->withCount('downloads')
                        ->withCount(['ratings as average_rating' => function($q){
                            $q->select(DB::raw('ROUND(coalesce(avg(value),0),2)'));
                        }])
                        ->findorfail( $theme_id );

        if( $theme->public == 0 && ( !Auth::check() || $theme->author_user_id != $user->id ) ){
            return redirect('/browse');
        }

        $theme->upgrade();

        $now = Carbon::now();

        $adverts = Advert::where('from', '<=', $now)
                            ->where('to', '>', $now)
                            ->inRandomOrder()
                            ->limit(5)
                            ->get();

        $downloadAds = Advert::where('from', '<=', $now)
                            ->where('to', '>', $now)
                            ->inRandomOrder()
                            ->limit(2)
                            ->get();

        $video = Video::inRandomOrder()->first();

        return view('theme', compact('theme', 'adverts', 'downloadAds', 'video'));
    }

    /**
     * Remix
     */
    public function remix(Theme $theme, $slug, $theme_id)
    {
        Session::put('remix', intval($theme_id));

        return redirect('/editor');
    }

    /**
     * Public
     */
    public function visibility()
    {
        $this->validate(request(), [
            'theme_id' => 'required|integer'
        ]);

        $user = auth()->user();

        $theme = Theme::where('id', request('theme_id'))
                        ->where('author_user_id', $user->id)
                        ->first();

        if( is_null($theme) ){
            abort(401, 'You do not have permission to do this!');
        }

        $theme->update([
            'public' => $theme->public ? 0 : 1
        ]);

        return [
            'success' => true
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Theme  $theme
     * @return \Illuminate\Http\Response
     */
    public function destroy(Theme $theme)
    {
        $this->validate(request(), [
            'theme_id' => 'required|integer'
        ]);

        $user = auth()->user();

        $theme = Theme::where('id', request('theme_id'))
                        ->where('author_user_id', $user->id)
                        ->first();

        if( is_null($theme) ){
            abort(401, 'You do not have permission to do this!');
        }

        if( $theme->preview ){
            Storage::delete( $theme->preview );
        }

        ThemeColour::where('theme_id', $theme->id)->delete();
        ThemeDownload::where('theme_id', $theme->id)->delete();
        ThemeRating::where('theme_id', $theme->id)->delete();

        $theme->delete();

        return [
            'success' => true
        ];
    }

    /**
     * Download
     */
    public function download(Theme $theme, $slug, $theme_id)
    {
        $this->validate(request(), [
            'theme_id' => 'required|integer'
        ]);

        $user = auth()->user();

        $theme = Theme::findorfail( request('theme_id') );

        $theme->upgrade();

        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><Ableton></Ableton>');

        $xml->addAttribute('MajorVersion', '5');
        $xml->addAttribute('MinorVersion', '10.0_373');
        $xml->addAttribute('SchemaChangeCount', '1');
        $xml->addAttribute('Creator', $theme->author->name);
        $xml->addAttribute('Revision', '');

        $SkinManager = $xml->addChild('SkinManager');

        //
        // Add Existing Elements
        //
        foreach($theme->elements as $k => $v){

            $element = $SkinManager->addChild($k);

            if( isset($v->r) ){
                $r = $element->addChild('R');
                $r->addAttribute('Value', $v->r);
                $g = $element->addChild('G');
                $g->addAttribute('Value', $v->g);
                $b = $element->addChild('B');
                $b->addAttribute('Value', $v->b);
                $alpha = $element->addChild('Alpha');
                $alpha->addAttribute('Value', $v->a);

            } else if( isset($v->value) ){
                $element->addAttribute('Value', $v->value);

            } else {
                $element->addAttribute('Value', $v);

            }
        }

        $dom = dom_import_simplexml($xml)->ownerDocument;
        $dom->formatOutput = true;

        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        $ago = \Carbon\Carbon::now()->subHour();

        if( !is_null($user) ){

            $download = ThemeDownload::where('theme_id', '=', $theme->id)
                                       ->where('user_id', '=', $user->id)
                                       ->where('ip', '=', $ip)
                                       ->where('created_at', '>=', $ago)->first();

            ThemeDownload::create([
                'theme_id' => $theme->id,
                'user_id'  => $user->id,
                'ip'       => $ip
            ]);

        } else {

            ThemeDownload::create([
                'theme_id' => $theme->id,
                'user_id'  => 0,
                'ip'       => $ip
            ]);

        }

        $response = Response::make($dom->saveXML(), 200);
        $response->header('Cache-Control', 'public');
        $response->header('Content-Description', 'File Transfer');
        $response->header('Content-Disposition', 'attachment; filename=' . preg_replace('/[^a-zA-Z0-9 ]/', '', $theme->name) . '.ask');
        $response->header('Content-Transfer-Encoding', 'binary');
        $response->header('Content-Type', 'text/ask; charset=utf-8');

        return $response;
    }

    /**
     * Rate
     */
    public function rate(Theme $theme, $slug, $theme_id)
    {
        if( !Auth::check() ){
            abort(401, 'You are not logged in!');
        }

        $this->validate(request(), [
            'id'    => 'required|integer',
            'stars' => 'required|integer'
        ]);

        ThemeRating::updateOrCreate([
            'theme_id' => request('id'),
            'user_id'  => auth()->user()->id
        ], [
            'value'    => request('stars')
        ]);

        $theme = Theme::withCount(['ratings as average_rating' => function($q){
                            $q->select(DB::raw('ROUND(coalesce(avg(value),0),2)'));
                        }])
                        ->findorfail( request('id') );

        return [
            'success'        => true,
            'average_rating' => $theme->average_rating
        ];
    }

    /**
     * Feature
     */
    public function feature(Theme $theme, $slug, $theme_id)
    {
        $theme = Theme::select('id', 'featured')->findorfail($theme_id);

        $theme->update([
            'featured' => $theme->featured ? false : true
        ]);

        return redirect( $theme->url );
    }

    /**
     * Recent
     */
    public function recent()
    {
        $user = auth()->user();

        $themes = Theme::select('id', 'name', 'preview')
                        ->where('author_user_id', $user->id)
                        ->orderBy('created_at', 'desc')
                        ->limit(5)
                        ->get();

        return $themes;
    }

    /**
     * Import
     */
    public function import(Request $request)
    {
        $this->validate(request(), [
            'file' => 'required|file|mimes:ask,txt,xml|max:100'
        ],[
            'file.required' => 'You have not imported a theme',
            'file.file'     => 'This does not appear to be a file',
            'file.mimes'    => 'This file does not appear to be an Ableton 10 theme (.ask) file',
            'file.max'      => 'This file is too large to be a theme'
        ]);

        $user = auth()->user();

        //
        // Load XML
        //
        libxml_use_internal_errors(TRUE);

        $xml = simplexml_load_file( $request->file('file') );

        if($xml === FALSE){
            return response()->json([
                'message' => "There was an error reading this file"
            ], 505);
        }

        $xml = json_decode(json_encode($xml),TRUE);

        //
        // Check for Skin Manager Tag
        //
        if( !isset($xml['SkinManager']) ){
            return response()->json([
                'message' => "This doesn't appear to be a valid theme file"
            ], 505);
        }

        //
        // Top Level Theme Information
        //
        $theme = new \StdClass();

        $theme->remixed_theme_id = 0;
        $theme->author_user_id = $user->id;
        $theme->public = 0;
        $theme->name = "Imported Theme";
        $theme->major_version = intval($xml['@attributes']['MajorVersion']);
        $theme->minor_version = sanitize($xml['@attributes']['MinorVersion']);
        $theme->creator = sanitize($user->name);
        $theme->elements = new \StdClass();

        //
        // Create Elements
        //
        $themeElements = ThemeElement::select('element', 'default', 'inherits')->get();

        foreach($xml['SkinManager'] as $k => $v){

            if( $themeElements->where('element', $k)->count() != 1 ){
                return response()->json([
                    'message' => "Your theme file doesn't appear to be formatted correctly"
                ], 505);
            }

            if( isset($v['R']) ){

                $el = [
                    'r' => intval( $v['R']['@attributes']['Value'] ),
                    'g' => intval( $v['G']['@attributes']['Value'] ),
                    'b' => intval( $v['B']['@attributes']['Value'] ),
                    'a' => intval( $v['Alpha']['@attributes']['Value'] ),
                ];

                $el['rgba'] = "rgba(" . $el['r'] . "," . $el['g'] . "," . $el['b'] . "," . ( $el['a'] / 255 ) . ")";

            } else {

                $el = [
                    'value' => floatval( $v['@attributes']['Value'] )
                ];

            }

            $matchedElement = $themeElements->where('element', $k)->first();

            if( is_null($matchedElement) ){
                return response()->json([
                    'message' => "Your theme file does not appear to be formatted correctly, sorry we can't import it"
                ], 505);
            }

            $el['element'] = sanitize($k);
            $el['name'] = $matchedElement->name;

            $theme->elements->{ sanitize($k) } = $el;
        }

        //
        // Add In Elements That Don't Exist
        //
        foreach($themeElements as $v){

            // If new element not in theme
            if( !isset( $theme->elements->{ $v->element } ) ){

                // Check for inherits
                if( $v->inherits && isset($theme->elements->{ $v->inherits }) ){
                    $value = $theme->elements->{ $v->inherits };
                } else {
                    $value = $v->default;
                }

                if( isset($value->r) ){
                    $el = [
                        'r' => intval( $value->r ),
                        'g' => intval( $value->g ),
                        'b' => intval( $value->b ),
                        'a' => intval( $value->a ),
                    ];

                } else if( isset($value->value) ){
                    $el = [
                        'value' => floatval( $value->value )
                    ];

                } else {
                    $el = [
                        'value' => floatval( $value )
                    ];

                }

                $theme->elements->{ $v->element } = $el;

            }

        }

        return json_encode($theme);

    }
}
