@extends('layouts.app')

@section('pageClass', 'body body-contact')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 pt-5">

        	<h1>Contact</h1>

            @if(Session::has('success'))
            <p class="alert alert-success">{{ Session::get('success') }}</p>

            @else

        	<h4>Loving Live Themes? Want to let us know about a bug you've found? Got an idea for a new feature?</h4>

        	<form method="POST" action="/contact" class="pt-4">
                @csrf

                <input type='hidden' name='recaptcha_token' id='recaptcha_token'>

				@if($errors->has('recaptcha_token'))
				    {{$errors->first('recaptcha_token')}}
				@endif

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

                <div class="form-group">
                    <label for="email" class="mb-3">{{ __('Message') }} *</label>

                    <div>
                        <textarea id="message" type="message" style="height: 120px;" class="form-control @error('message') is-invalid @enderror" name="message" required>{{ old('message') }}</textarea>

                        @error('message')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <div>
                        <button type="submit" class="btn btn-lg btn-primary">
                            {{ __('Send') }}
                        </button>
                    </div>
                </div>

                <span class="text-small">* Required Fields</span>

            </form>

            @endif

        </div>
    </div>
</div>

@endsection

@section('pagescript')
	<script src="https://www.google.com/recaptcha/api.js?render={{ env('GOOGLE_CAPTCHA_PUBLIC_KEY') }}"></script>
	<script>
	grecaptcha.ready(function() {
		grecaptcha.execute('{{ env('GOOGLE_CAPTCHA_PUBLIC_KEY') }}').then(function(token) {
			document.getElementById("recaptcha_token").value = token;
		});
	});
	</script>
@endsection
