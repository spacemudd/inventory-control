@extends('layouts.app', ['title' => 'Quotations'])

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
                    <span class="icon is-small"><i class="fa fa-quote-right"></i></span>
                    <span>Quotations</span>
                </a>
            </li>
        </ul>
    </nav>
@endsection

@section('content')
<div class="columns is-multiline">
    <div class="column is-6">
        <div class="is-inline job-order-status-box{{ request()->tab == 'draft' || !request()->has('tab')  ? 'active' : '' }}">
            <a href="{{ route('quotations.index').'?tab=draft' }}">Draft: {{ \App\Models\Quotation::draft()->count() }}</a>
        </div>
        <div class="is-inline job-order-status-box{{ request()->tab == 'saved' ? ' active' : ''}}" style="margin-left:10px;">
            <a href="{{ route('quotations.index').'?tab=saved' }}">Saved: {{ \App\Models\Quotation::SavedQuotations()->count() }}</a>
        </div>
        <div class="is-inline job-order-status-box{{ request()->tab == 'all' ? ' active' : ''}}" style="margin-left:10px;">
            <a href="{{ route('quotations.index').'?tab=all' }}">All: {{ \App\Models\Quotation::count() }}</a>
        </div>
    </div>
    <div class="column is-6">
        <div class="has-text-right">
            <a href="{{ route('quotations.create') }}" class="button is-primary is-small">Add new</a>
        </div>
    </div>
    <div class="column">
        <table class="table is-fullwidth is-bordered is-size-7 is-narrow">
        <thead>
        <tr>
        	<th width="200px">Code</th>
            <th width="200px">
            
            		@if (request()->has('sort_by') && request()->sort_by === 'material-desc')
                        <a href="?tab={{request()->tab}}&sort_by=material-asc">Material Request No. <i class="fa fa-arrow-down"></i></a>
                     @elseif(request()->has('sort_by') && request()->sort_by === 'material-asc')
                        <a href="?tab={{request()->tab}}&sort_by=material-desc">Material Request No. <i class="fa fa-arrow-up"></i></a>                        
                    @else
                    	<a href="?tab={{request()->tab}}&sort_by=material-asc">Material Request No. </a>  
                    @endif
            </th>
            <th>Cost Center</th>
            <th width="100px">
            		@if (request()->has('sort_by') && request()->sort_by === 'vendor-desc')
                        <a href="?tab={{request()->tab}}&sort_by=vendor-asc">Vendor <i class="fa fa-arrow-down"></i></a>
                     @elseif(request()->has('sort_by') && request()->sort_by === 'vendor-asc')
                        <a href="?tab={{request()->tab}}&sort_by=vendor-desc">Vendor <i class="fa fa-arrow-up"></i></a>                        
                    @else
                    	<a href="?tab={{request()->tab}}&sort_by=vendor-asc">Vendor </a>  
                    @endif
            </th>
            <th width="100px">Items</th>
            <th width="100px">Total</th>
            <th width="100px">Status</th>
            <th width="100px"></th>
        </tr>
        </thead>
        	<tbody>
                @if ($quotations)
                    @foreach ($quotations as $quote)
                        <tr>
                            <td>{{ $quote->vendor_quotation_number }}</td>
                            <td>
                                @if ($quote->material_request)
                                    <a href="{{ route('material-requests.show', ['id' => optional($quote->material_request)->id]) }}">{{ optional($quote->material_request)->number }}</a>
                                @endif
                            </td>
                            <td>{{ $quote->cost_center->display_name }}</td>
                            <td>{{ $quote->vendor->display_name }}</td>
                            <td>{{ $quote->items()->count() }}</td>
                            <td class="has-text-right">{{ $quote->totalCost() }}</td>
                            <td class="has-text-centered">{{ $quote->status_name }}</td>
                            <td class="has-text-centered">
                                <a href="{{ route('quotations.show', ['id' => $quote->id]) }}"
                                   class="button is-small is-warning"
                                   style="height:20px;"
                                >
                                    <span class="icon"><i class="fa fa-eye"></i></span>
                                    <span>View</span>
                                </a>
                                {{--<button class="button is-small is-warning">--}}
                                    {{--<span class="icon"><i class="fa fa-pencil"></i></span>--}}
                                    {{--<span>Edit</span>--}}
                                {{--</button>--}}

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
                        <td class="has-text-centered is-italic" colspan="7" style="background-color:#f5f5f5;">No quotations.</td>
                    </tr>
                @endif
        	</tbody>
        </table>
    </div>
</div>
@endsection
