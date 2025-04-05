<?php

namespace App\Http\Controllers;

use Auth;
use App\Rules\MatchOldPassword;
use App\Theme;
use App\ThemeComment;
use App\ThemeRating;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();

        return view('account', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $user = auth()->user();

        $this->validate(request(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $user->id
        ]);

        User::find($user->id)->update([
            'name' => sanitize(request('name')),
            'email' => request('email')
        ]);

        session()->flash('details', 'Details updated!');

        return redirect('/account');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function password(Request $request, User $user)
    {
        $user = auth()->user();

        $this->validate(request(), [
            'current_password'     => ['required', new MatchOldPassword],
            'new_password'         => ['required', 'different:current_password', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],
            'new_password_confirm' => ['required', 'same:new_password']
        ],[
            'current_password.required' => "You haven't entered your current password",
            'new_password.required'     => "You haven't entered your new password",
            'new_password.different'    => "Your new password should not match your old password",
            'new_password.min'          => "Your password must be at least 8 characters long",
            'new_password.regex'        => "Your password must contain an uppercase letter, a lowercase letter and a number",
            'new_password_confirm.same' => "Your confirmed password doesn't match your new password"
        ]);

        User::find($user->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        session()->flash('password', 'Password updated!');

        return redirect('/account');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user = auth()->user();

        // Get Themes
        $themes = Theme::select('id', 'preview')
                        ->where('author_user_id', $user->id)
                        ->get();

        foreach($themes as $t){
            $t->delete();
        }

        ThemeComment::where('user_id', $user->id)->delete();

        ThemeRating::where('user_id', $user->id)->delete();

        User::find( $user->id )->delete();

        Auth::logout();

        return redirect('/');

    }
}
