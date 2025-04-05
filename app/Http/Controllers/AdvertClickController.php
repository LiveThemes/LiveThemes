<?php

namespace App\Http\Controllers;

use App\Advert;
use App\AdvertClick;
use Illuminate\Http\Request;

class AdvertClickController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $user = auth()->user();

        $advert = Advert::findorfail($id);

        AdvertClick::create([
            'advert_id' => $id,
            'user_id'   => $user ? $user->id : null
        ]);

        return redirect( $advert->url );
    }
}
