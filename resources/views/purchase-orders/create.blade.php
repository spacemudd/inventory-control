@extends('layouts.app', ['title' => trans('words.create') . ' ' . trans('words.purchase-order')])

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
				<a href="{{ route('purchase-orders.index') }}">
					<span class="icon is-small"><i class="fa fa-shopping-cart"></i></span>
					<span>{{ trans('words.purchase-orders') }}</span>
				</a>
			</li>
			<li class="is-active">
				<a href="#">{{ trans('words.new-purchase-order') }}</a>
			</li>
		</ul>
	</nav>
@endsection

@section('content')
	<form action="{{ route('purchase-orders.store') }}" method="post">
		{{ csrf_field() }}

		{{-- Cost center --}}
		<div class="columns">
			<div class="column is-3"><p class="title is-5">Information</p></div>
			<div class="column is-4">
				<div class="field">
					<label for="cost_center_id" class="label">Cost Center</label>

					<div class="control">
						<select-department name="cost_center_id"
										   url="{{ route('api.search.department') }}">
						</select-department>
						<p class="help">Search by department code or name</p>
						<button type="button" class="is-small button is-text" @click="$store.commit('Department/showNewModal', true)">
							New
						</button>
					</div>
				</div>
				<hr>

				{{-- To --}}
				<div class="field">
					<label for="to" class="label">To</label>

					<div class="control">
						<input type="text" class="input" name="to">
					</div>
				</div>

				<hr>

				
				{{-- Subject --}}
				<div class="field">
					<label for="subject" class="label">Subject</label>

					<div class="control">
						<input type="text" class="input" name="subject">
					</div>
				</div>

				<hr>

				{{-- Quote --}}
				<div class="field">
					<label for="subject" class="label">Ref. Quote</label>

					<div class="select is-fullwidth">
						<select class="select is-fullwidth" name="quotation_id" id="quotation_id">
							<option value=""></option>
							@foreach ($quotes as $quote)
								<option value="{{ $quote->id }}">{{ $quote->vendor_quotation_number }}</option>
							@endforeach
						</select>
					</div>
				</div>

			</div>
		</div>


		{{-- -- Form done. --}}
		<div class="column is-7 has-text-right">
			<a class="button is-text" href="{{ route('purchase-orders.index') }}">Cancel</a>
			<button type="submit" class="button is-primary">Save</button>
		</div>
	</form>
@endsection
