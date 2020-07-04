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
    </div>
    <div class="column is-6">
        <div class="has-text-right">
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
                <col style="width:10%;">
                <col style="width:10%;">
                <col style="width:10%;">
            </colgroup>
            <thead>
                <th>Contract</th>
                <th>Date</th>
                <th>Supplier</th>
                <th>Cost</th>
                <th>Frequency</th>
                <th>Contract value</th>
                <th>Paid so far</th>
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
