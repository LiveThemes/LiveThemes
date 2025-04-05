<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\ThemeElement;
use Illuminate\Http\Request;

class ThemeElementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $elements = ThemeElement::all();

        return view('admin.theme-element-list', compact('elements'));
    }

    /**
     * Import Elements from Theme File
     */
    public function import(Request $request)
    {
        $validate = $request->validate([
            'theme'  => 'required|file|mimes:ask,xml,txt'
        ]);

        $xml = simplexml_load_file( $request->file('theme') );

        $xml = json_decode(json_encode($xml),TRUE);

        foreach($xml['SkinManager'] as $k => $v){

            if( isset($v['R']) ){

                $default = [
                    'r' => intval( $v['R']['@attributes']['Value'] ),
                    'g' => intval( $v['G']['@attributes']['Value'] ),
                    'b' => intval( $v['B']['@attributes']['Value'] ),
                    'a' => intval( $v['Alpha']['@attributes']['Value'] ),
                ];

            } else {

                $default = [
                    'value' => floatval( $v['@attributes']['Value'] )
                ];

            }

            ThemeElement::updateOrCreate([
                'element' => $k
            ],[
                'min_version' => 10,
                'default' => $default
            ]);

        }

        return redirect('/--admin/elements');

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
     * @param  \App\ThemeElement  $themeElement
     * @return \Illuminate\Http\Response
     */
    public function show(ThemeElement $themeElement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ThemeElement  $themeElement
     * @return \Illuminate\Http\Response
     */
    public function edit(ThemeElement $themeElement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ThemeElement  $themeElement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ThemeElement $themeElement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ThemeElement  $themeElement
     * @return \Illuminate\Http\Response
     */
    public function destroy(ThemeElement $themeElement)
    {
        //
    }
}
