@extends('layouts.app', ['title' => $jobOrder->job_order_number.' - Job orders'])

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
                <a href="{{ route('job-orders.index') }}">
                    <span class="icon is-small"><i class="fa fa-shopping-cart"></i></span>
                    <span>Job Orders</span>
                </a>
            </li>
            <li class="is-active">
                <a href="#">
                    {{ $jobOrder->job_order_number }}
                </a>
            </li>
        </ul>
    </nav>
@endsection

@section('content')
    <div class="columns is-centered">
        <div class="column is-12">
            <div class="box">
                <job-order-show-form :id.number="{{ $jobOrder->id }}"
                                     :can-change-equipment="{{ $jobOrder->isCompleted() ? 'false' : 'true' }}"
                ></job-order-show-form>
            </div>
        </div>
    </div>
@endsection
