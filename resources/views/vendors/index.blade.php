@extends('layouts.app', ['title' => trans('words.suppliers')])

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
				<a href="{{ route('vendors.index') }}">
					<span class="icon is-small"><i class="fa fa-truck"></i></span>
					<span>{{ trans('words.suppliers') }}</span>
				</a>
			</li>
		</ul>
	</nav>
@endsection

@section('content')

	<div class="columns">
		<div class="column is-4">
			<p class="title is-6">
				<b>{{ trans('words.inactive') }} {{ trans('words.supplier') }}</b>
			</p>

			<div class="notification is-warning">
				<p class="subtitle is-7">
					<b>{{ $inactiveVendors }}</b>
				</p>
			</div>
		</div>

		<div class="column is-4">
			<p class="title is-6">
				<b>{{ trans('words.active') }} {{ trans('words.supplier') }}</b>
			</p>

			<a href="{{ route('vendors.all') }}">
				<div class="notification is-success">
					<p class="subtitle is-7">
						<b>{{ $activeVendors->count() }}</b>
					</p>
				</div>
			</a>
		</div>
	</div>

	<vendors :can-create.number="{{ Auth::user()->can('create-vendor') ? '1' : '0' }}"></vendors>

	<div class="columns">
			<div class="column">
				<table class="table is-fullwidth is-narrow is-size-7">
					<colgroup>
						<col style="width:20%;">
						<col style="width:20%;">
						<col style="width:65%;">
					</colgroup>
					<thead>
					<tr>
						<th>Name</th>
						<th class="has-text-left">Telephone Number</th>
						<th class="has-text-center">Web Site</th>
						<th></th>
					</tr>
					</thead>
					<tbody>
					@foreach ($activeVendors as $vendor)
								<tr>
									<td>{{ $vendor->name }}</td>
									<td class="has-text-left">{{ $vendor->telephone_number }}</td>
									<td class="has-text-left">{{ $vendor->website }}</td>
									<td class="has-text-right">
									<a href="{{ route('vendors.edit', ['id' => $vendor->id]) }}" class="button is-small is-grey">
										Edit
									</a>
								    </td>
									</tr>
					@endforeach
				</tbody>
			</table>

@endsection
 