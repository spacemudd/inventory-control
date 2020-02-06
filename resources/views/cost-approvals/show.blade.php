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
                {{ $ca->id }}
            </a>
        </li>
    </ul>
</nav>
@endsection

@section('content')
<div class="box">
    <div class="columns is-multiline">
        <div class="column is-12 has-text-right">
            <a target="_blank" href="{{ route('cost-approvals.print', $ca->id) }}" class="button is-small is-secondary">Print</a>
            <form method="post" action="{{ route('cost-approvals.destroy', $ca->id) }}" class="is-inline">
                {{ @csrf_field() }}
                <input type="hidden" name="_method" value="delete">
                <button class="button is-danger is-small is-secondary">Delete</button>
            </form>
            @if (!$ca->number)
            <a href="{{ route('cost-approvals.save', $ca->id) }}" class="button is-small is-success">Save</a>
            @endif
        </div>
        <div class="column is-5">
            <table class="table is-small is-size-7 is-fullwidth">
                <colgroup>
                <col style="width:30%;">  
            </colgroup>
            <tbody>
                <tr>
                    <td><b>Number</b></td>
                    <td>{{ $ca->number ?: $ca->id }}</td>
                </tr>
                <tr>
                    <td><b>Date</b></td>
                    <td>{{ $ca->date->format('d-m-Y') }}</td>
                </tr>
                <tr>
                    <td><b>Requested by</b></td>
                    <td>{{ $ca->requested_by->display_name }}</td>
                </tr>
                <tr>
                    <td><b>Cost Center</b></td>
                    <td>{{ optional($ca->cost_center)->code }}</td>
                </tr>
                <tr>
                    <td><b>Project location</b></td>
                    <td>{{ optional($ca->cost_center)->description }}</td>
                </tr>
                <tr>
                    <td><b>Approval 1</b></td>
                    <td>
                            <select-approver-for-cost-approval 
                            cost-approval-id="{{ $ca->id }}"
                            selected-approver-id="{{ $ca->approver_one_id }}"
                            field-name="approver_one_id">
                            </select-approver-for-cost-approval>
                    </td>
                </tr>
                <tr>
                    <td><b>Approval 2</b></td>
                    <td>
                            <select-approver-for-cost-approval 
                            cost-approval-id="{{ $ca->id }}"
                            selected-approver-id="{{ $ca->approver_two_id }}"
                            field-name="approver_two_id">
                            </select-approver-for-cost-approval>
                    </td>
                </tr>
                <tr>
                    <td><b>Approval 3</b></td>
                    <td>
                            <select-approver-for-cost-approval 
                            cost-approval-id="{{ $ca->id }}"
                            selected-approver-id="{{ $ca->approver_three_id }}"
                            field-name="approver_three_id">
                            </select-approver-for-cost-approval>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="column is-5 is-offset-1">
        <table class="table is-small is-size-7 is-fullwidth">
            <colgroup>
            <col style="width:30%;">  
        </colgroup>
        <tbody>
            <tr>
                <td><b>Vendor</b></td>
                <td>{{ optional($ca->cost_center)->description }}</td>
            </tr>
            <tr>
                <td><b>Quotation #</b></td>
                <td>{{ $ca->quotation_number }}</td>
            </tr>
            <tr>
                <td><b>Due dilegence approved</b></td>
                <td>{{ $ca->due_dilgenced_approved ? 'Yes' : 'No' }}</td>
            </tr>
            <tr>
                <td><b>Purpose of request</b></td>
                <td>{{ $ca->purpose_of_request }}</td>
            </tr>
        </tbody>
    </table>
</div>

</div>
</div>

<div class="column is-12">
    <cost-approval-items :cost-approval-id="{{ $ca->id }}"></cost-approval-items>
</div>
@endsection
