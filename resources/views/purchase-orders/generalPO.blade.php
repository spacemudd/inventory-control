@extends('layouts.app', [
	'title' => trans('words.purchase-orders')
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
            <li class="is-active">
                <a href="{{ route('purchase-orders.index') }}">
                    <span class="icon is-small"><i class="fa fa-shopping-cart"></i></span>
                    <span>{{ trans('words.purchase-orders') }}</span>
                </a>
            </li>
            <li class="is-active">
                <a href="{{ route('purchase-orders.index') }}">
                    <span>General PO</span>
                </a>
            </li>
            <li class="is-active">
                <a href="{{ route('purchase-orders.index') }}">
                    <span>{{ trans('words.new') }}</span>
                </a>
            </li>
        </ul>
    </nav>
@endsection

@section('content')
    <form action="/api/v1/download/PO/PDF" method="post">
        @csrf
        <div class="box colimn is-6">
        <new-purchase-orders-general-po></new-purchase-orders-general-po>
            <div>
                 <button class="button is-success">Save</button>
            </div>
        </div>
    </form>


@endsection