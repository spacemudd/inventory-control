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
			<div class="column is-3"><p class="title is-5">General</p></div>
		</div>

		<div class="columns">
			<div class="column is-3">
				<div class="field">
					<label class="label has-text-weight-normal">Purchase order number</label>
					<div class="control">
						<input name="number" class="input is-small" type="text" autofocus autocomplete="off">
						<p class="help">Leave blank to be auto-generated.</p>
					</div>
				</div>
			</div>
			<div class="column is-3">
				<div class="field">
					<label class="label has-text-weight-normal">Cost Center</label>
					<div class="control">
						<select-department name="cost_center_id"
										   input-class="is-small"
										   url="{{ route('api.search.department') }}">
						</select-department>
						<div class="is-flex" style="justify-content:space-between">
							<p class="help">Search by department code or name</p>
							<button style="margin-top:5px;height:20px;border-color:#078af3;" type="button" class="is-small button" @click="$store.commit('Department/showNewModal', true)">
								New
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="columns">
			<div class="column is-3">
				<div class="field">
					<label class="label has-text-weight-normal">Date</label>
					<div class="control">
						<input name="date" class="input is-small" type="date" autofocus>
					</div>
				</div>
			</div>
			<div class="column is-3">
				<div class="field">
					<label class="label has-text-weight-normal">Subject</label>
					<div class="control">
						<input name="subject" class="input is-small" type="text" autofocus autocomplete="off">
					</div>
				</div>
			</div>
		</div>

		<div class="columns">
			<div class="column is-3">
				<div class="field">
					<label class="label has-text-weight-normal">Supplier</label>
					<div class="control">
						<select-vendors name="vendor_id"
										input-class="is-small"
										url="{{ route('api.search.vendor') }}">
						</select-vendors>

						<span class="help">Search by code or name</span>
						@if ($errors->has('supplier_id'))
							<span class="help is-danger">
								{{ $errors->first('supplier_id') }}
							</span>
						@endif
					</div>
				</div>
			</div>
			<div class="column is-3">
				<div class="field">
					<label class="label has-text-weight-normal">Quote number</label>
					<div class="control">
						<input name="quote_reference_number" class="input is-small" type="text" autofocus autocomplete="off">
					</div>
				</div>
			</div>
		</div>

		{{-- -- Form done. --}}
		<div class="column is-6 has-text-right">
			<a class="button is-text" href="{{ route('purchase-orders.index') }}">Cancel</a>
			<button type="submit" class="button is-primary">Save</button>
		</div>
	</form>
@endsection
