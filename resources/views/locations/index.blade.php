@extends('layouts.app', [
	'title' => 'Locations',
])

@section('header')
	<nav class="breadcrumb" aria-label="breadcrumbs">
		<ul>
			<li>
				<a href="{{ route('dashboard.index') }}" aria-current="page">
					<span class="icon is-small"><i class="fa fa-inbox"></i></span>
					<span>{{ trans('words.dashboard') }}</span>
				</a>
			</li>
			<li class="is-active">
				<a href="{{ route('locations.index') }}">
					<span>Locations</span>
				</a>
			</li>
		</ul>
	</nav>
@endsection

@section('content')
	<div class="columns">
		<div class="column is-12 has-text-right">
			<a class="button is-primary" href="{{ route('locations.create') }}">New Location</a>
		</div>
	</div>
		<div class="columns">
			<div class="column">
				<table class="table is-fullwidth is-narrow is-size-7">
					<colgroup>
						<col style="width:5%;">
						<col style="width:15%">
						<col>
					</colgroup>
					<thead>
					<tr>
						<th>ID</th>
						<th>Location</th>
						<th></th>
					</tr>
					</thead>
					<tbody>
					@if($locations->count() == 0)
						<tr>
							<td class="has-text-centered" style="line-height:50px;" colspan="6">No data to display</td>
						</tr>
					@endif
					@foreach($locations as $record)
						<tr>
							<td>
								{{ $record->id }}
							</td>
							<td>
								{{ $record->name }}
							</td>
							<td class="has-text-right"></td>
						</tr>
					@endforeach
					</tbody>
				</table>
			</div>
		</div>

@endsection
