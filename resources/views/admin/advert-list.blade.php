@extends('layouts.app')

@section('content')
<div class="container">

	<div class="card">
		<div class="card-body">
			<h5 class="card-title mb-0">
				<div class="float-right">
					<a href="/--admin/adverts/create" class="btn btn-primary btn-xs">Add</a>
				</div>
				Adverts
			</h5>
		</div>
		<table class="table">
			<thead>
				<tr>
					<th>Image</th>
					<th>From</th>
					<th>To</th>
					<th>Alt</th>
					<td>Clicks</td>
					<th>Link</th>
					<th></th>
				</tr>
			</thead>
			@foreach($adverts as $a)
			<tr>
				<td class="align-middle"><img src="{{ url('/storage/products/' . $a->image) }}" width="150"></td>
				<td class="align-middle">{{ $a->from->format('d/m/Y') }}</td>
				<td class="align-middle">{{ $a->to->format('d/m/Y') }}</td>
				<td class="align-middle">{{ $a->alt }}</td>
				<td class="align-middle">{{ $a->clicks_count }}</td>
				<td class="align-middle">
					<a href="{{ $a->url }}" target="_blank" class="btn btn-primary btn-xs">View</a>
				</td>
				<td class="align-middle">
					<form action="/--admin/adverts/destroy" method="post">
						@csrf
						<input type="hidden" name="id" value="{{ $a->id }}">
						<button type="submit" class="btn btn-primary btn-xs">Delete</button>
					</form>
				</td>
			</tr>
			@endforeach
		</table>
	</div>

</div>
@endsection
