@extends('layouts.app', ['title' => 'Cost approvals'])

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
                <a href="{{ route('cost-approvals.index') }}">
                    <span class="icon is-small"><i class="fa fa-file"></i></span>
                    <span>Cost approvals</span>
                </a>
            </li>
            @if (request()->has('all'))
                <li class="is-active">
                    <a href="{{ route('cost-approvals.index') }}">
                        All
                    </a>
                </li>
            @endif
        </ul>
    </nav>
@endsection

@section('content')
<div class="columns is-multiline">
    <div class="column is-12">
        <div class="has-text-right">
            <div>
                <a href="{{ route('cost-approvals.create') }}" class="button is-primary is-small">Add new</a>
            </div>
        </div>
    </div>

    <div class="column is-12">
        <table class="table is-fullwidth is-size-7">
            <thead>
                <th>No.</th>
                <th>Date</th>
                <th>Cost Center</th>
                <th>Requested by</th>
                <th>Purpose of request</th>
                <th></th>
            </thead>
            <tbody>
                @foreach ($cas as $ca)
                    <tr>
                        <td>{{ $ca->number ?: $ca->id }}</td>
                        <td>{{ $ca->date->format('d-m-Y') }}</td>
                        <td>{{ $ca->cost_center->display_name }}</td>
                        <td>{{ $ca->requested_by->display_name }}</td>
                        <td>{{ $ca->purpose_of_request }}</td>
                        <td><a href="{{ route('cost-approvals.show', ['id' => $ca->id]) }}">View</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $cas->links('vendor.pagination.bulma') }}
    </div>
</div>
@endsection
