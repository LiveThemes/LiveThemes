<?php

namespace App\Http\Controllers\Admin;

use App\Advert;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdvertController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $now = \Carbon\Carbon::now();

        $adverts = Advert::where('from', '<=', $now)
                            ->where('to', '>', $now)
                            ->withCount('clicks')
                            ->orderBy('from')
                            ->get();

        return view('admin.advert-list', compact('adverts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.advert-create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'from'  => 'required|date_format:Y-m-d',
            'to'    => 'required|date_format:Y-m-d',
            'image' => 'required|image',
            'url'   => 'required|url',
            'alt'   => 'required|string'
        ]);

        $path = $request->file('image')->store('public/products');

        Advert::create([
            'from'  => \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", request('from') . ' 00:00:00'),
            'to'    => \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", request('to') . ' 00:00:00'),
            'image' => str_replace('public/products/','', $path),
            'url'   => request('url'),
            'alt'   => request('alt')
        ]);

        return redirect( '/--admin/adverts' );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Advert  $advert
     * @return \Illuminate\Http\Response
     */
    public function show(Advert $advert)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Advert  $advert
     * @return \Illuminate\Http\Response
     */
    public function edit(Advert $advert)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Advert  $advert
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Advert $advert)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Advert  $advert
     * @return \Illuminate\Http\Response
     */
    public function destroy(Advert $advert, Request $request)
    {
        $validate = $request->validate([
            'id' => 'required|integer'
        ]);

        $advert = Advert::findorfail( request('id') );

        if( $advert->image ){
            unlink( storage_path('app/public/products/' . $advert->image) );
        }

        $advert->delete();

        return redirect( '/--admin/adverts' );
    }
}
