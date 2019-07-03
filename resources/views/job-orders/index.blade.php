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
            <a href="{{ route('job-orders.index').'?all' }}">All: {{ \App\Models\JobOrder::completed()->count() }}</a>
        </div>
    </div>
    <div class="column is-6">
        <div class="has-text-right">
            <a href="{{ route('job-orders.create') }}" class="button is-primary is-small">New</a>
        </div>
    </div>
    <div class="column">
        <table class="table is-fullwidth is-bordered is-size-7 is-narrow">
        <thead>
        <tr>
        	<th>No.</th>
            <th>Date</th>
            <th>Requester</th>
            <th>Employee ID</th>
            <th>Department</th>
            <th>Location</th>
            <th>Ext</th>
            <th>CC</th>
            <th>Requested via</th>
            <th>Status</th>
            <th>Items</th>
            <th></th>
        </tr>
        </thead>
        	<tbody>
                @if ($jobOrders)
                    @foreach ($jobOrders as $jobOrder)
                        <tr>
                            <td>{{ $jobOrder->job_order_number }}</td>
                            <td>{{ $jobOrder->date->format('d-m-Y') }}</td>
                            <td>{{ optional($jobOrder->employee)->name }}</td>
                            <td>{{ optional($jobOrder->employee)->code }}</td>
                            <td>{{ optional($jobOrder->department)->description }}</td>
                            <td>{{ $jobOrder->location->name }}</td>
                            <td>{{ $jobOrder->ext }}</td>
                            <td>{{ optional($jobOrder->department)->code }}</td>
                            <td>{{ ucfirst($jobOrder->requested_through_type) }}</td>
                            <td>{{ $jobOrder->status }}</td>
                            <td>
                                <span class="tag{{ $jobOrder->dispatched_count===$jobOrder->items()->count() ? ' is-success' : '' }}">
                                    DISPATCHED (<b>{{$jobOrder->dispatched_count}}</b>/{{ $jobOrder->items()->count() }})
                                </span>
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
    </div>
</div>
@endsection
