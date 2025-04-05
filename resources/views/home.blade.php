@extends('layouts.app')

@section('pageClass', 'body body-home')

@section('content')
<div id="home" class="home">

	<div class="row-first" v-cloak>

		@include('partials.home-preview')

		<div class="title">
			<span class="pl-2 pr-2">Live Themes</span>

			<div class="sub">
				v2.0
			</div>
			<a class="btn btn-primary strap" href="#" v-on:click.prevent="dialog.features = true">
				<i class="far fa-play mr-3"></i>Check out the new features!
			</a>
		</div>

		<div class="author" v-if="theme">
			<span class="theme-name">@{{ theme.name }}</span><br>
			<span class="author-name"><span>by</span> @{{ theme.author.name }}</span>
		</div>

	</div>

	<div class="row-second">

		<div class="row">

			<div class="col-sm-4 mb-5 mb-sm-0">
				<h2><span>_</span>Browse</h2><br>
				<p>Browse through thousands of free Ableton 10 themes & filter by lightness, colour and more.</p>
				<a href="/browse" class="btn btn-primary">
					Browse
				</a>
			</div>

			<div class="col-sm-4 mb-5 mb-sm-0">
				<h2><span>_</span>Editor</h2><br>
				<p>Create your own themes for Ableton 10 from scratch with our editor.</p>
				<a href="/browse" class="btn btn-primary">
					Editor
				</a>
			</div>

			<div class="col-sm-4">
				<h2><span>_</span>Remix</h2><br>
				<p>Take any theme you've found on the site and remix it to tailor it to your taste.</p>
				<a href="/browse" class="btn btn-primary">
					Remix
				</a>
			</div>

		</div>

	</div>

	<div class="pl-5 pr-5 mb-2">
		<div class="row">
			@foreach($adverts as $a)
			<div class="col-sm-3">
				<a href="{{ $a->url }}" target="_blank">
					<img src="/storage/products/{{ $a->image }}" class="mb-2" style="width:100%; height:auto">
					<h5>{{ $a->alt }}</h5>
				</a>
			</div>
			@endforeach
		</div>
	</div>

	<div class="container">
		<div class="row-second pb-2">

			<h2 class="mb-5">
				<span>_</span>How To Install Themes In Ableton 10
			</h2>

			<div class="row">
				<div class="col-sm-6">
					<h5>
						<i class="fab fa-apple pr-2"></i>Mac
					</h5>
					<p>Find the <strong>Ableton 10</strong> application in your <span class="text-italic">Applications</span> folder, right click on it and select <strong>Show Package Contents</strong>. Then inside the app, head into <span class="text-italic">Content &gt; App Resources &gt; Themes</span> and drop the theme in there.</p>
				</div>
				<div class="col-sm-6">
					<h5>
						<i class="fab fa-windows pr-2"></i>Windows
					</h5>
					<p>Drop the theme into <span class="text-italic">C:\ProgramData\Ableton\Live 10 Suite\Resources\Themes</span>.</p>
				</div>
			</div>

			<p>Go to <strong>Ableton</strong> and open the <span class="text-italic">Preferences</span> window, if it's open already close and reopen. The theme will then be under the <strong>Look/Feel</strong> tab.</p>

		</div>
	</div>

	<transition name="fade">
		<div class="dialog dialog-lg dialog-minimal dialog-features" v-if="dialog.features" v-on:click="dialog.features = false" v-cloak>

			<div class="dialog-inner text-left">

				<iframe width="100%" height="400" src="https://www.youtube.com/embed/zdKnCuChMnc" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>

			</div>

		</div>
	</transition>

	<theme-classes v-if="theme" v-cloak>
		@{{ theme.css }}
		.body-home .row-first .title .sub{
			background-color: rgb(@{{ theme.elements.RetroDisplayForeground.r }}, @{{ theme.elements.RetroDisplayForeground.g }}, @{{ theme.elements.RetroDisplayForeground.b }});
		}
		.body-home .row-first .title .btn-primary{
			background-color: rgb(@{{ theme.elements.RetroDisplayForeground.r }}, @{{ theme.elements.RetroDisplayForeground.g }}, @{{ theme.elements.RetroDisplayForeground.b }});
		}
		.body-home .row-first .author .theme-name{
			background-color: rgb(@{{ theme.elements.RetroDisplayForeground.r }}, @{{ theme.elements.RetroDisplayForeground.g }}, @{{ theme.elements.RetroDisplayForeground.b }});
		}
	</theme-classes>

</div>
@endsection

@section('pagescript')
	<script>
		var themes = {!! json_encode($themes) !!};
	</script>
	<script type="text/javascript" src="{{ mix('js/home.js') }}"></script>
@endsection

