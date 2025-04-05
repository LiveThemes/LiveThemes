@extends('layouts.app')

@section('content')
<div id="advert-create" class="container">

	<div class="card">
		<div class="card-body">

			<h5 class="card-title mb-4">
				<div class="float-right">
					<a href="/--admin/adverts" class="btn btn-primary btn-xs">Back</a>
				</div>
				Create Advert
			</h5>

			<form action="/--admin/adverts/store" method="post" enctype="multipart/form-data">
				@csrf

				<div class="form-group">
					<label>From</label>
					<input name="from" type="date" class="form-control @error('from') is-invalid @enderror" placeholder="DD/MM/YYYY" value="{{ old('from') }}">

					@error('from')
					    <div class="alert alert-danger">{{ $message }}</div>
					@enderror
				</div>

				<div class="form-group">
					<label>From</label>
					<input name="to" type="date" class="form-control @error('to') is-invalid @enderror" placeholder="DD/MM/YYYY" value="{{ old('to') }}">

					@error('to')
					    <div class="alert alert-danger">{{ $message }}</div>
					@enderror
				</div>

				<div class="form-group">
					<label>Image</label><br>
					<input name="image" type="file">

					@error('image')
					    <div class="alert alert-danger">{{ $message }}</div>
					@enderror
				</div>

				<div class="form-group">
					<label>URL</label>
					<input id="url" name="url" type="text" class="form-control @error('url') is-invalid @enderror" value="{{ old('url') }}">

					@error('url')
					    <div class="alert alert-danger">{{ $message }}</div>
					@enderror
				</div>

				<div class="form-group">
					<label>Alt</label>
					<input name="alt" type="text" class="form-control @error('alt') is-invalid @enderror" value="{{ old('alt') }}">

					@error('alt')
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
