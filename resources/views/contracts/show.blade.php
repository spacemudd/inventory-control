@extends('layouts.app', ['title' => 'Contracts - ' . $contract->number ?: $ $contract->id])

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
                {{ $contract->number ?: $contract->id }}
            </a>
        </li>
    </ul>
</nav>
@endsection

@section('content')
<div class="box">
    <div class="columns is-multiline">
        <div class="column is-12 has-text-right">
            {{--<a target="_blank" href="{{ route('cost-approvals.print', $contract->id) }}" class="button is-small is-secondary">Print</a>--}}
            @can('delete-contracts')
                <form method="post" action="{{ route('contracts.destroy', $contract->id) }}" class="is-inline">
                    {{ @csrf_field() }}
                    <input type="hidden" name="_method" value="delete">
                    <button class="button is-danger is-small is-secondary">Delete</button>
                </form>
            @endcan
{{--            @if (!$contract->number)--}}
{{--                <a href="{{ route('cost-approvals.save', $contract->id) }}" class="button is-small is-success">Save</a>--}}
{{--            @endif--}}
        </div>
        <div class="column is-5">
            <table class="table is-small is-size-7 is-fullwidth">
                <colgroup>
                <col style="width:30%;">  
            </colgroup>
            <tbody>
                <tr>
                    <td><b>Number</b></td>
                    <td>{{ $contract->number ?: $contract->id }}</td>
                </tr>
                <tr>
                    <td><b>Cost Center</b></td>
                    <td>{{ optional($contract->cost_center)->display_name }}</td>
                </tr>
                <tr>
                    <td><b>Contract total cost</b></td>
                    <td>{{ number_format($contract->total_cost, 2) }}</td>
                </tr>
                <tr>
                    <td><b>Payment interval</b></td>
                    <td><span style="color:#898989;">{{ ucfirst($contract->payment_frequency) }}</span> - {{ number_format($contract->cost, 2) }}</td>
                </tr>
                <tr>
                    <td><b>Duration</b></td>
                    <td>
                        {{ optional($contract->issued_at)->format('d-m-Y') }} <span style="color:red;">&rarr;</span> {{ optional($contract->expires_at)->format('d-m-Y') }}
                    </td>
                </tr>
                <tr>
                    <td><b>Paid so far</b></td>
                    <td>{{ number_format($contract->payments()->sum('cost'), 2) }}</td>
                </tr>
                <tr>
                    <td><b>Remaining</b></td>
                    <td>{{ number_format($contract->total_cost - $contract->payments()->sum('cost'), 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
        <div class="column is-5 is-offset-1">
        <table class="table is-small is-size-7 is-fullwidth">
            <colgroup>
            <col style="width:40%;">
        </colgroup>
        <tbody>
            <tr>
                <td><b>Supplier</b></td>
                <td>{{ optional($contract->vendor)->display_name }}</td>
            </tr>
            <tr>
                <td><b>Supplier Reference Number</b></td>
                <td>{{ $contract->vendor_reference }}</td>
            </tr>
            <tr>
                <td><b>Supplier Reference Number</b></td>
                <td>{{ $contract->vendor_reference }}</td>
            </tr>
            <tr>
                <td><b>Remarks</b></td>
                <td>{{ $contract->remarks }}</td>
            </tr>
        </tbody>
    </table>
</div>
    </div>
</div>

<div class="columns">
    <div class="column is-6">
        <p><b>Equipments</b></p>
    </div>
    <div class="column is-6 has-text-right">
        @if ($contract->status == \App\Models\Contract::STATUS_DRAFT)
            <a class="button is-small is-primary" href="{{ route('contracts.equipments.create', $contract->id) }}">Add</a>
        @endif
    </div>
</div>
<div class="box">
    <div class="columns">
        <div class="column is-12">
        @if (!$contract->equipments()->count())
            <p style="text-align: center; font-size: 12px;"><i>There are no equipments attached</i></p>
        @else
            <table class="table is-small is-size-7 is-fullwidth">
                <colgroup>
                    <col style="width:30%;">
                </colgroup>
            <thead>
            <tr>
            	<th>Equipment</th>
                <th>Location</th>
            </tr>
            </thead>
            	<tbody>
                    @foreach ($contract->equipments as $equip)
            			<tr>
            				<td>{{ $equip->name }}</td>
                            <td>{{ optional(\App\Models\Location::find($equip->pivot->location_id))->name }}</td>
            			</tr>
                    @endforeach
            	</tbody>
            </table>
        @endif
    </div>
    </div>
</div>

<div class="columns">
    <div class="column is-6">
        <p><b>Payments</b></p>
    </div>
    <div class="column is-6 has-text-right">
        <a class="button is-small is-primary" href="{{ route('contracts.payments.create', $contract->id) }}">Add</a>
    </div>
</div>
<div class="box">
    <div class="column is-12">
        @if (!$contract->payments()->count())
            <p style="text-align: center; font-size: 12px;"><i>There are no payments</i></p>
        @else
            <table class="table is-small is-size-7 is-fullwidth">
                <colgroup>
                    <col style="width:30%;">
                </colgroup>
                <thead>
                <tr>
                    <th>Reference</th>
                    <th>Date</th>
                    <th class="has-text-right">Amount</th>
                </tr>
                </thead>
                <tbody>
                    @foreach ($contract->payments as $payment)
                        <tr>
                            <td>{{ $payment->reference }}</td>
                            <td>{{ $payment->issued_at->format('d-m-Y') }}</td>
                            <td class="has-text-right">{{ number_format($payment->cost, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection
