@extends('layouts.app')

@section('pageClass', 'body body-contact')

@section('content')
<div id="account" class="container">

    <div class="row justify-content-center">
        <div class="col-md-8 pt-5">

        	<h1>Your Details</h1>

            @if(Session::has('details'))
            <p class="alert alert-success">{{ Session::get('details') }}</p>
            @endif

        	<form method="POST" action="/account/details" class="pt-4">
                @csrf

                <div class="form-group">
                    <label for="name">Name</label>

                    <div>
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $user->name) }}" required autocomplete="name" autofocus>

                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>

                    <div>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}" required autocomplete="email">

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <div>
                        <button name="details" type="submit" class="btn btn-lg btn-primary">
                            {{ __('Update') }}
                        </button>
                    </div>
                </div>

            </form>

        </div>
    </div>


    <div class="row justify-content-center">
        <div class="col-md-8 pt-5">

            <h1 class="mb-1">Password</h1>

            @if(Session::has('password'))
            <p class="alert alert-success">{{ Session::get('password') }}</p>
            @endif

            <form method="POST" action="/account/password" class="pt-4">
                @csrf

                <div class="alert alert-info mb-5">
                    Your new password must be at least <strong>8 characters</strong> in length and include an <strong>an uppercase letter</strong>, <strong>a lowercase letter</strong> and <strong>a number</strong>.
                </div>

                <div class="form-group">
                    <label for="current_password">{{ __('Current Password') }} *</label>

                    <div>
                        <input id="current_password" type="password" class="form-control @error('current_password') is-invalid @enderror" name="current_password" required autocomplete="password">

                        @error('current_password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="new_password">{{ __('New Password') }} *</label>

                    <div>
                        <input id="new_password" type="password" class="form-control @error('new_password') is-invalid @enderror" name="new_password" required autocomplete="new_password">

                        @error('new_password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="new_password_confirm">{{ __('Confirm New Password') }} *</label>

                    <div>
                        <input id="new_password_confirm" type="password" class="form-control" name="new_password_confirm" required autocomplete="new-password">
                    </div>
                </div>

                <div class="form-group">
                    <div>
                        <button name="password" type="submit" class="btn btn-lg btn-primary">
                            {{ __('Update') }}
                        </button>
                    </div>
                </div>

            </form>

        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8 pt-5">

            <h1>Delete Account</h1>

            <div class="alert alert-danger mb-3">
                Clicking below will delete your account and all your themes.
            </div>

            <form ref="deleteAccount" method="POST" action="/account/delete" class="pt-4">
                @csrf

                <div class="form-group">
                    <div>
                        <button v-on:click.prevent="deleteAccount" name="delete" type="submit" class="btn btn-lg btn-primary">
                            Delete
                        </button>
                    </div>
                </div>

            </form>

        </div>
    </div>

</div>

@endsection

@section('pagescript')
<script type="text/javascript" src="{{ mix('js/account.js') }}"></script>
@endsection
