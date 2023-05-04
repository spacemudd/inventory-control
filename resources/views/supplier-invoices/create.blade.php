@extends('layouts.app', ['title' => 'Create invoice'])

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
                <a href="{{ route('supplier-invoices.index') }}">
                    <span class="icon is-small"><i class="fa fa-file"></i></span>
                    <span>Invoices</span>
                </a>
            </li>
            <li class="is-active">
                <a href="#">
                    New
                </a>
            </li>
        </ul>
    </nav>
@endsection

@section('content')
    <form action="{{ route('supplier-invoices.store') }}" method="post">
        {{ csrf_field() }}

        {{-- Cost center --}}
        <div class="columns">
            <div class="column is-3"><p class="title is-5">Information</p></div>
            <div class="column is-4">

                <div class="field">
                    <label for="po_number" class="label">P.O. #</label>

                    <div class="is-fullwidth">
                        <input type="text" class="input" name="po_number" value="{{ old('po_number') }}" required>
                    </div>
                </div>

                <hr>

                {{-- Vendor --}}
                <div class="field">
                    <label for="vendor_id" class="label">Supplier</label>

                    <div class="select is-fullwidth">
                        <select name="vendor_id" class="select is-fullwidth"  value="{{ old('vendor_id') }}" required>
                            <option></option>
                            @foreach ($vendors as $vendor)
                                <option value="{{ $vendor->id }}" {{ old("vendor_id") == $vendor->id ? "selected":"" }} >{{ $vendor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <hr>

                {{-- Invoice number of the supplier --}}
                <div class="field">
                    <label for="number" class="label">Invoice #</label>

                    <div class="is-fullwidth">
                        <input type="text" class="input" name="number" value="{{ old('number') }}" required>
                    </div>
                   
                    @if ($errors->first('number'))
                    <p class="has-text-danger">{{ $errors->first('number') }}</p>
                    @endif
                    
                </div>

                <hr>

                {{-- Date --}}
                <div class="field">
                    <label for="date" class="label">Proceeded date</label>

                    <div class="control">
                        <input type="date" class="input" name="proceeded_date" value="{{ old('proceeded_date') }}" required>
                    </div>
                   
                </div>

                <hr>

                {{-- Invoice number of the supplier --}}
                <div class="field">
                    <label for="number" class="label">Remarks</label>

                    <div class="is-fullwidth">
                        <input type="text" class="input" name="remarks" value="{{ old('remarks') }}" required>
                    </div>
                </div>


            </div>
        </div>


        {{-- -- Form done. --}}
        <div class="column is-7 has-text-right">
            <a class="button is-text" href="{{ route('supplier-invoices.index') }}">Cancel</a>
            <button type="submit" class="button is-primary">Save</button>
        </div>
    </form>
@endsection
