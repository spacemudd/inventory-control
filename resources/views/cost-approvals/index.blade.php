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
            <li class="is-active">
                <a href="{{ route('cost-approvals.index') }}">
                    <span class="icon is-small"><i class="fa fa-file"></i></span>
                    <span>Cost approvals</span>
                </a>
            </li>
            @if (request()->has('all'))
                <li class="is-active">
                    <a href="{{ route('cost-approvals.index') }}">
                        All
                    </a>
                </li>
            @endif
        </ul>
    </nav>
@endsection

@section('content')
<div class="columns is-multiline">
    <div class="column is-12">
        <div class="has-text-right">
            <div>
                <a href="{{ route('cost-approvals.create') }}" class="button is-primary is-small">Add new</a>
            </div>
        </div>
    </div>
</div>
@endsection
