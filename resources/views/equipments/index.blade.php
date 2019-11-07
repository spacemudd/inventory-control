@extends('layouts.app', ['title' => 'Equipments'])

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
                    <span class="icon is-small"><i class="fa fa-cubes"></i></span>
                    <span>Equipments</span>
                </a>
            </li>
        </ul>
    </nav>
@endsection

@section('content')

    <div class="columns">
       <div class="column is-4">
            <p class="title is-6">
                <b>Equipments</b>
            </p>

            <div class="notification is-success">
                <p class="subtitle is-7">
                    <b>{{ $totalCount }}</b>
                </p>
            </div>
        </div>
    </div>

    <equipments-index></equipments-index>

@endsection
