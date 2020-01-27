@extends('layouts.app', ['title' => 'Invoices'])

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
                <a href="{{ route('supplier-invoices.index') }}">
                    <span class="icon is-small"><i class="fa fa-file"></i></span>
                    <span>Invoices</span>
                </a>
            </li>
            <li class="is-active">
                <a href="{{ route('supplier-invoices.index') }}">
                    All
                </a>
            </li>
        </ul>
    </nav>
@endsection

@section('content')
<div class="columns is-multiline">
    <div class="column is-12">
        <div class="has-text-right">
            <div>
                <a href="{{ route('supplier-invoices.create') }}" class="button is-primary is-small">Add new</a>
            </div>
        </div>
    </div>

    <div class="column is-12">
        <table class="table is-fullwidth is-size-7">
            <thead>
                <th>No.</th>
                <th>Supplier</th>
                <th>Proceeded date</th>
                <th></th>
            </thead>
            <tbody>
                @foreach ($invoices as $invoice)
                    <tr>
                        <td>{{ $invoice->number }}</td>
                        <td>{{ optional($invoice->vendor)->display_name }}</td>
                        <td>{{ optional($invoice->date)->format('d-m-Y') }}</td>
                        <td><a href="{{ route('supplier-invoices.show', $invoice->id) }}">View</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- $invoice->links('vendor.pagination.bulma') --}}
    </div>
</div>
@endsection
