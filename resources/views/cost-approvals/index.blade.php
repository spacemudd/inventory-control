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
    <div class="column is-6">
        <form class="select is-small is-flex">
            @csrf
            @if (request()->has('sort_by'))
                <input type="hidden" name="sort_by" value="number-desc">
            @endif
            <p class="is-small is-size-7" style="margin-top:4px;margin-right:10px;">Cost center: </p>
            <select class="select"
                    name="cost_center_id"
                    id="cost_center_id">
                <option value=""></option>
                @foreach ($departments as $dep)
                    <option value="{{ $dep->id }}"{{ request()->cost_center_id == $dep->id ? ' selected' : '' }}>{{ $dep->code }} {{ $dep->description }}</option>
                @endforeach
            </select>
            <button class="button is-primary is-small">Filter</button>
        </form>
    </div>
    <div class="column is-6">
        <div class="has-text-right">
            <div>
                <a href="{{ route('cost-approvals.create') }}" class="button is-primary is-small">Add new</a>
            </div>
        </div>
    </div>

    <div class="column is-12">
        <table class="table is-fullwidth is-size-7">
            <colgroup>
                <col style="width:13%;">
                <col style="width:10%;">
                <col>
                <col style="width:10%;">
                <col style="width:20%;">
                <col style="width:15%;">
                <col style="width:10%;">
            </colgroup>
            <thead>
                <th>
                    @if (request()->has('sort_by') && request()->sort_by === 'number-desc')
                        <a href="{{ route('cost-approvals.index') }}">No.</a>
                    @else
                        @if (request()->has('cost_center_id'))
                            <a href="?sort_by=number-desc&cost_center_id={{ request()->cost_center_id }}">No.</a>
                        @else
                            <a href="?sort_by=number-desc">No.</a>
                        @endif
                    @endif
                </th>
                <th>Date</th>
                <th>Cost Center</th>
                <th>Requested by</th>
                <th>Purpose of request</th>
                <th>PO Processed</th>
                <th></th>
            </thead>
            <tbody>
                @foreach ($cas as $ca)
                    <tr>
                        <td>{{ $ca->number ?: $ca->id }}</td>
                        <td>{{ $ca->date->format('d-m-Y') }}</td>
                        <td>{{ optional($ca->cost_center)->display_name }}</td>
                        <td>{{ optional($ca->requested_by)->display_name }}</td>
                        <td>{{ $ca->purpose_of_request }}</td>
                        <td>
                            @foreach ($ca->purchase_orders as $po)
                                <p><a href="{{ $po->link }}">{{ $po->number }}</a></p>
                                <p><a href="{{ $po->link }}">{{ $po->number }}</a></p>
                            @endforeach
                        </td>
                        <td class="has-text-right"><a class="button is-small" href="{{ route('cost-approvals.show', ['id' => $ca->id]) }}">View</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $cas->links('vendor.pagination.bulma') }}
    </div>
</div>
@endsection
