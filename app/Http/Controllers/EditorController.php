<?php

namespace App\Http\Controllers;

use App\Advert;
use App\Colour;
use App\Theme;
use App\ThemeColour;
use App\ThemeElement;
use Auth;
use Illuminate\Http\Request;
use \Carbon\Carbon;
use Imagick;
use ImagickPixel;
use Session;
use Storage;

class EditorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($theme_id = 0)
    {
        if( !Auth::check() ){
            return redirect('/register')->with('error', 'You need to create an account to use the theme editor!');
        }

        $elements = ThemeElement::select('element')->get();

        $now = Carbon::now();

        $downloadAds = Advert::where('from', '<=', $now)
                            ->where('to', '>', $now)
                            ->inRandomOrder()
                            ->limit(2)
                            ->get();

        $standardThemes = Theme::where('author_user_id', 2)->get();

        return view('editor', compact('theme_id', 'elements', 'downloadAds', 'standardThemes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Theme  $theme
     * @return \Illuminate\Http\Response
     */
    public function show(Theme $theme)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Theme  $theme
     * @return \Illuminate\Http\Response
     */
    public function edit(Theme $theme)
    {
        $this->validate(request(), [
            'theme_id' => 'nullable|integer'
        ]);

        $user = auth()->user();

        // Remix from Welcome
        if( request('remix_id') ){
            $theme = Theme::findorfail( request('remix_id') )->toEditor();
            $theme->id = 0;
            $theme->remixed_theme_id = intval(request('remix_id'));
            $theme->name = $theme->name . ' copy';
            $theme->public = 0;

        // Requested Theme ID
        } else if( request('theme_id') ){
            $theme = Theme::findorfail( request('theme_id') )->toEditor();

        // Get Mid Light otherwise
        } else {

            $theme_id = intval(Session::get('remix', '2'));

            Session::forget('remix');

            $theme  = Theme::findorfail( $theme_id )->toEditor();
            $theme->id = 0;
            $theme->remixed_theme_id = $theme_id;
            $theme->name = $theme->name . ' copy';
            $theme->public = 0;
        }

        $theme->upgrade();

        // Global Colours
        $coloursGlobal = Colour::select('r','g','b')
                                    ->whereNull('user_id')
                                    ->get();

        $coloursGlobal->map(function($c){
            $hsl = ThemeColour::rgbToHsl($c->r, $c->g, $c->b);
            $c->hue = $hsl[0];
        });

        $coloursGlobalSorted = $coloursGlobal->sortBy('hue');

        // User Colours
        $coloursUser = Colour::select('r','g','b')->where('user_id', $user->id)->orderBy('created_at')->get();

        return [
            'theme' => $theme,
            'coloursGlobal' => $coloursGlobalSorted,
            'coloursUser' => $coloursUser
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Theme  $theme
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Theme $theme)
    {

        $this->validate(request(), [
            'theme' => 'required|array',
            'theme.name' => 'required|string'
        ]);

        $user = auth()->user();

        $theme_id = intval( request('theme.id') );

        // Check Owner
        if( $theme_id ){
            $theme = Theme::findorfail( $theme_id );

            if( $user->id != $theme->author_user_id ){
                abort(401, 'This is not your theme to update');
            }
        }

        // Update Theme
        if( $theme_id ){

            $theme->update([
                'public'      => request('theme.public', 0),
                'name'        => request('theme.name'),
                'description' => request('theme.description'),
                'elements'    => request('theme.elements'),
                'colour_1'    => request('theme.colour_1'),
                'colour_2'    => request('theme.colour_2'),
                'lightness'   => request('theme.lightness')
            ]);

        } else {

            $theme = Theme::create([
                'remixed_theme_id' => request('theme.remixed_theme_id'),
                'author_user_id'   => $user->id,
                'public'           => request('theme.public', 0),
                'name'             => request('theme.name'),
                'description'      => request('theme.description'),
                'major_version'    => request('theme.major_version'),
                'minor_version'    => request('theme.minor_version'),
                'creator'          => request('theme.creator'),
                'elements'         => request('theme.elements'),
                'colour_1'         => request('theme.colour_1'),
                'colour_2'         => request('theme.colour_2'),
                'lightness'        => request('theme.lightness')
            ]);

        }

        // Save Colours
        ThemeColour::where('theme_id', $theme->id)->delete();
        $ele = request('theme.elements');

        foreach($ele as $k => $e){
            if(isset($e['r'])){

                $hsl = ThemeColour::rgbToHsl($e['r'], $e['g'], $e['b']);
                $hex = ThemeColour::rgbToHex($e['r'], $e['g'], $e['b']);

                ThemeColour::create([
                    'theme_id'   => $theme->id,
                    'element'    => $k,
                    'red'        => $e['r'],
                    'green'      => $e['g'],
                    'blue'       => $e['b'],
                    'hue'        => $hsl[0],
                    'saturation' => $hsl[1] * 100,
                    'lightness'  => $hsl[2] * 100,
                    'hex'        => $hex
                ]);
            }
        }

        // Save Preview
        $svg = '<?xml version="1.0" encoding="UTF-8" standalone="no" ?>' . $theme->toSVG();
        $filename = 'public/previews/' . md5(time()) . '.png';

        // Fix SVG Errors
        $svg = str_replace("font-family=\"'ArialMT'\"", "", $svg);
        $svg = str_replace("font-family=\"'Arial-BoldMT'\"", "", $svg);

        $image = new Imagick();

        $image->setRegistry('temporary-path', storage_path('app/tmp'));

        $image->setBackgroundColor(new ImagickPixel('transparent'));
        $image->setSize(500, 370);
        $image->readImageBlob($svg);
        $image->setImageFormat("png32");
        $image->resizeImage(500, 370, imagick::FILTER_LANCZOS, 1);

        Storage::put($filename, $image);

        $image->clear();

        if($theme->preview){
            Storage::delete($theme->preview);
        }

        $theme->update([
            'preview' => $filename
        ]);

        return [
            'success' => true,
            'id'      => $theme->id,
            'url'     => $theme->url
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
        //
    }
}
