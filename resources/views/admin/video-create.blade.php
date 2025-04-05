@extends('layouts.app')

@section('content')
<div id="advert-create" class="container">

	<div class="card">
		<div class="card-body">

			<h5 class="card-title mb-4">
				<div class="float-right">
					<a href="/--admin/videos" class="btn btn-primary btn-xs">Back</a>
				</div>
				Create Video
			</h5>

			<form action="/--admin/videos/store" method="post">
				@csrf

				<div class="form-group">
					<label>Title</label>
					<input name="title" type="text" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}">

					@error('title')
					    <div class="alert alert-danger">{{ $message }}</div>
					@enderror
				</div>

				<div class="form-group">
					<label>Description</label>
					<input name="description" type="text" class="form-control @error('description') is-invalid @enderror" value="{{ old('description') }}">

					@error('description')
					    <div class="alert alert-danger">{{ $message }}</div>
					@enderror
				</div>

				<div class="form-group">
					<label>Url</label>
					<input name="url" type="text" class="form-control @error('url') is-invalid @enderror" value="{{ old('url') }}">

					@error('url')
					    <div class="alert alert-danger">{{ $message }}</div>
					@enderror
				</div>

				<div class="form-group">
					<label>Embed Url</label>
					<input name="embed_url" type="text" class="form-control @error('embed_url') is-invalid @enderror" value="{{ old('embed_url') }}">

					@error('embed_url')
					    <div class="alert alert-danger">{{ $message }}</div>
					@enderror
				</div>

				<button class="btn btn-primary" type="submit">
					Add
				</button>

			</form>

		</div>
	</div>

</div>
@endsection

@section('pagescript')
<script type="text/javascript" src="{{ mix('js/admin/advert-create.js') }}"></script>
@endsection
