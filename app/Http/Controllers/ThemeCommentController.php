<?php

namespace App\Http\Controllers;

use App\ThemeComment;
use Illuminate\Http\Request;

class ThemeCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($slug, $theme_id)
    {
        return ThemeComment::with(['user'])
                            ->where('theme_id', $theme_id)
                            ->orderBy('created_at', 'desc')
                            ->get();
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
        $this->validate(request(), [
            'id'      => 'required|integer',
            'comment' => 'required|string'
        ]);

        $user = auth()->user();

        $comment = sanitize(request('comment'));

        if( !$comment ){
            abort(500, 'Your comment was not valid!');
        }

        // Check latest comment
        $userComments = ThemeComment::where('user_id', $user->id)
                                        ->where('created_at', '>=', \Carbon\Carbon::now()->subMinute())
                                        ->count();

        if( !is_null($userComments) && $userComments > 0 ){
            abort(500, "Ok we get it, you love commenting but please wait at least a minute before commenting again.");
        }

        ThemeComment::create([
            'theme_id' => request('id'),
            'user_id'  => $user->id,
            'comment'  => $comment
        ]);

        return [
            'success' => true
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ThemeComment  $themeComment
     * @return \Illuminate\Http\Response
     */
    public function show(ThemeComment $themeComment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ThemeComment  $themeComment
     * @return \Illuminate\Http\Response
     */
    public function edit(ThemeComment $themeComment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ThemeComment  $themeComment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ThemeComment $themeComment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ThemeComment  $themeComment
     * @return \Illuminate\Http\Response
     */
    public function destroy(ThemeComment $themeComment)
    {
        $this->validate(request(), [
            'id'      => 'required|integer'
        ]);

        $comment = ThemeComment::where('id', request('id'))
                                ->where('user_id', auth()->user()->id)
                                ->first();

        if( is_null($comment) ){
            abort(500, 'There was an error deleting this comment!');
        }

        $comment->delete();

        return [
            'success' => true
        ];

    }
}
