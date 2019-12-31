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
                    <span class="icon is-small"><i class="fa fa-document"></i></span>
                    <span>Cost approvals</span>
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
<div class="columns is-centered">
    <div class="column is-12">
        <div class="box">
            <p>Disabled</p>
            <!-- <p class="is-uppercase"><b>Cost approval</b></p>
            <new-cost-approval-form></new-cost-approval-form> -->
        </div>
    </div>
</div>
@endsection
