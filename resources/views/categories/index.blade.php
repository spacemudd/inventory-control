@extends('layouts.app', ['title' => 'Categories'])

@section('header')
	<nav class="breadcrumb" aria-label="breadcrumbs">
		<ul>
			<li>
				<a href="{{ route('dashboard.index') }}" aria-current="page">
					<span class="icon is-small"><i class="fa fa-inbox"></i></span>
					<span>{{ trans('words.dashboard') }}</span>
				</a>
			</li>
			<li>
				<a href="{{ route('categories.index') }}">
					<span class="icon is-small"><i class="fa fa-bookmark"></i></span>
					<span>Categories</span>
				</a>
			</li>
		</ul>
	</nav>
@endsection

@section('content')

	<div class="columns">
		<div class="column is-12 has-text-right">
			<a href="{{ route('categories.create') }}" class="button is-primary">New category</a>
		</div>
	</div>

	<div class="columns">
		<div class="column is-12">
			<table class="table is-size-7 is-narrow is-fullwidth is-bordered is-">
			<thead>
			<colgroup>
				<col style="width:1px;">
				<col style="width:5rem">
			</colgroup>
			<tr>
				{{--<th style="width:1px;">ID</th>--}}
				<th>Name</th>
				{{--<th class="has-text-right">Actions</th>--}}
			</tr>
			</thead>
				<tbody>
					@foreach ($categories as $category)
						<tr>
							{{--<td style="width:1px;">{{ $category->id }}</td>--}}
							<td>{{ $category->name }}</td>
							{{--<td class="has-text-right">{{ $stock->on_hand_quantity }}</td>--}}
							{{--<td class="has-text-right">--}}
								{{--<a href="{{ route('stock.edit', ['id' => $stock->id]) }}" class="button is-small is-warning">--}}
									{{--Edit--}}
								{{--</a>--}}
							{{--</td>--}}
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
@endsection
