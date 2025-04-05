@extends('layouts.app')

@section('content')

<div id="browse" class="browse-content">

	<div class="filters">

		<h2 class="text-primary">My Themes</h2>

		<input v-model="search" type="text" class="form-control" placeholder="Search">

		<div class="group">

			<h3>Visibility</h3>

			<label>
				<input v-model="visibility" type="radio" value="all"> All
			</label>

			<label>
				<input v-model="visibility" type="radio" value="public"> Public
			</label>

			<label>
				<input v-model="visibility" type="radio" value="private"> Private
			</label>

		</div>

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

			</a>

			<div class="content">

				<h5>
					<span>
						<i class="fas" v-bind:class="t.public == 1 ? 'fa-globe-americas' : 'fa-lock'"></i>
						<span class="text-upper" v-html="t.public == 1 ? 'Public' : 'Private'"></span>
					</span>
				</h5>

				<h4>@{{ t.name }}</h4>

				<ul class="meta">
					<li>
						<i class="fas fa-download"></i> @{{ t.downloads_count }}
					</li>
					<li>
						<i class="fas fa-star"></i> @{{ t.average_rating }}
					</li>
				</ul>

				<div class="drop">
					<i class="fas fa-ellipsis-v"></i>
					<ul>
						<li>
							<a v-bind:href="t.url">
								<i class="fas fa-eye"></i>View
							</a>
						</li>
						<li>
							<a v-on:click.prevent="toggleVisibility(t.id)" href="#">
								<i class="fas" v-bind:class="t.public ? 'fa-lock' : 'fa-globe-americas'"></i>Make <span v-html="t.public ? 'Private' : 'Public'"></span>
							</a>
						</li>
						<li>
							<a v-bind:href="'/editor/' + t.id">
								<i class="fas fa-pencil"></i>Edit
							</a>
						</li>
						<li>
							<a v-bind:href="t.url + '/remix'">
								<i class="fas fa-retweet"></i>Remix
							</a>
						</li>
						<li>
							<a v-on:click.prevent="deleteId = t.id" href="#">
								<i class="fas fa-trash"></i>Delete
							</a>
						</li>
					</ul>
				</div>

			</div>

		</div>

		<div class="none text-center pl-5 pr-5" v-if="(!themes.data || themes.data.length == 0) && !loading" v-cloak>
			<h2>You haven't made any themes yet, head over to the editor!</h2>
			<a href="/editor" class="btn btn-primary">
				Editor
			</a>
		</div>

		<ul class="pagination" v-if="themes.data && themes.total > 16" v-cloak>
			<li>
				<button type="button" class="btn btn-secondary" v-on:click.prevent="getThemes(1)">First</button>
			</li>
			<li v-if="themes.current_page > 1">
				<button type="button" class="btn btn-secondary" v-on:click.prevent="getThemes(themes.current_page - 1)">Prev</button>
			</li>
			<li v-for="p in paginationNumbers">
				<button type="button" class="btn" v-bind:class="p.current ? 'btn-primary' : 'btn-secondary'" v-bind:disabled="p.current" v-on:click.prevent="getThemes(p.name)">@{{ p.name }}</button>
			</li>
			<li v-if="themes.current_page < themes.last_page">
				<button type="button" class="btn btn-secondary" v-on:click.prevent="getThemes(themes.current_page + 1)">Next</button>
			</li>
			<li>
				<button type="button" class="btn btn-secondary" v-on:click.prevent="getThemes(themes.last_page)">Last</button>
			</li>
		</ul>

	</div>

	<div class="products" v-cloak>

		<div class="product" v-for="p in products">
			<a v-bind:href="p.url" target="blank">
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

	<transition name="fade">
		<div class="dialog dialog-delete" v-if="deleteId" v-cloak>

			<div class="dialog-inner" v-bind:class="{ loading: saving }">
				<p>Are you absolutely sure you want to delete this theme?</p>
				<button class="btn btn-primary mr-2" v-on:click="deleteTheme()">
					Yes
				</button>
				<button class="btn btn-secondary" v-on:click="deleteId = null">
					No
				</button>
			</div>

		</div>
	</transition>

</div>

@endsection

@section('pagescript')
<script type="text/javascript" src="{{ mix('js/my-themes.js') }}"></script>
@endsection
