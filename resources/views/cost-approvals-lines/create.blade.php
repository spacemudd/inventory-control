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
            <li>
                <a href="{{ route('cost-approvals.index') }}">
                    <span class="icon is-small"><i class="fa fa-file"></i></span>
                    <span>Cost approvals</span>
                </a>
            </li>
        </ul>
    </nav>
@endsection

@section('content')
    <form action="{{ route('cost-approvals.lines.store', ['id' => $ca->id]) }}" method="post">
        {{ csrf_field() }}

        <div class="columns">
            <div class="column is-3"><p class="title is-5">Information</p></div>

            <div class="column is-4">
                <div class="field">
                    <label for="description" class="label">Description</label>

                    <div class="control">
                        <input type="text" class="input" name="description">
                    </div>
                </div>
            </div>

            <hr>

            <div class="column is-4">
                <div class="field">
                    <label for="vendor" class="label">Vendor</label>

                    <div class="control">
                        <input type="text" class="input" name="vendor">
                    </div>
                </div>
            </div>

            <hr>

            <div class="column is-4">
                <div class="field">
                    <label for="unit_price" class="label">Unit price</label>

                    <div class="control">
                        <input type="text" class="input" name="unit_price">
                    </div>
                </div>
            </div>

            <hr>

            <div class="column is-4">
                <div class="field">
                    <label for="qty" class="label">Qty</label>

                    <div class="control">
                        <input type="text" class="input" name="qty">
                    </div>
                </div>
            </div>

        </div>


        {{-- -- Form done. --}}
        <div class="column is-7 has-text-right">
            <a class="button is-text" href="{{ route('purchase-orders.index') }}">Cancel</a>
            <button type="submit" class="button is-primary">Save</button>
        </div>
    </form>
@endsection
