@extends('layouts.app', ['title' => 'Regions'])

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
                <a href="{{ route('regions.index') }}">
                    <span class="icon is-small"><i class="fa fa-map"></i></span>
                    <span>Regions</span>
                </a>
            </li>
            <li class="is-active">
                <a href="{{ route('regions.create') }}">
                    <span>Create</span>
                </a>
            </li>
        </ul>
    </nav>
@endsection

@section('content')
    <div class="columns">
        <div class="column is-6">
            <div class="box">
                <p class="is-uppercase"><b>Region details</b></p>
                <form class="form" action="{{ route('regions.store') }}" method="post" style="margin-top:2rem">
                    @csrf
                    <div class="filed">
                        <label for="name" class="label">Region Name <span class="has-text-danger">*</span></label>

                        <input type="text" required id="name" name="name" class="input {{ $errors->has('name') ? ' is-danger' : '' }}">
                    </div>
                    <div class="field" style="margin-top:30px;">
                        <button class="button is-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
