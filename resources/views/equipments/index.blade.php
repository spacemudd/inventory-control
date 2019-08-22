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
			<stock-search></stock-search>
{{--			<simple-search--}}
{{--					:hyper-linked-results="true"--}}
{{--					placeholder-text="Search"--}}
{{--					size="is-small"--}}
{{--					search-endpoint="stock">--}}
{{--			</simple-search>--}}
{{--			<ul style="margin-top:20px;">--}}
{{--				@foreach (\App\Models\Category::get() as $category)--}}
{{--					<li style="display: inline;{{ $category->id===\App\Models\Category::get()->first()->id ? '' : 'margin-left:1rem;' }}">--}}

{{--						@if (isset($selectedCategory))--}}
{{--							@if ($category->id === $selectedCategory->id)--}}
{{--								<b>{{ $category->name }}</b>--}}
{{--							@else--}}
{{--								<a href="{{ route('stock.category', ['category_id' => $category->id]) }}">--}}
{{--									{{ $category->name }}--}}
{{--								</a>--}}
{{--							@endif--}}
{{--						@else--}}
{{--							<a href="{{ route('stock.category', ['category_id' => $category->id]) }}">--}}
{{--								{{ $category->name }}--}}
{{--							</a>--}}
{{--						@endif--}}
{{--					</li>--}}
{{--				@endforeach--}}
{{--			</ul>--}}
		</div>
		<div class="column is-3 has-text-right" style="padding-top:25px;">
			<a href="makeStockExcel" class="button is-small is-success">
				<span class="icon"><i class="fa fa-file-excel-o"></i></span>
				<span>Excel</span>
			</a>
			<a href="{{ route('stock.create') }}" class="button is-small is-primary">New stock</a>
		</div>
	</div>

	<div class="columns">
		<div class="column is-12">
			<b-tabs class="">
				<b-tab-item label="All">
					<table class="table is-size-7 is-narrow is-fullwidth is-bordered">
						<thead>
						<colgroup>
							<col style="width:3rem;">
							<col style="width:7rem">
							<col>
							<col style="width:6rem;">
							<col style="width:6rem;">
							<col style="width:6rem;">
							<col style="width:5rem;">
						</colgroup>
						<tr>
							<th>Code</th>
							<th>Category</th>
							<th>Description</th>
							@if (request()->has('sort-rack-desc'))
								<th class="has-text-right">
									<a href="{{ route('stock.index') }}">Rack No. <i class="fa fa-chevron-down"></i></a>
								</th>
							@else
								<th class="has-text-right">
									<a href="?sort-rack-desc">Rack No.</a>
								</th>
							@endif
							<th class="has-text-right">Avail. quantity</th>
							<th class="has-text-right">
								<b-tooltip label="Recommended quantity">
									R. quantity
								</b-tooltip>
							</th>
							<th class="has-text-right">Actions</th>
						</tr>
						</thead>
						<tbody>
						@foreach ($stocks as $stock)
							<tr>
								<td class="has-text-left">{{ $stock->code }}</td>
								<td>
									<a href="{{ route('stock.category', ['category_id' => $stock->category_id]) }}">
										{{ optional($stock->category)->name }}
									</a>
								</td>
								<td><a href="{{ route('stock.show', ['id' => $stock->id]) }}">
									{{ ucwords(strtolower( $stock->description )) }}</a></td>
								<td class="has-text-right">{{ $stock->rack_number }}</td>
								<td class="has-text-right">{{ $stock->on_hand_quantity }}</td>
								<td class="has-text-right">{{ $stock->recommended_qty }}</td>
								<td class="has-text-right">
									<a href="{{ route('stock.edit', ['id' => $stock->id]) }}" class="button is-small is-warning">
										Edit
									</a>
								</td>
							</tr>
						@endforeach
						</tbody>
					</table>
				</b-tab-item>
				@foreach (\App\Models\Category::get() as $category)
					<b-tab-item label="{{ $category->name }}">
						<table class="table is-size-7 is-narrow is-fullwidth is-bordered">
							<thead>
							<colgroup>
								<col style="width:1px;">
								<col style="width:7rem">
								<col>
								<col style="width:6rem;">
								<col style="width:6rem;">
								<col style="width:6rem;">
								<col style="width:5rem;">
							</colgroup>
							<tr>
								<th>code</th>
								<th>Category</th>
								<th>Description</th>
								<th class="has-text-right">Rack No.</th>
								<th class="has-text-right">Avail. quantity</th>
								<th class="has-text-right">
									<b-tooltip label="Recommended quantity">
										R. quantity
									</b-tooltip>
								</th>
								<th class="has-text-right">Actions</th>
							</tr>
							</thead>
							<tbody>
							@foreach ($category->stocks as $stock)
								<tr>
									<td>{{ $stock->code }}</td>
									<td>
										<a href="{{ route('stock.category', ['category_id' => $stock->category_id]) }}">
											{{ optional($stock->category)->name }}
										</a>
									</td>
									<td><a href="{{ route('stock.show', ['id' => $stock->id]) }}">{{ ucwords(strtolower($stock->description ))}}</a></td>
									<td class="has-text-right">{{ $stock->rack_number }}</td>
									<td class="has-text-right">{{ $stock->on_hand_quantity }}</td>
									<td class="has-text-right">{{ $stock->recommended_qty }}</td>
									<td class="has-text-right">
										<a href="{{ route('stock.edit', ['id' => $stock->id]) }}" class="button is-small is-warning">
											Edit
										</a>
									</td>
								</tr>
							@endforeach
							</tbody>
						</table>
					</b-tab-item>
				@endforeach
			</b-tabs>
		</div>
	</div>
@endsection
