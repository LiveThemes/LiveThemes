@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 pt-5">

            <h1>Create An Account</h1>

            <p>
                Already got an account? <a href="/login" class="btn btn-xs btn-primary ml-2">Login</a>
            </p>



            @if (\Session::has('error'))
                <div class="alert alert-danger">
                    {{ \Session::get('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="pt-4">
                @csrf

                <div class="form-group">
                    <label for="name">{{ __('Name') }} *</label>

                    <div>
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">{{ __('Email') }} *</label>

                    <div>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="alert alert-info mb-5">
                    Your new password must be at least <strong>8 characters</strong> in length and include an <strong>an uppercase letter</strong>, <strong>a lowercase letter</strong> and <strong>a number</strong>.
                </div>

                <div class="form-group">
                    <label for="password">{{ __('Password') }} *</label>

                    <div>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="password-confirm">{{ __('Confirm Password') }} *</label>

                    <div>
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                    </div>
                </div>

                <div class="form-group">
                    <div>
                        <button type="submit" class="btn btn-lg btn-primary">
                            {{ __('Register') }}
                        </button>
                    </div>
                </div>

                <span class="text-small">* Required Fields</span>

            </form>

        </div>
    </div>
</div>
@endsection
