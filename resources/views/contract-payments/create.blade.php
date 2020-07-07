@extends('layouts.app', ['title' => 'Adding payment to contract'])

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
            <li>
                <a href="{{ route('contracts.show', $contract->id) }}">
                    {{ $contract->number ?: $contract->id }}
                </a>
            </li>
            <li class="is-active">
                <a href="#">
                    Add payment
                </a>
            </li>
        </ul>
    </nav>
@endsection

@section('content')
    <form action="{{ route('contracts.payments.store', $contract->id) }}" method="post">
        {{ csrf_field() }}

        <input type="hidden" name="contract_id" value="{{ $contract->id }}">

        <div class="columns">
            <div class="column is-3"><p class="title is-5">General</p></div>
        </div>

        <div class="columns">
            <div class="column is-3">
                <div class="field">
                    <label for="reference" class="label">Reference <span class="has-text-danger">*</span></label>
                    <input type="text" class="input is-small" name="reference" required>
                    <span class="help">Can be supplier's receipt number</span>
                    @if ($errors->has('reference'))
                        <span class="help is-danger">
                        {{ $errors->first('reference') }}
                    </span>
                    @endif
                </div>
            </div>
            <div class="column is-3">
                <div class="field">
                    <label for="issued_at" class="label">Date <span class="has-text-danger">*</span></label>
                    <input type="date" class="input is-small" name="issued_at" value="{{ now()->format('Y-m-d') }}" required>
                    <span class="help">The day the payment was completed</span>
                    @if ($errors->has('issued_at'))
                        <span class="help is-danger">
                        {{ $errors->first('issued_at') }}
                    </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="columns">
            <div class="column is-6">
                <div class="field">
                    <label for="cost" class="label">Amount paid <span class="has-text-danger">*</span></label>
                    <input type="number" step="0.01" min="0" class="input is-small" name="cost" required>
                    @if ($errors->has('cost'))
                        <span class="help is-danger">
                        {{ $errors->first('cost') }}
                    </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="column is-6 has-text-right">
            <a class="button is-text" href="{{ route('contracts.show', $contract->id) }}">Cancel</a>
            <button type="submit" class="button is-primary">Save</button>
        </div>
    </form>
@endsection
