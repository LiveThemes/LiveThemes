@extends('layouts.app')

@section('pageTitle', $pageTitle)

@section('content')

<div class="annoucement mb-2" style="margin-top: -1.5rem">
	<a href="/help/ableton-11-compatibility">
		<strong>Ableton 11 Compatibility</strong> - Can you use these themes with Ableton 11?
	</a>
</div>

<div id="browse" class="browse-content">

	<div class="filters">

		<h2 v-cloak><span id="themecount">0</span> Themes</h2>

		<input v-model="search" type="text" class="form-control" placeholder="Search">

		<div class="group">

			<h3>Sort</h3>

			<label>
				<input v-model="sort" type="radio" name="sort" value="featured"> Featured
			</label>

			<label>
				<input v-model="sort" type="radio" name="sort" value="newest"> Newest
			</label>

			<label>
				<input v-model="sort" type="radio" name="sort" value="oldest"> Oldest
			</label>

			<label>
				<input v-model="sort" type="radio" name="sort" value="most-downloaded"> Most Downloaded
			</label>

			<label>
				<input v-model="sort" type="radio" name="sort" value="least-downloaded"> Least Downloaded
			</label>

			<label>
				<input v-model="sort" type="radio" name="sort" value="highest-rated"> Highest Rated
			</label>

			<label>
				<input v-model="sort" type="radio" name="sort" value="lowest-rated"> Lowest Rated
			</label>

		</div>

		<div class="group">

			<h3>Brightness</h3>

			<label>
				<input v-model="brightness" type="radio" value="all"> All
			</label>

			<label>
				<input v-model="brightness" type="radio" value="dark"> Dark
			</label>

			<label>
				<input v-model="brightness" type="radio" value="mid"> Mid
			</label>

			<label>
				<input v-model="brightness" type="radio" value="light"> Light
			</label>

		</div>

		<div class="group">

			<h3>Colour</h3>

			<label>
				<input v-model="color" type="radio" value="all"> All
			</label>

			<label>
				<input v-model="color" type="radio" value="red"> Red
			</label>

			<label>
				<input v-model="color" type="radio" value="orange"> Orange
			</label>

			<label>
				<input v-model="color" type="radio" value="yellow"> Yellow
			</label>

			<label>
				<input v-model="color" type="radio" value="green"> Green
			</label>

			<label>
				<input v-model="color" type="radio" value="blue"> Blue
			</label>

			<label>
				<input v-model="color" type="radio" value="purple"> Purple
			</label>

		</div>

	</div>

	<div class="themes">

		<div class="theme" v-for="t in themes.data" v-if="themes.data" v-cloak v-bind:style="{ opacity: loading ? 0.5 : 1 }">

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

				<div class="content">

					<h4>@{{ t.name }}</h4>

					<h5>by <span>@{{ t.author.name }}</span></h5>

					<ul class="meta">
						<li>
							<i class="fas fa-download"></i> @{{ t.downloads_count }}
						</li>
						<li>
							<i class="fas fa-star"></i> @{{ t.average_rating }}
						</li>
					</ul>

				</div>

				<i class="featured fas fa-star" v-if="t.featured" title="Featured"></i>

			</a>

		</div>

		<div class="none" v-if="(!themes.data || themes.data.length == 0) && !loading" v-cloak>
			<h2>Oh darn, we can't find any themes!</h2>
		</div>

		<ul class="pagination" v-if="themes.data && themes.total > 16" v-cloak>
			<li class="first">
				<button type="button" class="btn btn-secondary" v-on:click.prevent="getThemes(1)">First</button>
			</li>
			<li class="prev" v-if="themes.current_page > 1">
				<button type="button" class="btn btn-secondary" v-on:click.prevent="getThemes(themes.current_page - 1)">Prev</button>
			</li>
			<li class="number" v-for="p in paginationNumbers">
				<button type="button" class="btn" v-bind:class="p.current ? 'btn-primary' : 'btn-secondary'" v-bind:disabled="p.current" v-on:click.prevent="getThemes(p.name)">@{{ p.name }}</button>
			</li>
			<li class="next" v-if="themes.current_page < themes.last_page">
				<button type="button" class="btn btn-secondary" v-on:click.prevent="getThemes(themes.current_page + 1)">Next</button>
			</li>
			<li class="last">
				<button type="button" class="btn btn-secondary" v-on:click.prevent="getThemes(themes.last_page)">Last</button>
			</li>
		</ul>

		<div class="tips" v-if="!loading" v-cloak>

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

	<div class="products pt-0" v-cloak>

		<div class="product" v-for="p in products">
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

@endsection

@section('pagescript')
<script type="text/javascript" src="{{ mix('js/browse.js') }}"></script>
@endsection
