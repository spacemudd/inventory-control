@extends('layouts.app', ['title' => 'Create Equipments'])


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
				<a href="{{ route('equipments.index') }}">
					<span class="icon is-small"><i class="fa fa-wrench"></i></span>
					<span>Equipments</span>
				</a>
			</li>
			<li class="is-active">
				<a href="#">New</a>
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

	<form method="post" action="{{ route('equipments.store') }}">
		@csrf
		
		<div class="column is-4 is-offset-4">
				<div class="field">
					<label for="name" class="label">Name</label>
					<p class="control">
						<input id="name"
							   type="string"
							   class="input {{ $errors->has('name') ? ' is-danger' : '' }}"
							   name="name"
							   value="{{ old('name') }}"
							   autocomplete="off">
						@if ($errors->has('name'))
							<span class="help is-danger">
                                {{ $errors->first('name') }}
                            </span>
						@endif
					</p>
				</div>
			</div>

		<div class="column is-4 is-offset-4">
			   <div class="field">
					<label for="select-category" class="label">Category</label>

					<p class="control">
						<div class="select is-fullwidth">
							<select id="select-category" name="category_id">
								
							</select>
						</div>

						@if ($errors->has('category_id'))
							<span class="help is-danger">
                                {{ $errors->first('category_id') }}
                            </span>
						@endif
					</p>
				</div>
			</div>

			<div class="column is-4 is-offset-4 has-text-right">
				<a class="button is-text" href="{{ route('equipments.index') }}">Cancel</a>
				<button type="submit" class="button is-success">{{ trans('words.save') }}</button>
			</div>


	</form>

@endsection
