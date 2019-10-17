@extends('layouts.app', ['title' => 'Material Requests'])

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
                <a href="{{ route('material-requests.index') }}">
                    <span class="icon is-small"><i class="fa fa-shopping-cart"></i></span>
                    <span>Material Requests</span>
                </a>
            </li>
        </ul>
    </nav>
@endsection

@section('content')
<div class="columns is-multiline">
    <div class="column is-6">
        <div class="is-inline job-order-status-box{{ request()->has('pending') ||request()->has('approved') || request()->has('delivered') || request()->has('all')  ? '' : ' active' }}">
            <a href="{{ route('material-requests.index') }}">Pending: {{ \App\Models\MaterialRequest::pending()->count() }}</a>
        </div>
        {{--
        <div class="is-inline job-order-status-box{{ request()->has('approved') ? ' active' : ''}}" style="margin-left:10px;">
            <a href="{{ route('material-requests.index').'?approved' }}">Approved: {{ \App\Models\MaterialRequest::approved()->count() }}</a>
        </div>
        --}}
        <div class="is-inline job-order-status-box{{ request()->has('delivered') ? ' active' : ''}}" style="margin-left:10px;">
            <a href="{{ route('material-requests.index').'?delivered' }}">Delivered: {{ \App\Models\MaterialRequest::delivered()->count() }}</a>
        </div>
        <div class="is-inline job-order-status-box{{ request()->has('all') ? ' active' : ''}}" style="margin-left:10px;">
            <a href="{{ route('material-requests.index').'?all' }}">All: {{ \App\Models\MaterialRequest::count() }}</a>
        </div>
    </div>
    <div class="column is-6">
        <div class="has-text-right">
            <div>
                <a href="{{ route('material-requests.create') }}" class="button is-primary is-small">Add new</a>
                 <a href="{{ route('material-requests.all-excel', ['type' => 'csv']) }}" class="button is-small has-icon">Export CSV format</a>
                 <a href="{{ route('material-requests.all-excel', ['type' => 'xlsx']) }}" class="button is-small has-icon">Export XLSX format</a>
            </div>
        </div>
    </div>
    <div class="column">
        <table class="table is-fullwidth is-bordered is-size-7">
        <thead>
        <tr>
        	<th width="200px">Code</th>
            <th>Lines</th>
            <th>Cost Center</th>
            <th width="200px">Location</th>
            <th width="80px">Status</th>
            <th width="100px"></th>
        </tr>
        </thead>
        	<tbody>
                @if($mRequests)
                    @foreach ($mRequests as $request)
                        <tr>
                            <td>{{ $request->number }}</td>
                            <td>{{ $request->items()->count() }}</td>
                            <td>{{ $request->cost_center->display_name }}</td>
                            <td>{{ optional($request->location)->name }}</td>
                            <td class="has-text-centered">{{ $request->status_name }}</td>
                            <td class="has-text-right">
                                <a href="{{ route('material-requests.show', ['id' => $request->id]) }}"
                                   class="button is-small is-warning"
                                   style="height:20px;"
                                >
                                    <span class="icon"><i class="fa fa-eye"></i></span>
                                    <span>View</span>
                                </a>

                                {{--<button class="button is-small is-primary">--}}
                                    {{--<span class="icon"><i class="fa fa-inbox"></i></span>--}}
                                    {{--<span>Mark as received</span>--}}
                                {{--</button>--}}

                                {{--<button class="button is-small">--}}
                                    {{--<span class="icon"><i class="fa fa-print"></i></span>--}}
                                    {{--<span>Print</span>--}}
                                {{--</button>--}}
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="has-text-centered is-italic" colspan="7" style="background-color:#f5f5f5;">No material requests.</td>
                    </tr>
                @endif
        	</tbody>
        </table>
    </div>
</div>
@endsection
