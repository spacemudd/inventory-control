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
			<equipments-search></equipments-search>
{{--			<simple-search--}}
{{--					:hyper-linked-results="true"--}}
{{--					placeholder-text="Search"--}}
{{--					size="is-small"--}}
{{--					search-endpoint="equipments">--}}
{{--			</simple-search>--}}
{{--			<ul style="margin-top:20px;">--}}
{{--				@foreach (\App\Models\Category::get() as $category)--}}
{{--					<li style="display: inline;{{ $category->id===\App\Models\Category::get()->first()->id ? '' : 'margin-left:1rem;' }}">--}}

{{--						@if (isset($selectedCategory))--}}
{{--							@if ($category->id === $selectedCategory->id)--}}
{{--								<b>{{ $category->name }}</b>--}}
{{--							@else--}}
{{--								<a href="{{ route('equipments.category', ['category_id' => $category->id]) }}">--}}
{{--									{{ $category->name }}--}}
{{--								</a>--}}
{{--							@endif--}}
{{--						@else--}}
{{--							<a href="{{ route('equipments.category', ['category_id' => $category->id]) }}">--}}
{{--								{{ $category->name }}--}}
{{--							</a>--}}
{{--						@endif--}}
{{--					</li>--}}
{{--				@endforeach--}}
{{--			</ul>--}}
		</div> 	

@endsection
