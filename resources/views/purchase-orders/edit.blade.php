@extends('layouts.app', ['title' => $purchase_order->id.' - Edit purchase order'])

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
				<a href="{{ route('purchase-orders.show', ['id' => $purchase_order->id]) }}">
					{{ $purchase_order->number ? $purchase_order->number : $purchase_order->id }}
				</a>
			</li>

			<li class="is-active"><a href="#">Edit</a></li>
		</ul>
	</nav>
@endsection

@section('content')

	<form action="{{ route('purchase-orders.update', ['id' => $purchase_order->id]) }}" method="post">
		{{ csrf_field() }}
		<input type="hidden" name="_method" value="put">
		{{-- Cost center --}}
		<div class="columns">
			<div class="column is-3"><p class="title is-5">General</p></div>
		</div>

		<div class="columns">
			<div class="column is-3">
				<div class="field">
					<label class="label has-text-weight-normal">Subject</label>
					<div class="control">
						<input name="subject" class="input is-small" type="text" autofocus autocomplete="off">
					</div>
				</div>
			</div>
		</div>
		<div class="column is-6 has-text-right">
			<a class="button is-text" href="{{ route('purchase-orders.index') }}">Cancel</a>
			<button type="submit" class="button is-primary">Save</button>
		</div>
	</form>
@endsection
