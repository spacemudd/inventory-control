<style>
    @media print {
        html, body {
            height: auto;
            font-size: 12pt; /* changing to 10pt has no impact */
        }
    }
</style>
{{--@extends('layouts.app', ['title' => 'Job Orders'])--}}

{{--@section('header')--}}
{{--    <nav class="breadcrumb" aria-label="breadcrumbs">--}}
{{--        <ul>--}}
{{--            <li>--}}
{{--                <a href="{{ route('dashboard.index') }}" aria-current="page">--}}
{{--                    <span class="icon is-small"><i class="fa fa-inbox"></i></span>--}}
{{--                    <span>{{ trans('words.dashboard') }}</span>--}}
{{--                </a>--}}
{{--            </li>--}}
{{--            <li>--}}
{{--                <a href="{{ route('job-orders.index') }}">--}}
{{--                    <span class="icon is-small"><i class="fa fa-paper-plane-o"></i></span>--}}
{{--                    <span>Job Orders</span>--}}
{{--                </a>--}}
{{--            </li>--}}
{{--            <li class="is-active">--}}
{{--                <a href="{{ route('job-orders.index') }}">--}}
{{--                    Pending ({{ $jobOrders->count() }})--}}
{{--                </a>--}}
{{--            </li>--}}
{{--        </ul>--}}
{{--    </nav>--}}
{{--@endsection--}}

{{--@section('content')--}}
<div class="columns is-multiline">
    <div class="column">
        <p><b>Total:</b> {{ $jobOrders->count() }}</p>
        <table class="table is-fullwidth is-bordered is-narrow is-striped" style="font-size:10px;width:100%;">
        <thead>
        <tr>
        	<th style="width:100px;text-align:left">No.</th>
            <th style="width:100px;text-align:left">Date</th>
            <th style="width:100px;text-align:left">Dispatched</th>
            <th style="width:300px;text-align:left">Description</th>
        </tr>
        </thead>
        	<tbody>
                @if ($jobOrders)
                    @foreach ($jobOrders as $jobOrder)
                        <tr>
                            <td>
                                <p>{{ $jobOrder->job_order_number }}</p>
                            </td>
                            <td>{{ $jobOrder->date->format('d-m-Y') }}</td>
                            <td>
                                <div class="tag{{ $jobOrder->dispatched_count===$jobOrder->items()->count() ? ' is-success' : ' is-warnings' }}" style="height:14px;">
                                    DISPATCHED (<b>{{$jobOrder->dispatched_count}}</b>/{{ $jobOrder->items()->count() }})
                                </div>
                            </td>
                            <td>
                                <p>{{ $jobOrder->job_description }}</p>
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
{{--@endsection--}}
