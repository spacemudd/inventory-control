@extends('layouts.app', ['title' => 'Contracts'])

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
                <a href="{{ route('contracts.index') }}">
                    <span class="icon is-small"><i class="fa fa-file"></i></span>
                    <span>Contracts</span>
                </a>
            </li>
            @if (request()->has('all'))
                <li class="is-active">
                    <a href="{{ route('contracts.index') }}">
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
        <contracts-search></contracts-search>
    </div>
    <div class="column is-6">
        <div class="has-text-right is-flex" style="justify-content:flex-end">
            <div style="margin: 0 5px;">
                <a href="{{ route('contracts.export') }}" class="button is-small">Export</a>
            </div>
            <div>
                <a href="{{ route('contracts.create') }}" class="button is-primary is-small">New</a>
            </div>
        </div>
    </div>

    <div class="column is-12">
        <table class="table is-fullwidth is-size-7">
            <colgroup>
                <col style="width:10%;">
                <col style="width:10%;">
                <col>
                <col style="width:10%;">
                <col style="width:10%;">
                <col style="width:12%;">
                <col style="width:10%;">
                <col style="width:0%;">
            </colgroup>
            <thead>
                <th>    @if (request()->has('sort_by') && request()->sort_by === 'contract-id-desc')
                            <a href="?tab={{request()->tab}}&sort_by=contract-id-asc">Contract <i class="fa fa-arrow-down"></i></a>
                        @elseif(request()->has('sort_by') && request()->sort_by === 'contract-id-asc')
                            <a href="?tab={{request()->tab}}&sort_by=contract-id-desc">Contract<i class="fa fa-arrow-up"></i></a>                        
                        @else
                            <a href="?tab={{request()->tab}}&sort_by=contract-id-asc">Contract </a>  
                        @endif
                </th>
                <th>    @if (request()->has('sort_by') && request()->sort_by === 'date-desc')
                            <a href="?tab={{request()->tab}}&sort_by=date-asc">Date <i class="fa fa-arrow-down"></i></a>
                        @elseif(request()->has('sort_by') && request()->sort_by === 'date-asc')
                            <a href="?tab={{request()->tab}}&sort_by=date-desc">Date<i class="fa fa-arrow-up"></i></a>                        
                        @else
                            <a href="?tab={{request()->tab}}&sort_by=date-asc">Date</a>  
                        @endif
                </th>
                <th>    @if (request()->has('sort_by') && request()->sort_by === 'supplier-desc')
                            <a href="?tab={{request()->tab}}&sort_by=supplier-asc">Supplier <i class="fa fa-arrow-down"></i></a>
                        @elseif(request()->has('sort_by') && request()->sort_by === 'supplier-asc')
                            <a href="?tab={{request()->tab}}&sort_by=supplier-desc">Supplier<i class="fa fa-arrow-up"></i></a>                        
                        @else
                            <a href="?tab={{request()->tab}}&sort_by=supplier-asc">Supplier </a>  
                        @endif
                </th>
                <th>    @if (request()->has('sort_by') && request()->sort_by === 'cost-desc')
                            <a href="?tab={{request()->tab}}&sort_by=cost-asc">Cost <i class="fa fa-arrow-down"></i></a>
                        @elseif(request()->has('sort_by') && request()->sort_by === 'cost-asc')
                            <a href="?tab={{request()->tab}}&sort_by=cost-desc">Cost<i class="fa fa-arrow-up"></i></a>                        
                        @else
                            <a href="?tab={{request()->tab}}&sort_by=cost-asc">Cost </a>  
                        @endif
                </th>
                <th>    
                    Frequency
                </th>
                <th>    @if (request()->has('sort_by') && request()->sort_by === 'value-desc')
                            <a href="?tab={{request()->tab}}&sort_by=value-asc">Contract Value <i class="fa fa-arrow-down"></i></a>
                        @elseif(request()->has('sort_by') && request()->sort_by === 'value-asc')
                            <a href="?tab={{request()->tab}}&sort_by=value-desc">Contract Value<i class="fa fa-arrow-up"></i></a>                        
                        @else
                            <a href="?tab={{request()->tab}}&sort_by=value-asc">Contract Value </a>  
                        @endif
                </th>
                <th>    
                    Paid So Far
                </th>
                <th></th>
            </thead>
            <tbody>
                @foreach ($contracts as $co)
                    <tr>
                        <td><a href="{{ route('contracts.show', $co->id) }}">{{ $co->number ?: $co->id }}</a></td>
                        <td>
                            {{ optional($co->issued_at)->format('d-m-Y') }}<br/>
                            {{ optional($co->expires_at)->format('d-m-Y') }}
                        </td>
                        <td>{{ optional($co->vendor)->display_name }}</td>
                        <td>{{ $co->cost }}</td>
                        <td>{{ ucwords($co->payment_frequency) }}</td>
                        <td>{{ number_format($co->total_cost, 2) }}</td>
                        <td>
                            {{ number_format($co->payments()->sum('cost'), 2) }}
                        </td>
                        <td class="has-text-right"><a class="button is-small" href="{{ route('contracts.show', ['id' => $co->id]) }}">View</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $contracts->links('vendor.pagination.bulma') }}
    </div>
</div>
@endsection
