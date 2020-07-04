@extends('layouts.app', ['title' => 'Create stock'])


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
				<a href="#">{{ $stock->description }}</a>
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

	<div class="columns">
		<div class="column is-6">
			<b>{{ $stock->description }}</b>
		</div>
		<div class="column has-text-right">
			<form class="is-inline"
			      method="post"
			      action="{{ route('stock.destroy', ['id' => $stock->id]) }}"
			      style="margin-right: 20px;">
			      @csrf
			      <input type="hidden" name="_method" value="delete">
				<button class="button is-danger">Delete</button>
			</form>

			<a class="button is-outlined is-primary" href="{{ route('stock.edit', ['id' => $stock->id]) }}">
				<span class="icon">
					<i class="fa fa-cog"></i>
				</span>
				<span>Edit</span>
			</a>
		</div>
	</div>

	<hr>

	<div class="columns">
		<div class="column is-4">
			<div class="box" style="height:100%;">
				<p class="title is-7 is-uppercase" style="margin:0;letter-spacing: 0.1px;">On-hand Qty</p>
				<p style="margin-top:1rem;" class="title is-4 has-text-weight-light">{{ $stock->on_hand_quantity }}</p>
			</div>
		</div>
		<div class="column is-4">
			<div class="box" style="height:100%;">
				<p class="title is-7 is-uppercase" style="margin:0;letter-spacing: 0.1px;">Recommended Qty</p>
				<p style="margin-top:1rem;" class="title is-4 has-text-weight-light">{{ $stock->recommended_qty }}</p>
			</div>
		</div>
		<div class="column is-4">
			<div class="box" style="height:100%;">
				<p class="title is-7 is-uppercase" style="margin:0;letter-spacing: 0.1px;">Last updated</p>
				<p style="margin-top:1rem;" class="title is-4 has-text-weight-light">{{ $stock->updated_at->format('d-m-Y') }}</p>
			</div>
		</div>
	</div>

	<div class="columns">
		<div class="column">
			<h1 class="has-text-weight-semibold">Activity</h1>
			<b-tabs size="is-small" style="margin-top:1rem;">
				<b-tab-item label="Job orders">
					<table class="table is-fullwidth is-bordered is-size-7 is-narrow">
						<colgroup>
							<col style="width:1px;">
						</colgroup>
					<thead>
					<tr>
						<th>Date</th>
						<th>Ref. Number</th>
					</tr>
					</thead>
						<tbody>
							@foreach ($stock->job_order_items()->get() as $job_item)
								<tr>
									<td>{{ $job_item->jobOrder->created_at->format('d-m-Y') }}</td>
									<td><a href="{{ route('job-orders.show', ['id' => $job_item->jobOrder->job_order_number]) }}">{{ $job_item->jobOrder->job_order_number }}</a></td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</b-tab-item>
{{--				<b-tab-item label="Material requests">--}}
{{--					<table class="table is-fullwidth is-bordered is-size-7 is-narrow">--}}
{{--						<colgroup>--}}
{{--							<col style="width:1px;">--}}
{{--						</colgroup>--}}
{{--						<thead>--}}
{{--						<tr>--}}
{{--							<th>Date</th>--}}
{{--							<th>Ref. Number</th>--}}
{{--						</tr>--}}
{{--						</thead>--}}
{{--						<tbody>--}}
{{--						@foreach ($stock->quotation_items()->get() as $quotation_item)--}}
{{--							<tr>--}}
{{--								<td>{{ $quotation_item->quotation->created_at->format('d-m-Y') }}</td>--}}
{{--								<td><a href="{{ route('quotations.show', ['id' => $quotation_item->quotation->id]) }}">{{ $quotation_item->quotation->vendor_quotation_number }}</a></td>--}}
{{--							</tr>--}}
{{--						@endforeach--}}
{{--						</tbody>--}}
{{--					</table>--}}
{{--				</b-tab-item>--}}
{{--				<b-tab-item label="Quotations">--}}
{{--					...--}}
{{--				</b-tab-item>--}}
				<b-tab-item label="Stock movement">
					<table class="table is-fullwidth is-bordered is-size-7 is-narrow">
						<colgroup>
							<col style="width:1rem;">
						</colgroup>
						<thead>
						<tr>
							<th>Date</th>
							<th>In</th>
							<th>Out</th>
							<th>Reference</th>
						</tr>
						</thead>
						<tbody>
							@foreach ($stock->movement()->take(100)->get() as $movement)
								<tr>
									<td>{{ $movement->created_at->format('d-m-Y') }}</td>
									<td>{{ $movement->in }}</td>
									<td>{{ $movement->out }}</td>
									<td>
										<a href="{{ optional($movement->stockable)->url }}">{{ optional($movement->stockable)->display_name }}</a>
									</td>
	{{--								<td><a href="{{ route('job-orders.show', ['id' => $job_item->jobOrder->job_order_number]) }}">{{ $job_item->jobOrder->job_order_number }}</a></td>--}}
								</tr>
							@endforeach
						</tbody>
					</table>
				</b-tab-item>
			</b-tabs>
		</div>
	</div>

@endsection
