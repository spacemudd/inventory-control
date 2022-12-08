@extends('layouts.app', ['title' => $category->name . ' - Edit'])


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
			<li class="is-active">
				<a href="#">{{ $category->name }}</a>
			</li>
		</ul>
	</nav>
@endsection

@section('content')

	@if(session()->has('success'))
		<div class="columns">
			<div class="column is-4 is-offset-4">
				<div class="notification is-primary">
					{{ session()->get('success') }}
				</div>
			</div>
		</div>
	@endif

	<form method="post" action="{{ route('categories.update', ['id' => $category->id]) }}">
		@csrf
		<input type="hidden" name="_method" value="put">
		<div class="columns is-multiline">
			<div class="column is-4 is-offset-4">
				<div class="field">
					<label for="name" class="label">{{ trans('words.name') }} <span class="has-text-danger">*</span></label>

					<p class="control">
						<input id="description" type="text"
							   class="input {{ $errors->has('name') ? ' is-danger' : '' }}"
							   name="name"
							   value="{{ $category->name }}"
							   autofocus
							   required>

						@if ($errors->has('name'))
							<span class="help is-danger">
                                {{ $errors->first('name') }}
                            </span>
						@endif
					</p>
				</div>
			</div>

			<div class="column is-4 is-offset-4 has-text-right">
				<a class="button is-text" href="{{ url('/') }}">Cancel</a>
				<button type="submit" class="button is-success">{{ trans('words.save') }}</button>
			</div>
		</div>
	</form>

@endsection
