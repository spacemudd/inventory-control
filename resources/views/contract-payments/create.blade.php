@extends('layouts.app', ['title' => 'Adding payment to contract'])

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
                    Add payment
                </a>
            </li>
        </ul>
    </nav>
@endsection

@section('content')
    <form action="{{ route('contracts.payments.store', $contract->id) }}" method="post">
        {{ csrf_field() }}

        <input type="hidden" name="contract_id" value="{{ $contract->id }}">

        <div class="columns">
            <div class="column is-3"><p class="title is-5">General</p></div>
        </div>

        <div class="columns">
            <div class="column is-3">
                <div class="field">
                    <label for="reference" class="label">Reference <span class="has-text-danger">*</span></label>
                    <input type="text" class="input is-small" name="reference" required>
                    <span class="help">Can be supplier's receipt number</span>
                    @if ($errors->has('reference'))
                        <span class="help is-danger">
                        {{ $errors->first('reference') }}
                    </span>
                    @endif
                </div>
            </div>
            <div class="column is-3">
                <div class="field">
                    <label for="issued_at" class="label">Date <span class="has-text-danger">*</span></label>
                    <input type="date" class="input is-small" name="issued_at" value="{{ now()->format('Y-m-d') }}" required>
                    <span class="help">The day the payment was completed</span>
                    @if ($errors->has('issued_at'))
                        <span class="help is-danger">
                        {{ $errors->first('issued_at') }}
                    </span>
                    @endif
                </div>
            </div>
        </div>
        
         <div class="columns">
         	<div class="column is-6">
         		<div class="box">
				    <p style="margin-bottom:10px;"><b>Invoice Details</b></p>
					
					<div class="columns">
						<div class="column is-6">
							<div class="field">
			                    <label for="invoice_no" class="label">Invoice No. <span class="has-text-danger">*</span></label>
			                    <input type="text" class="input is-small" name="invoice_no" required>
			                    <span class="help">Source Invoice No. for which the payment is to be made</span>
			                    @if ($errors->has('invoice_no'))
			                        <span class="help is-danger">
			                        {{ $errors->first('invoice_no') }}
			                    </span>
			                    @endif
			                </div>
						</div>
						
						<div class="column is-6">
			                <div class="field">
			                    <label for="proceeded_date" class="label">Proceeded Date <span class="has-text-danger">*</span></label>
			                    <input type="date" class="input is-small" name="proceeded_date" value="{{ now()->format('Y-m-d') }}" required>
			                    <span class="help">Date when the Invoice was created</span>
			                    @if ($errors->has('proceeded_date'))
			                        <span class="help is-danger">
			                        {{ $errors->first('proceeded_date') }}
			                    </span>
			                    @endif
			                </div>
			            </div>
					</div>
					
					<div class="columns">
			            <div class="column is-6">
			                <div class="field">
			                    <label for="invoice_period_from" class="label">From <span class="has-text-danger">*</span></label>
			                    <input type="date" class="input is-small" name="invoice_period_from" value="{{ now()->format('Y-m-d') }}" required>
			                    <span class="help">Invoice Period (Beginning)</span>
			                    @if ($errors->has('invoice_period_from'))
			                        <span class="help is-danger">
			                        {{ $errors->first('invoice_period_from') }}
			                    </span>
			                    @endif
			                </div>
			            </div>
			            <div class="column is-6">
			                <div class="field">
			                    <label for="invoice_period_to" class="label">To <span class="has-text-danger">*</span></label>
			                    <input type="date" class="input is-small" name="invoice_period_to" value="{{ now()->format('Y-m-d') }}" required>
			                    <span class="help">Invoice Period (Ending)</span>
			                    @if ($errors->has('invoice_period_to'))
			                        <span class="help is-danger">
			                        {{ $errors->first('invoice_period_to') }}
			                    </span>
			                    @endif
			                </div>
			            </div>
			        </div>
			        
			        
			        <div class="columns is-multiline">
                        <div class="column is-9">
                            <div class="field">
                                <label for="cost" class="label">Amount paid (ex. VAT)<span class="has-text-danger">*</span></label>
                                <input type="number" step="0.01" min="0" class="input is-small" name="cost" required>
                                @if ($errors->has('cost'))
                                    <span class="help is-danger">
                                        {{ $errors->first('cost') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="column is-3">
                            <div class="field">
                                <label class="label" for="tax">VAT %</label>
                                <div class="select is-small is-fullwidth">
                                    <select name="tax_percentage" class="select">
                                        <option value="0.15">15%</option>
                                    </select>
                                </div>
                            </div>
                        </div>
			            {{--<div class="column is-12">--}}
			            {{--    <div class="field">--}}
			            {{--        <label for="invoice_tax_amount" class="label">Tax Invoice Amount <span class="has-text-danger">*</span></label>--}}
			            {{--        <input type="number" step="0.01" min="0" class="input is-small" name="invoice_tax_amount" required>--}}
			            {{--        @if ($errors->has('invoice_tax_amount'))--}}
			            {{--            <span class="help is-danger">--}}
			            {{--            {{ $errors->first('invoice_tax_amount') }}--}}
			            {{--        </span>--}}
			            {{--        @endif--}}
			            {{--    </div>--}}
			            {{--</div>--}}
			        </div>
					     
				</div>
         	</div>
         	
         </div>
        
			   
			    
	
			
			

        <div class="columns">

        </div>

        <div class="column is-6 has-text-right">
            <a class="button is-text" href="{{ route('contracts.show', $contract->id) }}">Cancel</a>
            <button type="submit" class="button is-primary">Save</button>
        </div>
    </form>
@endsection
