@extends('layouts.app')

@section('content')
<div class="container">

	<div class="card">
		<div class="card-body">
			<h5 class="card-title mb-0">
				<div class="float-right">
					<a href="/--admin/videos/create" class="btn btn-primary btn-xs">Add</a>
				</div>
				Videos
			</h5>
		</div>
		<table class="table">
			<thead>
				<tr>
					<th>Title</th>
					<th>URL</th>
				</tr>
			</thead>
			@foreach($videos as $v)
			<tr>
				<td>{{ $v->title }}</td>
				<td><a href="{{ $v->url }}">{{ $v->url }}</a></td>
			</tr>
			@endforeach
		</table>
	</div>

</div>
@endsection
