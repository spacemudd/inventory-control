@extends('layouts.app', [
	'title' => 'Purchase Orders Issued to Suppliers Report'
])

@section('header')
	<nav class="breadcrumb" aria-label="breadcrumbs">
		<ul>
			<li>
				<a href="{{ route('dashboard.index') }}" aria-current="page">
					<span class="icon is-small"><i class="fa fa-inbox"></i></span>
					<span>{{ trans('words.dashboard') }}</span>
				</a>
			</li>
			<li >
				<a href="{{ route('purchase-orders.index') }}">
					<span class="icon is-small"><i class="fa fa-shopping-cart"></i></span>
					<span>{{ trans('words.purchase-orders') }}</span>
				</a>
			</li>
			<li class="is-active">
				<a href="#">Purchase Order Issued To Suppliers Report</a>
			</li>
		</ul>
	</nav>
@endsection

@section('content')
	<div class="columns">
		<div class="column">
			<p class="title is-5">Selection</p>

			<form action="{{ route('purchase-orders.report.genreport') }}" method="post">
				{{ @csrf_field() }}
				<div class="columns">
					<div class="column is-3">
						<div class="field">
							<label class="label has-text-weight-normal">Date from</label>
							<div class="control">
								<input name="date_from" class="input is-small" type="date">
							</div>
						</div>
					</div>
					<div class="column is-3">
						<div class="field">
							<label class="label has-text-weight-normal">Date to</label>
							<div class="control">
								<input name="date_to" class="input is-small" type="date">
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
							<label class="checkbox">
							  <input name="is_group" type="checkbox">
							  	Group Supplier in Report
							</label>
						</div>
			
			   </div>	

				{{-- -- Form done. --}}
				<div class="column is-6 has-text-right">
					<a class="button is-text" href="{{ route('purchase-orders.index') }}">Cancel</a>
					<button type="submit" class="button is-primary">Generate</button>
					
				</div>
			</form>
			
		
			
		</div>
	</div>
@endsection
