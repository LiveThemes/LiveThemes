<?php

namespace App\Http\Controllers;

use Mail;
use App\Mail\ContactForm;
use App\Rules\ReCaptchaRule;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return view('faq');
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
            'name' => 'required|string',
            'email' => 'required|email',
            'message' => 'required|string',
            'recaptcha_token' => [
                'required', new ReCaptchaRule(request('recaptcha_token'))
            ]
        ]);

        Mail::to('hello@livethemes.co')->send( new ContactForm(request('name'), request('email'), request('message')) );

        session()->flash('success', 'Thanks for sending us mail, we\'ll get back to you soon!');

        return redirect('/contact');
    }
}
