@extends('layouts.app')

@section('content')
<div class="container">

	<div class="card mb-4">
		<div class="card-body">

			<h5 class="card-title mb-3">
				Import Theme
			</h5>

			<form action="/--admin/elements/import" method="post" enctype="multipart/form-data">
				@csrf

				<div class="form-group">
					<label>Theme File</label><br>
					<input name="theme" type="file">

					@error('image')
					    <div class="alert alert-danger">{{ $message }}</div>
					@enderror
				</div>

				<button class="btn btn-primary" type="submit">
					Import
				</button>

			</form>

		</div>
	</div>

	<div class="card">
		<div class="card-body">
			<h5 class="card-title mb-0">
				Elements
			</h5>
		</div>
		<table class="table">
			<thead>
				<tr>
					<th>Element</th>
					<th>Name</th>
					<th>Description</th>
					<th>Version</th>
					<th></th>
				</tr>
			</thead>
			@foreach($elements as $e)
			<tr>
				<td>{{ $e->element }}</td>
				<td>{{ $e->name }}</td>
				<td>{{ $e->description }}</td>
				<td>{{ $e->min_version }}</td>
				<td>
					<a href="/--admin/elements/{{ $e->id }}" class="btn btn-secondary btn-sm">Edit</a>
				</td>
			</tr>
			@endforeach
		</table>
	</div>

</div>
@endsection
