@extends('layouts.app', [
	'title' => trans('words.reports')
])

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
                <a href="{{ route('reports.index') }}">
                    <span class="icon is-small"><i class="fa fa-bar-chart"></i></span>
                    <span>{{ trans('words.reports') }}</span>
                </a>
            </li>
        </ul>
    </nav>
@endsection


@section('content')

    <div class="columns is-multiline">
        <div class="column is-4">
            <p class="is-size-5">
                <span class="icon"><i class="fa fa-barcode"></i></span>
                Stock
            </p>
            <div class="content">
                <ul>
                {{--
                    <li><a href="{{ route('reports.stock.index') }}">Report builder</a></li>
                    <li><a href="{{ route('reports.stock.categories.excel') }}">Categories</a></li>
                   --}}
                </ul>
            </div>
        </div>

        <div class="column is-4">
            <p class="is-size-5">
                <span class="icon"><i class="fa fa-file-text"></i></span>
                Asset Issuances
            </p>
            <div class="content">
               {{--  <ul>
                    <li><a href="{{ route('reports.asset-issuances.builder') }}">Report builder</a></li>
                </ul> --}}
            </div>
        </div>

        <div class="column is-4">
            <p class="is-size-5">
                <span class="icon"><i class="fa fa-handshake-o"></i></span>
                Issuance Returns
            </p>
            <div class="content">
           {{-- <ul>
                    <li><a href="{{ route('reports.issuance-returns.builder') }}">Report builder</a></li>
                </ul> --}}
            </div>
        </div>

        <div class="column is-4">
            <p class="is-size-5">
                <span class="icon"><i class="fa fa-file-text"></i></span>
                Purchase Orders
            </p>
            <div class="content">
            	<ul>
            		<li>
            			<a href="{{ route('purchase-orders-issued-to-suppliers') }}">POs issued to Suppliers</a>
            		</li>
            	</ul>
         {{--  <ul>
                    <li>
                        <build-report-generic-button
                                export-excel-url="{{ route('reports.purchase-orders.excel-backend') }}"
                                button-label="Export"
                                classes="link"
                        >
                        </build-report-generic-button>
                    </li>
                    <li>
                        <a href="{{ route('reports.purchase-orders.unreceived-items') }}">Unreceived items</a>
                    </li>
                </ul> --}}
            </div>
        </div>

        <div class="column is-4">
            <p class="is-size-5">
                <span class="icon"><i class="fa fa-user"></i></span>
                Employees
            </p>
            <div class="content">
          {{--      <ul>
                    <li><a href="{{ route('reports.employees.index') }}">Export</a></li>
                </ul> --}}
            </div>
        </div>

        <div class="column is-4">
            <p class="is-size-5">
                <span class="icon"><i class="fa fa-user"></i></span>
                Departments
            </p>
            <div class="content">
             {{--   <ul>
                    <li><a href="{{ route('reports.departments.index') }}">Export</a></li>
                </ul> --}}
            </div>
        </div>

        <div class="column is-4">
            <p class="is-size-5">
                <span class="icon"><i class="fa fa-truck"></i></span>
                Suppliers
            </p>
            <div class="content">
            {{--    <ul>
                    <li><a href="{{ route('reports.vendors.index') }}">Export</a></li>
                </ul> --}}
            </div>
        </div>

        <div class="column is-4">
            <p class="is-size-5">
                <span class="icon"><i class="fa fa-cubes"></i></span>
                Manufacturers
            </p>
            <div class="content">
         {{--       <ul>
                    <li><a href="{{ route('reports.manufacturers.excel') }}">Export</a></li>
                </ul> --}}
            </div>
        </div>
    </div>

    <hr>

    <reports-page></reports-page>

    <div class="columns">
        <div class="column is-4">
            <div class="block">
                <div class="panel">
                {{--    <div class="panel-heading">{{ __('words.user-activity') }}</div>
                    <div class="panel-block">
                        <a href="{{ route('reports.user-activities.index') }}" class="button is-primary">{{ __('words.show') }}</a>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>

@endsection
