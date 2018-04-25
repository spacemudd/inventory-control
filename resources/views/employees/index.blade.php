@extends('layouts.app', ['title' => trans('words.employees')])

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
                <a href="{{ route('employees.index') }}">
                    <span class="icon is-small"><i class="fa fa-user"></i></span>
                    <span>{{ trans('words.employees') }}</span>
                </a>
            </li>
        </ul>
    </nav>
@endsection

@section('content')

    <div class="columns">
        <div class="column is-4">
            <p class="title is-6">
                <b>{{ trans('words.inactive') }} {{ trans('words.employees') }}</b>
            </p>

            <div class="notification is-warning">
                <p class="subtitle is-7">
                    <b>{{ $inactiveEmployees }}</b>
                </p>
            </div>
        </div>

        <div class="column is-4">
            <p class="title is-6">
                <b>{{ trans('words.active') }} {{ trans('words.employees') }}</b>
            </p>

            <div class="notification is-success">
                <p class="subtitle is-7">
                    <b>{{ $activeEmployees }}</b>
                </p>
            </div>
        </div>
    </div>

    <employees :can-create.number="{{ Auth::user()->can('create-employees') ? '1' : '0' }}"></employees>

@endsection
