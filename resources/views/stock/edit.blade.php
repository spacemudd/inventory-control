@extends('layouts.app', ['title' => $stock->description . ' - Edit'])


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
				<a href="{{ route('stock.index') }}">
					<span class="icon is-small"><i class="fa fa-tags"></i></span>
					<span>Stock</span>
				</a>
			</li>
			<li class="is-active">
				<a href="#">{{ $stock->description }} ({{ $stock->on_hand_quantity }})</a>
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

	<form method="post" action="{{ route('stock.update', ['id' => $stock->id]) }}">
		@csrf
		<input type="hidden" name="_method" value="put">
		<div class="columns is-multiline">
			<div class="column is-4 is-offset-4">
				<div class="field">
					<label for="description" class="label">{{ trans('words.description') }} <span class="has-text-danger">*</span></label>

					<p class="control">
						<input id="description" type="text"
							   class="input {{ $errors->has('description') ? ' is-danger' : '' }}"
							   name="description"
							   value="{{ $stock->description }}"
							   autofocus
							   required>

						@if ($errors->has('description'))
							<span class="help is-danger">
                                {{ $errors->first('description') }}
                            </span>
						@endif
					</p>
				</div>
			</div>

			<div class="column is-4 is-offset-4">
				<div class="field">
					<label for="rack_number" class="label">Rack number</span></label>
					<p class="control">
						<input id="rack_number"
							   type="string"
							   class="input {{ $errors->has('rack_number') ? ' is-danger' : '' }}"
							   name="rack_number"
							   value="{{ $stock->rack_number }}"
							   autocomplete="off">
						@if ($errors->has('rack_number'))
							<span class="help is-danger">
                                {{ $errors->first('rack_number') }}
                            </span>
						@endif
					</p>
				</div>
			</div>


			<div class="column is-4 is-offset-4">
				<div class="field">
					<label for="available_quantity" class="label">Available quantity <span class="has-text-danger">*</span></label>
					<p class="control">
						<input id="available_quantity"
							   type="number"
							   class="input {{ $errors->has('available_quantity') ? ' is-danger' : '' }}"
							   name="available_quantity"
							   value="{{ $stock->on_hand_quantity }}"
							   required>

						@if ($errors->has('available_quantity'))
							<span class="help is-danger">
                                {{ $errors->first('available_quantity') }}
                            </span>
						@endif
					</p>
				</div>
			</div>

			<div class="column is-4 is-offset-4">
				<div class="field">
					<label for="recommended_qty" class="label">Recommended quantity</label>
					<p class="control">
						<input id="recommended_qty"
							   type="number"
							   class="input {{ $errors->has('recommended_qty') ? ' is-danger' : '' }}"
							   name="recommended_qty"
							   value="{{ $stock->recommended_qty }}">

						@if ($errors->has('recommended_qty'))
							<span class="help is-danger">
                                {{ $errors->first('recommended_qty') }}
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
								<option value=""></option>
								@foreach(\App\Models\Category::get() as $category)
									<option value="{{ $category->id }}"{{ (int)$category->id===(int)$stock->category_id ? ' selected' : '' }}>
										{{ $category->name }}
									</option>
								@endforeach
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
				<a class="button is-text" href="{{ url('/') }}">Cancel</a>
				<button type="submit" class="button is-success">Update</button>
			</div>

		</div>

	</form>

@endsection
