@extends('layouts.app')

@section('content')
<div id="charts" class="charts">

	<div class="text-center mb-3">
		<div class="btn-group" role="group">
			<button type="button" v-on:click="time = 'week'" class="btn" v-bind:class="{ 'btn-primary': time == 'week' }">This Week</button>
			<button type="button" v-on:click="time = 'month'" class="btn" v-bind:class="{ 'btn-primary': time == 'month' }">This Month</button>
			<button type="button" v-on:click="time = 'all'" class="btn" v-bind:class="{ 'btn-primary': time == 'all' }">All Time</button>
		</div>
	</div>

	<div class="p-3" v-cloak v-bind:style="{ opacity: loading ? 0.7 : 1 }">
		<div class="row">

			<div class="col-sm-2">

				<div class="mb-3">
					<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
					<!-- Standard Square Ad -->
					<ins class="adsbygoogle"
					     style="display:block"
					     data-ad-client="ca-pub-9153901419175702"
					     data-ad-slot="8426225716"
					     data-ad-format="auto"
					     data-full-width-responsive="true"></ins>
					<script>
					     (adsbygoogle = window.adsbygoogle || []).push({});
					</script>
				</div>

				<div class="products pt-0" v-cloak>

					<div class="product" v-for="p in products1">
						<a v-bind:href="'/products/' + p.id" target="blank">
							<load-image>
								<img slot="image" v-bind:src="'/storage/products/' + p.image" v-bind:alt="p.alt">
								<div slot="preloader">
									<i class="fal fa-spinner fa-spin"></i>
								</div>
			      				<div slot="error">
			      					<i class="fal fa-image-polaroid"></i>
			      				</div>
							</load-image>
						</a>
					</div>

				</div>

			</div>

			<div class="col-sm-4">

				<h3 class="text-center mb-3">Most Downloaded</h3>

				<div class="charts-loader" v-if="loading">
					<svg width="300" height="300" version="1.1" id="L9" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">
						<path fill="#fc6767" d="M73,50c0-12.7-10.3-23-23-23S27,37.3,27,50 M30.9,50c0-10.5,8.5-19.1,19.1-19.1S69.1,39.5,69.1,50">
							<animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="1s" from="0 50 50" to="360 50 50" repeatCount="indefinite"></animateTransform>
						</path>
					</svg>
				</div>

				<div class="text-center text-muted" v-if="!loading && mostDownloaded.length == 0">
					No themes have been downloaded this @{{ time }}
				</div>

				<ul class="chart">

					<li v-for="(t,ti) in mostDownloaded">
						<a v-bind:href="t.url">
							<div class="preview">
								<load-image>
									<img slot="image" v-bind:src="t.preview ? t.preview.replace('public/','/storage/') : ''" v-bind:alt="t.name + ' Theme for Ableton 10'">
									<div slot="preloader">
										<i class="fal fa-spinner fa-spin"></i>
									</div>
				      				<div slot="error">
				      					<i class="fal fa-image-polaroid"></i>
				      				</div>
								</load-image>
							</div>
						</a>
						<div class="number">@{{ ti + 1 }}</div>
						<div class="text">
							<div class="name">@{{ t.name }}</div>
							<div class="author">by <span>@{{ t.author.name }}</span></div>
							<div class="count">
								<i class="fas fa-download"></i>@{{ t.downloads_count }}
							</div>
						</div>
					</li>

				</ul>

			</div>

			<div class="col-sm-4">

				<h3 class="text-center mb-3">Highest Rated</h3>

				<div class="charts-loader" v-if="loading">
					<svg width="300" height="300" version="1.1" id="L9" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">
						<path fill="#fc6767" d="M73,50c0-12.7-10.3-23-23-23S27,37.3,27,50 M30.9,50c0-10.5,8.5-19.1,19.1-19.1S69.1,39.5,69.1,50">
							<animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="1s" from="0 50 50" to="360 50 50" repeatCount="indefinite"></animateTransform>
						</path>
					</svg>
				</div>

				<div class="text-center text-muted" v-if="!loading && highestRated.length == 0">
					No themes have been rated this @{{ time }}
				</div>

				<ul class="chart">

					<li v-for="(t,ti) in highestRated">
						<a v-bind:href="t.url">
							<div class="preview">
								<load-image>
									<img slot="image" v-bind:src="t.preview ? t.preview.replace('public/','/storage/') : ''" v-bind:alt="t.name + ' Theme for Ableton 10'">
									<div slot="preloader">
										<i class="fal fa-spinner fa-spin"></i>
									</div>
				      				<div slot="error">
				      					<i class="fal fa-image-polaroid"></i>
				      				</div>
								</load-image>
							</div>
						</a>
						<div class="number">@{{ ti + 1 }}</div>
						<div class="text">
							<div class="name">@{{ t.name }}</div>
							<div class="author">by <span>@{{ t.author.name }}</span></div>
							<div class="count">
								<i class="fas fa-star"></i>@{{ t.average_rating }} (@{{ t.ratings_count }})
							</div>
						</div>
					</li>

				</ul>

			</div>

			<div class="col-sm-2">

				<div class="mb-3">
					<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
					<!-- Standard Square Ad -->
					<ins class="adsbygoogle"
					     style="display:block"
					     data-ad-client="ca-pub-9153901419175702"
					     data-ad-slot="8426225716"
					     data-ad-format="auto"
					     data-full-width-responsive="true"></ins>
					<script>
					     (adsbygoogle = window.adsbygoogle || []).push({});
					</script>
				</div>

				<div class="products pt-0" v-cloak>

					<div class="product" v-for="p in products2">
						<a v-bind:href="'/products/' + p.id" target="blank">
							<load-image>
								<img slot="image" v-bind:src="'/storage/products/' + p.image" v-bind:alt="p.alt">
								<div slot="preloader">
									<i class="fal fa-spinner fa-spin"></i>
								</div>
			      				<div slot="error">
			      					<i class="fal fa-image-polaroid"></i>
			      				</div>
							</load-image>
						</a>
					</div>

				</div>

			</div>

		</div>
	</div>
</div>

<div class="container">
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


@endsection

@section('pagescript')
<script type="text/javascript" src="{{ mix('js/charts.js') }}"></script>
@endsection
