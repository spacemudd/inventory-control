@extends('layouts.app', ['title' => 'Adding equipment to contract'])

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
                    Add equipment
                </a>
            </li>
        </ul>
    </nav>
@endsection

@section('content')
    <form action="{{ route('contracts.equipments.store', $contract->id) }}" method="post">
        {{ csrf_field() }}

        <input type="hidden" name="contract_id" value="{{ $contract->id }}">

        <div class="columns">
            <div class="column is-3"><p class="title is-5">General</p></div>
        </div>

        <div class="columns">
            <div class="column is-3">
                <div class="field">
                    <label for="equipment_id" class="label">Equipment <span class="has-text-danger">*</span></label>
                    <equipments-search></equipments-search>
                    @if ($errors->has('equipment_id'))
                        <span class="help is-danger">
                        {{ $errors->first('equipment_id') }}
                    </span>
                    @endif
                </div>
            </div>
            <div class="column is-3">
                <location-selector-ui field-name="location_id"></location-selector-ui>
            </div>
        </div>

        {{-- -- Form done. --}}
        <div class="column is-6 has-text-right">
            <a class="button is-text" href="{{ route('contracts.show', $contract->id) }}">Cancel</a>
            <button type="submit" class="button is-primary">Save</button>
        </div>
    </form>
@endsection
