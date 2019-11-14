@extends('layouts.app', ['title' => 'Job Orders'])

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
                <a href="{{ route('job-orders.index') }}">
                    <span class="icon is-small"><i class="fa fa-paper-plane-o"></i></span>
                    <span>Job Orders</span>
                </a>
            </li>
            @if (request()->has('all'))
                <li class="is-active">
                    <a href="{{ route('job-orders.index') }}">
                        All
                    </a>
                </li>
            @endif
        </ul>
    </nav>
@endsection

@section('content')
<div class="columns is-multiline">
    <div class="column is-6">
        <div class="is-inline job-order-status-box{{ request()->has('all') || request()->has('completed') ? '' : ' active' }}">
            <a href="{{ route('job-orders.index') }}">Pending: {{ \App\Models\JobOrder::pending()->count() }}</a>
        </div>
        <div class="is-inline job-order-status-box{{ request()->has('completed') ? ' active' : ''}}" style="margin-left:10px;">
            <a href="{{ route('job-orders.index').'?completed' }}">Completed: {{ \App\Models\JobOrder::completed()->count() }}</a>
        </div>
        <div class="is-inline job-order-status-box{{ request()->has('all') ? ' active' : ''}}" style="margin-left:10px;">
            <a href="{{ route('job-orders.index').'?all' }}">All: {{ \App\Models\JobOrder::count() }}</a>
        </div>
    </div>
    <div class="column is-6">

        <div class="has-text-right">
            <a target="_blank" href="{{ route('job-orders.pending') }}" class="button is-small">Pending</a>

            <a href="{{ route('job-orders.excel') }}" class="button is-small is-success">
                <span class="icon"><i class="fa fa-file-excel-o"></i></span>
                <span>Excel</span>
            </a>

            <a href="{{ route('job-orders.create') }}" class="button is-primary is-small">New</a>
        </div>
    </div>
    <div class="column is-12">
        <job-orders-search></job-orders-search>
    </div>
    <div class="column">
        <table class="table is-fullwidth is-bordered is-size-7 is-narrow is-striped">
        <thead>
        <tr>
        	<th style="width:100px;">No.</th>
            <th style="width:100px;">Status</th>
            <th style="width:300px;">Description</th>
            <th>Location</th>
            <th style="width:75px;">Extension</th>
            <th style="width:150px;">Equipment</th>
            <th style="width:200px;">Technicians</th>
            <th style="width:80px;"></th>
        </tr>
        </thead>
        	<tbody>
                @if ($jobOrders)
                    @foreach ($jobOrders as $jobOrder)
                        <tr>
                            <td>
                                <p><b>{{ $jobOrder->job_order_number }}</b></p>
                                <p>{{ $jobOrder->date->format('d-m-Y') }}</p>
                                {{ str_replace('_', ' ', ucfirst($jobOrder->requested_through_type)) }}
                            </td>
                            <td>
                                <div class="tag{{ $jobOrder->dispatched_count===$jobOrder->items()->count() ? ' is-success' : '' }}">
                                    DISPATCHED (<b>{{$jobOrder->dispatched_count}}</b>/{{ $jobOrder->items()->count() }})
                                </div>
                                <div style="margin-top:5px;width:100%;" class="tag{{ $jobOrder->isCompleted ? ' is-success' : ' is-warning' }}">
                                    <i style="margin-top: 1px;padding-right:5px;" class="fa {{ $jobOrder->isCompleted ? 'fa-check' : 'fa-circle-o-notch'}}"></i>
                                    {{ $jobOrder->status }}
                                </div>
                            </td>
                            <td>
                                <p>{{ $jobOrder->job_description }}</p>
                            </td>
                            <td>{{ optional($jobOrder->location)->name }}</td>
                            <td>{{ $jobOrder->ext }}</td>
                            <td>
                                @if($jobOrder->equipment)
                                    <small>{{ $jobOrder->equipment->ancestors->count() ? implode(' > ', $jobOrder->equipment->ancestors->pluck('name')->toArray()) : 'Top Level' }}</small><br>
                                    <b>{{ $jobOrder->equipment->name }}</b>
                                @endif
                            </td>
                            <td>
                                <ul>
                                    @foreach($jobOrder->technicians as $tech)
                                        <li>- {{ $tech->name }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="has-text-centered">
                                <a href="{{ route('job-orders.show', $jobOrder) }}"
                                   class="button is-small is-warning"
                                   style="height:20px;">
                                        <span class="icon"><i class="fa fa-eye"></i></span>
                                        <span>View</span>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="has-text-centered is-italic" colspan="8" style="background-color:#f5f5f5;">No Job Orders.</td>
                    </tr>
                @endif
        	</tbody>
        </table>

        {{ $jobOrders->links('vendor.pagination.bulma') }}
    </div>
</div>
@endsection
