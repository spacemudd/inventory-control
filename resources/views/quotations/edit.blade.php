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
                <a href="{{ route('quotations.show', ['id' => $quotation->id]) }}">
                    {{ $quotation->vendor_quotation_number }}
                </a>
            </li>
			<li class="is-active">
				<a href="#">
					Edit
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

	<div class="columns">
		<div class="column is-6">
			<div class="box">
				<p class="is-uppercase"><b>Quotation details</b></p>
				<form class="form" action="{{ route('quotations.update', ['id' => $quotation->id]) }}" method="post" style="margin-top:2rem;">
					@csrf
					<input type="hidden" name="_method" value="put">

					<div class="field">
						<label for="date" class="label">Material request number <span class="has-text-danger">*</span></label>

						<div class="control">
							<div class="select is-fullwidth">
								<select class="select{{ $errors->has('material_request_id') ? ' is-danger' : '' }}"
										name="material_request_id"
										required>
									<option value=""></option>
									@foreach ($mRequests as $request)
										<option value="{{ $request->id }}"{{ (int)$request->id===(int)$quotation->material_request_id ? ' selected' : '' }}>{{ $request->number }}</option>
									@endforeach
								</select>
							</div>
							@if ($errors->has('material_request_id'))
								<span class="help is-danger">
                                {{ $errors->first('material_request_id') }}
                            </span>
							@endif
						</div>
					</div>

					<div class="field">
						<label for="vendor_id" class="label">Vendor</label>

						<div class="control">
							<select-vendors name="vendor_id"
											:vendor-data="{{ json_encode($quotation->vendor) }}"
											url="{{ route('api.search.vendor') }}">
							</select-vendors>

							<p class="help">Click here to <a href="#" @click="$store.commit('Vendor/setNewModal', true)">add new vendor</a>.</p>

							@if ($errors->has('vendor_id'))
								<span class="help is-danger">
								{{ $errors->first('vendor_id') }}
							</span>
							@endif
						</div>
					</div>

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

					{{--
                    <div class="field">
                        <label for="region" class="label">Region <span class="has-text-danger">*</span></label>


                        <div class="control">
                            <div class="select is-fullwidth{{ $errors->has('location') ? ' is-danger' : '' }}">
                                <select name="region_id" id="region" required>
                                    @foreach ($regions as $region)
                                        <option value="{{ $region->id }}">{{ $region->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    --}}

					<div class="field">
						<a class="button is-text" href="{{ route('quotations.show', ['id' => $quotation->id]) }}">Cancel</a>
						<button type="submit" class="button is-success">Update</button>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection