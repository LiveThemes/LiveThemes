<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $now = \Carbon\Carbon::now();

        $videos = Video::orderBy('created_at', 'desc')->get();

        return view('admin.video-list', compact('videos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.video-create');
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
            'title'       => 'required|string',
            'description' => 'required|string',
            'url'         => 'required|url',
            'embed_url'   => 'required|url'
        ]);

        Video::create([
            'title' => request('title'),
            'description' => request('description'),
            'url' => request('url'),
            'embed_url' => request('embed_url')
        ]);

        return redirect( '/--admin/videos' );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function destroy(Video $video)
    {
        $validate = $request->validate([
            'id' => 'required|integer'
        ]);

        Video::findorfail( request('id') )->delete();

        return redirect( '/--admin/videos' );
    }
}
