@extends('layouts.app', [
	'title' => $invoice->id . ' - Invoice' 
			   
])

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
				<a href="{{ route('supplier-invoices.index') }}">
					<span class="icon is-small"><i class="fa fa-shopping-cart"></i></span>
					<span>Hellow</span>
				</a>
			</li>

			<li class="is-active">
				<a href="{{ route('supplier-invoices.show', ['id' => $invoice->id]) }}">
					{{ $invoice->number }}
				</a>
			</li>
		</ul>
	</nav>
@endsection


@section('content')

	<div class="columns">
		<div class="column" :class="showNotesAndFilesSidebar ? 'is-9' : 'is-12'">
			<div class="columns">
				<div class="column">
					<div class="box">
						<div class="columns">
							<div class="column is-6">
								{{-- todo: notification/subscription bell --}}
								<h1 class="title">
									@if($invoice->number)
										{{ $invoice->number }}
									@else
										<span class="has-text-grey-lighter">-</span>
									@endif
								</h1>
								<p class="subtitle is-6">
									Invoice Number
									
								</p>
							</div>
							
						</div>


						<hr>

						{{-- Resource' table info --}}
						<div class="columns">
							<div class="column is-6">
								<table class="table is-size-7 is-fullwidth">
									<colgroup>
										<col width="30%">
									</colgroup>
									<tbody>
										<tr>
											<td><strong>No.</strong></td>
											<td>{{ $invoice->number }}</td>
										</tr>
										<tr>
											<td><strong>Supplier</strong></td>
											<td>{{ $invoice->vendors->name }}</td>
										</tr>
										<tr>
											<td><strong>Proceeded Date</strong></td>
											<td>{{ optional($invoice->proceeded_date)->format('d-m-Y') }}</td>
										</tr>
										<tr>
											<td><strong>P.O Number</strong></td>
											<td>{{ $invoice->purchase_order == null ? $invoice->po_number : $invoice->purchase_order->number }}</td>
										</tr>
										
										<?php 
					                       	 $newval = $invoice->number;
					                     ?>
                        
										<tr>
											<td><strong>Option</strong></td>
											<td>@can('edit-invoices')
						                        	<a @click="$store.commit('SupplierInvoices/showEditInvoiceUpdateModal', true); $store.commit('SupplierInvoices/id', {{json_encode(['number'=>$newval, 'uid'=>$invoice->id])}});" class="button is-small">Edit</a>
						                        	
						                        	<form  class="is-inline-block" method="post" action="{{ route('supplier-invoices.destroy', ['id' => $invoice->id]) }}">
							                        	{{ @csrf_field() }}
							                        	
							                        	<?php 
															$buttonlabelx = "Delete";
												            $messagex = "Are you sure you want to delete Invoice $invoice->number? This action is irreversible.";
												            $idx = $invoice->id.$invoice->number;
												         ?>
							                        	<input type="hidden" name="_method" value="delete">
							                        	<confirmation-prompt
									                        :button_label="{{json_encode($buttonlabelx)}}"
									                        :message="{{json_encode($messagex)}}"
									                        :id="{{json_encode($idx)}}"
									                        >
									                        
									                        </confirmation-prompt>
							                        
							                        	<button style="display:none;"  id="{{$idx}}" href="{{ route('supplier-invoices.destroy', ['id' => $invoice->id]) }}" class="button is-small is-danger">Delete</button>
							                        	</form>
						                         @endcan</td>
										</tr>
										
										
										
								
									</tbody>
								</table>
							</div>
								
							</div>
						</div>
					</div>
				</div>
			</div>
			

			<supplier-invoice-update>
    
    	    </supplier-invoice-update>
		</div>

@endsection
