@extends('layouts.app', ['title' => 'New invoice'])

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
				<a href="#">{{ $po->number }}</a>
			</li>
			<li class="is-active">
				<a href="#">New invoice</a>
			</li>
		</ul>
	</nav>
@endsection

@section('content')
	<form action="{{ route('purchase-orders.invoice.store', ['id' => $po->id]) }}" method="post">
		{{ csrf_field() }}
		<input type="hidden" name="purchase_order_id" value="{{ $po->id }}">

		{{-- Cost center --}}
		<div class="columns">
			<div class="column is-3"><p class="title is-5">General</p></div>
		</div>

		<div class="columns">
			<div class="column is-3">
				<div class="field">
					<label class="label has-text-weight-normal">Purchase order number</label>
					<div class="control">
						<input class="input is-small" type="text" value="{{ $po->number }}" disabled>
					</div>
				</div>
			</div>
			<div class="column is-3">
				<div class="field">
					<label class="label has-text-weight-normal">Grand total</label>
					<div class="control">
						<input class="input is-small" type="text" value="{{ number_format($po->lines()->sum('grand_total'), 2) }}" disabled>
					</div>
				</div>
			</div>
		</div>

		<div class="columns">
			<div class="column is-3">
				<div class="field">
					<label class="label has-text-weight-normal">Invoice number</label>
					<div class="control">
						<input name="number" class="input is-small" type="text" autofocus autocomplete="off">
					</div>
				</div>
			</div>
			<div class="column is-3">
				<div class="field">
					<label class="label has-text-weight-normal">Proceeding date</label>
					<div class="control">
						<input name="proceeded_date" class="input is-small" type="date" value="{{ date("Y-m-j") }}">
					</div>
				</div>
			</div>
		</div>

		<div class="columns">
			<div class="column is-6">
				<table class="table is-fullwidth is-size-7">
				<thead>
				<tr>
					<th>Description</th>
					<th>Serial No.</th>
					<th>Tag No.</th>
				</tr>
				</thead>
					<tbody>
						@foreach ($po->lines as $line)
							<tr>
								<td>
									<input type="hidden" name="lines[{{ $line->id }}][id]" value="{{ $line->id }}">
									{{ $line->description }}
								</td>
								<td><input name="lines[{{ $line->id }}][serial_number]" type="text" class="input is-fullwidth is-small"></td>
								<td><input name="lines[{{ $line->id }}][tag_number]" class="input is-fullwidth is-small"></td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>

		{{-- -- Form done. --}}
		<div class="column is-6 has-text-right">
			<a class="button is-text" href="{{ route('purchase-orders.index') }}">Cancel</a>
			<button type="submit" class="button is-primary">Save</button>
		</div>
	</form>
@endsection
