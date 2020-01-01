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
                    <span class="icon is-small"><i class="fa fa-file"></i></span>
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
    <form action="{{ route('cost-approvals.store') }}" method="post">
        {{ csrf_field() }}

        {{-- Cost center --}}
        <div class="columns">
            <div class="column is-3"><p class="title is-5">Information</p></div>
            <div class="column is-4">

                <div class="requested_by_employee_id">
                    <label for="requested_by_id" class="label">Requested by</label>

                    <div class="control">
                        <select-employee name="requested_by_id"
                                         url="{{ route('api.search.employee') }}">
                        </select-employee>
                        <button type="button" class="is-small button is-text" @click="$store.commit('Employee/setNewEmployeeModal', true)">
                            New
                        </button>
                    </div>
                </div>

                <div class="field">
                    <label for="cost_center_id" class="label">Cost Center</label>

                    <div class="control">
                        <select-department name="cost_center_id"
                                           url="{{ route('api.search.department') }}">
                        </select-department>
                        <p class="help">Search by department code or name</p>
                        <button type="button" class="is-small button is-text" @click="$store.commit('Department/showNewModal', true)">
                            New
                        </button>
                    </div>
                </div>
                <hr>

                {{-- project location --}}
                <div class="field">
                    <label for="project_location" class="label">Project location</label>

                    <div class="control">
                        <input type="text" class="input" name="project_location">
                    </div>
                </div>

                <hr>


                {{-- Vendor --}}
                <div class="field">
                    <label for="vendor_id" class="label">Vendor</label>

                    <div class="select is-fullwidth">
                        <select name="vendor_id" class="select is-fullwidth">
                            <option></option>
                            @foreach ($vendors as $vendor)
                                <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <hr>

                
                {{-- Date --}}
                <div class="field">
                    <label for="date" class="label">Date</label>

                    <div class="control">
                        <input type="date" class="input" name="date">
                    </div>
                </div>

                <hr>

                {{-- Purpose of request --}}
                <div class="field">
                    <label for="purpose_of_request" class="label">Purpose of request</label>

                    <div class="control">
                        <input type="text" class="input" name="purpose_of_request">
                    </div>
                </div>

                {{-- Purpose of request --}}
                <div class="field">

                    <div class="control">
                        <b-checkbox name="due_diligence_approved" size="is-small">
                            Due diligence approved
                        </b-checkbox>
                    </div>

                    <!-- <div class="control">
                        <input type="text" class="input" name="purpose_of_request">
                    </div> -->
                </div>


            </div>
        </div>


        {{-- -- Form done. --}}
        <div class="column is-7 has-text-right">
            <a class="button is-text" href="{{ route('purchase-orders.index') }}">Cancel</a>
            <button type="submit" class="button is-primary">Save</button>
        </div>
    </form>
@endsection
