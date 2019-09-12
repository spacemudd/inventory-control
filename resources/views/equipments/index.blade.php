@extends('layouts.app', ['title' => 'Equipments'])

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
				<a href="{{ route('equipments.index') }}">
					<span class="icon is-small"><i class="fa fa-wrench"></i></span>
					<span>Equipments</span>
				</a>
			</li>
			@if (isset($selectedCategory))
				<li class="is-active">
					<a href="#">
						<span>{{ $selectedCategory->name }}</span>
					</a>
				</li>
			@endif
		</ul>
	</nav>
@endsection

@section('content')

	<div class="columns">
		<div class="column is-4">
			<p class="title is-6">
				@if (isset($selectedCategory))
					<b>{{ $selectedCategory->name }}</b>
				@else
					<b>Total Equipments</b>
				@endif
			</p>

			<a href="{{ route('equipments.index') }}">
				<div class="notification is-success">
					<p class="subtitle is-7">
						<b>{{ $equipments->count() }}</b>
					</p>
				</div>
			</a>
		</div>
	</div>

	<div class="columns">
		<div class="column">
           
			<simple-search
					:hyper-linked-results="true"
					placeholder-text="Search"
					size="is-small"
					search-endpoint="employees">
			</simple-search>
		</div>

	<div class="column is-3 has-text-right" style="padding-top:25px;">
			<a href="makeEquipmentExcel" class="button is-small is-success">
				<span class="icon"><i class="fa fa-file-excel-o"></i></span>
				<span>Excel</span>
			</a>
			<a id="new" href="{{route('equipments.create')}}" class="button is-small is-primary">New</a>
		</div>
	</div>
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
						<th class="has-text-left"></th>
						<th class="has-text-center"></th>
						<th></th>
					</tr>
					</thead>
					<tbody>
					@foreach ($equipments as $equip)
								<tr>
									<td>{{$equip->name}}</td>
									<td class="has-text-left"></td>
									<td class="has-text-left"></td>
									<td class="has-text-right">
									<!-- <a href="" class="button is-small is-grey">
										Edit
									</a> -->
								    </td>
									</tr>
					@endforeach
				</tbody>
			</table>
		

	@endsection
