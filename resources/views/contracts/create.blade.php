@extends('layouts.app', ['title' => 'Contract'])

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
                <a href="{{ route('contracts.index') }}">
                    <span class="icon is-small"><i class="fa fa-file"></i></span>
                    <span>Contracts</span>
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
    <form action="{{ route('contracts.store') }}" method="post">
        {{ csrf_field() }}

        <div class="columns">
            <div class="column is-3"><p class="title is-5">General</p></div>
        </div>

        <div class="columns">
            <div class="column is-6">
                <div class="field">
                    <label class="label has-text-weight-normal is-small">Contract Number</label>
                    <div class="control">
                        <input name="number" class="input is-small" type="text" value="{{ old('number') }}" autofocus>
                        <span class="help">When not filled, it will be auto-generated.</span>
                        @if ($errors->has('issued_at'))
                            <span class="help is-danger">
                                {{ $errors->first('issued_at') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="columns">
            <div class="column is-3">
                <div class="field">
                    <label class="label has-text-weight-normal is-small">Contract Date <span class="has-text-danger">*</span></label>
                    <div class="control">
                        <input name="issued_at" class="input is-small" type="date" value="{{ now()->firstOfMonth()->format('Y-m-d') }}" autofocus>
                        @if ($errors->has('issued_at'))
                            <span class="help is-danger">
                                {{ $errors->first('issued_at') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="column is-3">
                <div class="field">
                    <label class="label has-text-weight-normal is-small">Contract Expiry Date <span class="has-text-danger">*</span></label>
                    <div class="control">
                        <input name="expires_at" class="input is-small" type="date" value="{{ now()->addYear()->firstOfMonth()->format('Y-m-d') }}" autofocus>
                        @if ($errors->has('expires_at'))
                            <span class="help is-danger">
                                {{ $errors->first('expires_at') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="columns">
            <div class="column is-3">
                <div class="field">
                    <label class="label has-text-weight-normal is-small">Cost Center</label>
                    <div class="control">
                        <select-department name="cost_center_id"
                                           input-class="is-small"
                                           url="{{ route('api.search.department') }}">
                        </select-department>
                        <div class="is-flex" style="justify-content:space-between">
                            <p class="help">Search by department code or name</p>
                            <button style="margin-top:5px;height:20px;border-color:#078af3;" type="button" class="is-small button" @click="$store.commit('Department/showNewModal', true)">
                                New
                            </button>
                        </div>
                        @if ($errors->has('cost_center_id'))
                            <span class="help is-danger">
								{{ $errors->first('cost_center_id') }}
							</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="column is-3">
                <div class="field">
                    <label class="label has-text-weight-normal is-small">Supplier <span class="has-text-danger">*</span></label>
                    <div class="control">
                        <select-vendors name="vendor_id"
                                        input-class="is-small"
                                        url="{{ route('api.search.vendor') }}">
                        </select-vendors>

                        <span class="help">Search by code or name</span>
                        @if ($errors->has('vendor_id'))
                            <span class="help is-danger">
								{{ $errors->first('vendor_id') }}
							</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="column is-3">
                <div class="field">
                    <label class="label has-text-weight-normal is-small">Supplier Reference No. <span class="has-text-danger">*</span></label>
                    <div class="control">
                        <input name="vendor_reference" class="input is-small" type="text" value="{{ old('vendor_reference') }}" required>
                        @if ($errors->has('vendor_reference'))
                            <span class="help is-danger">
                                {{ $errors->first('vendor_reference') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="columns">
            <div class="column is-6">
                <div class="field">
                    <label class="label has-text-weight-normal is-small">Remarks</label>
                    <div class="control">
                        <textarea name="remarks" class="textarea is-small" type="text" value="{{ old('remarks') }}"></textarea>
                        @if ($errors->has('remarks'))
                            <span class="help is-danger"></span>
                                {{ $errors->first('remarks') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="columns">
            <div class="column is-3"><p class="title is-5">Payments</p></div>
        </div>

        <div class="columns">
            <div class="column is-3">
                <div class="field">
                    <label class="label has-text-weight-normal is-small">Cost <span class="has-text-danger">*</span></label>
                    <div class="control">
                        <input name="cost" class="input is-small" type="number" step="0.01" min="0" value="{{ old('cost') }}" required>
                        <span class="help">Amount paid depending on frequency</span>
                        @if ($errors->has('cost'))
                            <span class="help is-danger">
                                {{ $errors->first('cost') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="column is-3">
                <div class="field">
                    <label class="label has-text-weight-normal is-small">Total Cost <span class="has-text-danger">*</span></label>
                    <div class="control">
                        <input name="total_cost" class="input is-small" type="number" step="0.01" min="0" value="{{ old('total_cost') }}" required>
                        <span class="help">The overall cost of the contract</span>
                        @if ($errors->has('total_cost'))
                            <span class="help is-danger">
                                {{ $errors->first('total_cost') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="column is-3">
                <div class="field">
                    <label class="label has-text-weight-normal is-small">Frequency <span class="has-text-danger">*</span></label>
                    <div class="control select is-fullwidth">
                        <select class="select is-small" name="payment_frequency" id="payment_frequency">
                            <option value="monthly" selected>Monthly</option>
                            <option value="yearly">Yearly</option>
                        </select>
                        @if ($errors->has('payment_frequency'))
                            <span class="help is-danger">
                                {{ $errors->first('payment_frequency') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- -- Form done. --}}
        <div class="column is-6 has-text-right">
            <a class="button is-text" href="{{ route('purchase-orders.index') }}">Cancel</a>
            <button type="submit" class="button is-primary">Save</button>
        </div>
    </form>
@endsection
