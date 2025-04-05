@extends('layouts.app')

@section('pageTitle', $theme->name . ' - Ableton 10 Theme by ' . app('profanityFilter')->filter($theme->author->name) )
@section('pageDescription', $theme->name . ' - Ableton 10 theme by ' . app('profanityFilter')->filter($theme->author->name) )
@section('pageImage', url( str_replace('public/', '/storage/', $theme->preview) ) )
@section('pageImageWidth', 500)
@section('pageImageHeight', 370)
@section('pageClass', 'page-theme')

@section('content')
<div id="theme">

	<div class="theme-preview" v-bind:class="{ full: showFullPreview }" v-cloak>

		@include('partials.preview')

		<div class="show-full">
			<i class="fas fa-arrow-circle-up" v-bind:class="{ 'rotate': showFullPreview }" v-on:click="showFullPreview = !showFullPreview"></i>
		</div>

	</div>

	<div class="annoucement">
		<a href="/help/ableton-11-compatibility">
			<strong>Ableton 11 Compatibility</strong> - Can you use these themes with Ableton 11?
		</a>
	</div>

	@if($theme->public == 0)
	<div class="theme-private">
		<i class="fas fa-lock"></i>This theme is private!
	</div>
	@endif

	<div class="theme-description">

		<div class="actions">

			@if(!Auth::guest() && auth()->user()->id == 1)
			<a href="{{ $theme->url }}/feature" class="btn btn-primary">
				<i class="{{ $theme->featured ? 'fas' : 'fal' }} fa-star"></i>Feature
			</a>
			@endif

			@if(!Auth::guest() && $theme->author_user_id == auth()->user()->id)
			<a href="/editor/{{ $theme->id }}" class="btn btn-primary">
				<i class="far fa-pencil"></i>Edit
			</a>
			@endif

			<a href="#" v-on:click.prevent="downloadTheme()" class="btn btn-primary">
				<i class="far fa-file-download"></i>Download
			</a>

			<a href="#" v-on:click.prevent="dialog.instructions = true" class="btn btn-secondary">
				<i class="fas fa-question"></i>How To Install
			</a>

			<a href="{{ $theme->url }}/remix" class="btn btn-secondary">
				<i class="far fa-retweet"></i>Remix
			</a>

			@if($theme->public == 1)
			<a v-on:click.prevent="dialog.share = true" class="btn btn-secondary">
				<i class="far fa-share"></i>Share
			</a>
			@endif

		</div>

		<div class="information">

			<div class="information-row">

				<div class="information-title">

					@if($theme->updated)
					<div class="text-small mb-2">
						<i class="fas fa-exclamation-triangle text-primary pr-2"></i>This theme has been updated & may look slightly different - <a href="/help/transparency-issues" target="_blank">Read More</a>
					</div>
					@endif

					<h1 class="name">{{ $theme->name }}</h1>
					<h4 class="author">by <span>{{ $theme->author->name }}</span></h4>

					<div class="stars" v-cloak>

						@if(!Auth::guest())
						<i v-for="i in 5" class="fa-star" v-bind:class="{ current: i <= rateStars, fas: i <= theme.average_rating, fal: i > theme.average_rating }" v-on:click="rateTheme(i)" v-on:mouseover="rateStars = i" v-on:mouseleave="rateStars = 0"></i>
						@else
						<i v-for="i in 5" class="fa-star" v-bind:class="{ current: i <= rateStars, fas: i <= theme.average_rating, fal: i > theme.average_rating }"></i>
						@endif

						<span v-html="theme.average_rating.toFixed(1) + ' avg'" v-if="!rated"></span>

						<transition name="fade">
							<span class="text-primary" v-if="rated">Thanks!</span>
						</transition>

					</div>

					<div class="downloads">
						<i class="fas fa-file-download"></i><strong>{{ $theme->downloads_count }}</strong> Downloads
					</div>

				</div>

				@if($theme->remixedFrom)
				<div class="information-remixed">

					<h6>
						<i class="far fa-retweet pr-1"></i>Remixed From
					</h6>

					<div class="theme">

						<a href="{{ $theme->remixedFrom->url }}">

							<div class="content">
								<h4>{{ $theme->remixedFrom->name }}</h4>
								<h5>by <span>{{ $theme->remixedFrom->author->name }}</span></h5>
							</div>

							<div class="preview">
								<div class="preview-image">
									<img slot="image" src="{{ str_replace('public/', '/storage/', $theme->remixedFrom->preview) }}" alt="{{ $theme->remixedFrom->name }} Theme for Ableton 10">
								</div>
							</div>

						</a>

					</div>

				</div>
				@endif

			</div>

			<div class="comments">

				@if(!Auth::guest())
				<div class="add">
					<textarea class="form-control" placeholder="Add a comment..." v-model="comment" v-bind:disabled="loading"></textarea>
					<button class="btn btn-primary" v-on:click="addComment()" v-bind:disabled="!comment || loading">
						Add Comment
					</button>
				</div>
				@endif

				<h4>Comments</h4>

				<div class="comment" v-for="c in comments" v-if="comments" v-cloak>
					<div class="meta">
						<i class="fal fa-user pr-2"></i>
						@{{ c.user.name }}
						<span class="date">@{{ c.created_at_ago }}</span>
						<a href="#" v-on:click.prevent="dialog.delete = c.id" v-if="user && c.user_id == user.id">
							<i class="fas fa-trash pr-2"></i>Delete
						</a>
					</div>
					<div class="text">
						@{{ c.comment }}
					</div>
				</div>

				<div class="comment" v-if="comments.length == 0">
					No Comments
				</div>

			</div>

			<div class="tips">

				<h2>
					<span>#</span>AbletonTips
				</h2>

				<div class="d-flex align-content-center">
					<div>
						<iframe width="320" height="180" src="{{ $video->embed_url }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
					</div>
					<div class="flex-fill align-self-center pl-4">
						<h3>{{ $video->title }}</h3>
						<p>{{ $video->description }}</p>
					</div>
				</div>

			</div>

		</div>

		<div class="products">

			@foreach($adverts as $a)
			<div class="product">
				<a href="{{ $a->url }}" target="blank">
					<img src="/storage/products/{{ $a->image }}">
				</a>
			</div>
			@endforeach

		</div>

		<form ref="download" method="post" action="{{ $theme->url }}/download">
			@csrf
			<input type="hidden" name="theme_id" value="{{ $theme->id }}">
		</form>

		<transition name="fade">
			<div class="dialog dialog-share" v-if="dialog.share" v-on:click="dialog.share = false" v-cloak>

				<div class="dialog-inner text-left">

					<i class="fal fa-times close"></i>

					<h2 class="mb-4">Share</h2>

					<div class="icons">
						<i v-on:click.stop="shareTwitter()" class="fab fa-twitter"></i>
						<i v-on:click.stop="shareFacebook()" class="fab fa-facebook"></i>
						<i v-on:click.stop="shareReddit()" class="fab fa-reddit"></i>
					</div>

					<div class="link">
						<input id="theme-link" v-on:click.stop="copyLink()" type="text" readonly="readonly" value="{{ url($theme->url) }}">
						<span class="link-copied" v-if="linkCopied">
							<i class="fal fa-check pr-2"></i>Link copied to clipboard!
						</span>
					</div>

				</div>

			</div>
		</transition>

		<transition name="fade">
			<div class="dialog dialog-lg dialog-download" v-if="dialog.download" v-on:click="dialog.download = false" v-cloak>

				<div class="dialog-inner text-left">

					<i class="fal fa-times close"></i>

					<h2 class="mb-4">Preparing your download</h2>

					<div class="progress mb-4">
						<div class="progress-bar" role="progressbar" v-bind:style="{ width: download.percent + '%' }" aria-valuenow="download.percent" aria-valuemin="0" aria-valuemax="100"></div>
					</div>

					<div class="row">
						@foreach($downloadAds as $a)
						<div class="col-sm-6">
							<div class="product">
								<a href="{{ $a->url }}" target="blank">
									<img src="/storage/products/{{ $a->image }}">
								</a>
							</div>
						</div>
						@endforeach
					</div>

				</div>

			</div>
		</transition>

		<transition name="fade">
			<div class="dialog dialog-error" v-if="dialog.error" v-on:click="dialog.error = false" v-cloak>

				<div class="dialog-inner text-left">

					<i class="fal fa-times close"></i>

					<h2 class="mb-4">Oops</h2>

					<p>@{{ dialog.error }}</p>

				</div>

			</div>
		</transition>

		<transition name="fade">
			<div class="dialog dialog-delete" v-if="dialog.delete" v-on:click="dialog.delete = false" v-cloak>

				<div class="dialog-inner text-center">

					<p>Are you sure you want to delete this comment?</p>

					<div>
						<button type="button" class="btn btn-primary mr-2" v-on:click="deleteComment()">
							Yes
						</button>
						<button class="btn btn-secondary" v-on:click="dialog.delete = false">
							No
						</button>
					</div>

				</div>

			</div>
		</transition>

		<transition name="fade">
			<div class="dialog dialog-instructions" v-if="dialog.instructions" v-on:click="dialog.instructions = false" v-cloak>

				<div class="dialog-inner text-left">

					<i class="fal fa-times close"></i>

					<h2 class="mb-4">Install Instructions</h2>

					<div class="video">
						<iframe width="100%" height="400" src="https://www.youtube.com/embed/G7RpM7mF0vE" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
					</div>

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
		</transition>

	</div>

</div>

<style>
	.theme-preview{
		background-color: {{ $theme->elementToRGBAString($theme->elements->RetroDisplayForeground) }};
	}
	.Preferences,
	.Automation,
	.Session{
		display: none;
	}
	@for($i = 1; $i <= 9; $i++)
	.Opacity{{ $i }}0 {
		opacity: 0.{{ $i }};
	}
	@endfor
	h1{
		border-color: {{ $theme->elementToRGBAString($theme->elements->RetroDisplayForeground) }} !important;
	}
	{{ $theme->css(true) }}
</style>
@endsection

@section('pagescript')
<script type="text/javascript">
	window.theme = {
		id: {{ $theme->id }},
		name: "{{ addslashes($theme->name) }}",
		url: "{{ url($theme->url) }}",
		average_rating: {{ $theme->average_rating }}
	};
</script>
<script type="text/javascript" src="{{ mix('js/theme.js') }}"></script>
@endsection
