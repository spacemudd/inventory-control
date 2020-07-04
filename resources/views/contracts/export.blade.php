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
                    Export
                </a>
            </li>
        </ul>
    </nav>
@endsection

@section('content')
    <div class="columns">
        <div class="column">
            <p class="title is-5">Selection</p>

            <form action="{{ route('contracts.export.excel') }}" method="post">
                {{ @csrf_field() }}
                <div class="columns">
                    <div class="column is-3">
                        <div class="field">
                            <label class="label has-text-weight-normal">Date from</label>
                            <div class="control">
                                <input name="date_from" class="input is-small" value="{{ now()->firstOfMonth()->format('Y-m-d') }}" type="date">
                            </div>
                        </div>
                    </div>
                    <div class="column is-3">
                        <div class="field">
                            <label class="label has-text-weight-normal">Date to</label>
                            <div class="control">
                                <input name="date_to" class="input is-small" value="{{ now()->endofMonth()->format('Y-m-d') }}" type="date">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- -- Form done. --}}
                <div class="column is-6 has-text-right">
                    <a class="button is-text" href="{{ route('contracts.index') }}">Cancel</a>
                    <button type="submit" class="button is-primary">Download</button>
                </div>
            </form>
        </div>
    </div>
@endsection
