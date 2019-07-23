@extends('layouts.app', ['title' => $quotation->vendor_quotation_number . ' - Edit'])

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
                <a href="{{ route('quotations.index') }}">
                    <span class="icon is-small"><i class="fa fa-shopping-cart"></i></span>
                    <span>Quotations</span>
                </a>
            </li>
            <li class="is-active">
                <a href="#">
                    {{ $quotation->vendor_quotation_number }}
                </a>
            </li>
        </ul>
    </nav>
@endsection

@section('content')

	@if(session()->has('success'))
		<div class="columns">
			<div class="column is-4 is-offset-4">
				<div class="notification is-primary">
					{{ session()->get('success') }}
				</div>
			</div>
		</div>
	@endif

	<form method="post" action="{{ route('quotations.update', ['id' => $quotation->id]) }}">
		@csrf
		<input type="hidden" name="_method" value="PUT" class="input">
		<div class="columns is-multiline">
			
			<div class="column is-4 is-offset-4">
				<div class="field">
				<label for="vendor_quotation_number" class="label">Quotation Number<span class="has-text-danger">*</span></label>
					<p class="control">
						<input id="vendor_quotation_number"
							   type="number"
							   class="input {{ $errors->has('vendor_quotation_number') ? ' is-danger' : '' }}"
							   name="vendor_quotation_number"
							   value="{{ $quotation->vendor_quotation_number }}"
							   autocomplete="off" 
							   required>

						@if ($errors->has('vendor_quotation_number'))
							<span class="help is-danger">
                                {{ $errors->first('vendor_quotation_number') }}
                            </span>
						@endif
					</p>
				</div>
			</div>
		<div class="column is-4 is-offset-4">
				<div class="field">
					<label for="material_request_number" class="label">Material Request Number<span class="has-text-danger">*</span></label>
					<p class="control">
						<input id="material_request_number"
							   type="string"
							   class="input {{ $errors->has('material_request_number') ? ' is-danger' : '' }}"
							   name="material_request_number"
							   value="{{ $quotation->material_request->number }}"
							   autocomplete="off" 
							   required>

						@if ($errors->has('material_request_number'))
							<span class="help is-danger">
                                {{ $errors->first('material_request_number') }}
                            </span>
						@endif
					</p>
				</div>
			</div>	
        <div class="column is-4 is-offset-4 has-text-right">
				<a class="button is-text" href="{{ url('/') }}">Cancel</a>
				<button type="submit" class="button is-success">Update</button>
		</div>

			
		</div>

	</form>

@endsection
