@extends('layouts.app', [
	'title' => $purchase_order->id . ' - ' .
			   trans('words.purchase-order')
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
				<a href="{{ route('purchase-orders.index') }}">
					<span class="icon is-small"><i class="fa fa-shopping-cart"></i></span>
					<span>{{ trans('words.purchase-orders') }}</span>
				</a>
			</li>

			<li class="is-active">
				<a href="{{ route('purchase-orders.show', ['id' => $purchase_order->id]) }}">
					{{ $purchase_order->number ? $purchase_order->number : $purchase_order->id }}
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
									@if($purchase_order->number)
										{{ $purchase_order->number }}
									@else
										<span class="has-text-grey-lighter">Draft</span>
									@endif
								</h1>
								<p class="subtitle is-6">
									Purchase Order Number
									@if(!$purchase_order->number)
										<b-tooltip label="Generated when saved"><span class="icon is-small"><i class="fa fa-question-circle"></i></span></b-tooltip>
									@endif
								</p>
							</div>

							{{-- Options --}}
							<div class="column has-text-right">
								<toggle-preview-purchase-order></toggle-preview-purchase-order>

								<button class="button is-small" @click="showNotesAndFilesSidebar=!showNotesAndFilesSidebar">
									Attachments and Notes
								</button>

								@can('create-purchase-orders')
									@if($purchase_order->is_draft)
										<a class="button is-small" href="{{ route('purchase-orders.edit', ['id' => $purchase_order->id]) }}">
											Edit
										</a>
									@endif
								@endcan

								@can('create-purchase-orders')
									@if($purchase_order->is_saved && !$purchase_order->supplier_invoice)
										<a class="button is-primary is-small" href="{{ route('purchase-orders.invoice.create', ['id' => $purchase_order->id]) }}">
											Submit invoice
										</a>
									@endif
								@endcan

								@can('create-purchase-orders')
									@if($purchase_order->is_draft)
										<form class="button is-warning is-small" action="{{ route('purchase-orders.save', ['id' => $purchase_order->id]) }}" method="post">
											{{ csrf_field() }}
											<button type="submit" class="button is-warning is-small">Save</button>
										</form>
									@endif
								@endcan

								@can('create-purchase-orders')
									@if($purchase_order->is_saved)
										<form class="button is-danger is-small" action="{{ route('api.purchase-orders.void', ['id' => $purchase_order->id]) }}" method="post">
											{{ csrf_field() }}
											<?php 
											$buttonlabelx = "Void";
					                        $messagex = "Are you sure you want to void this PO? This action is irreversible.";
					                        $idx = "deletebutton";
					                        ?>
											
											<confirmation-prompt
											:button_label="{{json_encode($buttonlabelx)}}"
					                        :message="{{json_encode($messagex)}}"
					                        :id="{{json_encode($idx)}}"
					                        >
					                        
					                        </confirmation-prompt>
					                        
											<button id="deletebutton" style="display:none;"  type="submit" class="button is-danger is-small">Void</button>
										</form>
									@endif
								@endcan

								@can('delete-purchase-orders')
									<form class="button is-danger is-small" action="{{ route('purchase-orders.destroy', ['id' => $purchase_order->id]) }}" method="post">
											{{ csrf_field() }}
											<input type="hidden" name="_method" value="delete">
											<button type="submit" class="button is-danger is-small">Delete</button>
										</form>
								@endcan
							</div>
						</div>

						<preview-pdf-container url="{{ route('purchase-orders.pdf', ['id' => $purchase_order->id]) }}"
											   show-type="PurchaseOrder/previewPdf"
						></preview-pdf-container>

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
											<td><strong>Date</strong></td>
											<td>{{ $purchase_order->date_string }}</td>
										</tr>
										<tr>
											<td><strong>Cost Center</strong></td>
											<td class="is-capitalized">{{ optional($purchase_order->cost_center)->display_name }}</td>
										</tr>
										<tr>
											<td><strong>Subject {{$purchase_order->cost_approval_id}}</strong></td>
											<td>
												<string-token :id.number="{{ $purchase_order->id }}"
															  name="subject"
															  :can-multiple-edit="{{auth()->user()->hasPermissionTo('edit-po-subject-after-approval')==1 ? 'true' : 'false'}}"
															  value="{{ $purchase_order->subject }}"
															  :highlighted="{{ $purchase_order->is_draft ? 'true' : 'false' }}"
															  placeholder="SUBJECT"
															  url="{{ route('purchase-orders.tokens', ['id' => $purchase_order->id]) }}"
															  :can-edit="{{ $purchase_order->is_draft ? 'true' : 'false' }}"
												></string-token>
											</td>
										</tr>
										<tr>
											<td><strong>Approver 1</strong></td>
											<td>
											@can('change-po-approvers')
												<select-approver-for-purchase-order
														purchase-order-id="{{ $purchase_order->id }}"
														selected-approver-id="{{ $purchase_order->approver_one_id }}"
														field-name="approver_one_id">
												</select-approver-for-purchase-order>
											@else
												{{ optional($purchase_order->approver_one)->name}}
											@endcan
												
											</td>
										</tr>
										<tr>
											<td><strong>Approver 2</strong></td>
											<td>
												
												
											@can('change-po-approvers')
												<select-approver-for-purchase-order
														purchase-order-id="{{ $purchase_order->id }}"
														selected-approver-id="{{ $purchase_order->approver_two_id }}"
														field-name="approver_two_id">
												</select-approver-for-purchase-order>
											@else
												{{ optional($purchase_order->approver_two)->name }}
											@endcan
											</td>
										</tr>
									</tbody>
								</table>
							</div>
							<div class="column is-6">
								<table class="table is-size-7 is-fullwidth">
									<colgroup>
										<col width="30%">
									</colgroup>
									<tbody>
									<tr>
										<td><strong>Supplier</strong></td>
										<td>{{ optional($purchase_order->vendor)->display_name }}</td>
									</tr>
									<tr>
										<td><strong>Quote #</strong></td>
										<td> 
										@foreach ($purchase_order->adhoc_quotations as $quotation)
				                            <p style="margin:0;padding:0">- {{ $quotation->quotation_number }}</p>
				                        @endforeach
										</td>
									</tr>
									<tr>
										<td>
											<strong>Status</strong>
										</td>
										<td class="is-capitalized">
											{{ $purchase_order->status_name }}
											@if($purchase_order->isDraft)
												<span class="circle is-warning"></span>
											@endif
											@if($purchase_order->isSaved)
												<span class="circle is-success"></span>
											@endif
											@if($purchase_order->isVoid)
												<span class="circle is-danger"></span>
											@endif
										</td>
									</tr>
									<tr>
										<td><strong>Created by</strong></td>
										<td>
											@if($purchase_order->created_by)
												{{ optional($purchase_order->created_by)->username }} - {{ optional($purchase_order->created_by)->name }}
											@endif
										</td>
									</tr>
									<tr>
										<td><strong>Remark</strong></td>
										<td>
											<form method="post" action="{{ route('purchase-orders.update', ['id' => $purchase_order->id]) }}">
												{{ csrf_field() }}
												<input type="hidden" name="_method" value="put">
												<textarea class="textarea" name="remarks">{{ $purchase_order->remarks }}</textarea>
												<div class="has-text-right">
													<button class="button is-small is-primary" style="margin-top: 5px;">Save</button>
												</div>
											</form>
										</td>
									</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			{{-- Items --}}
			<div class="columns">
				<div class="column">
					<div class="columns">
						<div class="column">
							<h2 class="title is-5 has-text-weight-light">Items</h2>
						</div>
					</div>
					<div class="box">
						<purchase-order-lines :lines="{{ json_encode($purchase_order->lines()->get()->toArray()) }}"
											  :editable="{{ $purchase_order->is_draft ? 'true' : 'false' }}"
											  :purchase-order-id="{{ $purchase_order->id }}"
											  created-at="{{ $purchase_order->created_at }}">
						</purchase-order-lines>
					</div>
				</div>
			</div>

			{{-- Invoice details --}}
			@if ((!$purchase_order->is_draft) && $purchase_order->supplier_invoice)
				<div class="columns">
					<div class="column">
						<div class="columns">
							<div class="column">
								<h2 class="title is-5 has-text-weight-light">Invoice</h2>
							</div>
						</div>
						<div class="box">
							<table class="table is-size-7 is-fullwidth">
								<colgroup>
									<col width="20%">
								</colgroup>
								<tbody>
								<tr>
									<td><strong>Invoice number</strong></td>
									<td>{{ $purchase_order->supplier_invoice->number }}</td>
								</tr>
								<tr>
									<td><strong>Proceeded date</strong></td>
									<td>{{ $purchase_order->supplier_invoice->proceeded_date->format('d-m-Y') }}</td>
								</tr>
								</tbody>
							</table>
							<hr>
							<purchase-order-invoice-lines :lines="{{ json_encode($purchase_order->lines()->get()->toArray()) }}"
														  :purchase-order-id="{{ $purchase_order->id }}"
														   created-at="{{ $purchase_order->created_at }}">
							</purchase-order-invoice-lines>
						</div>
					</div>
				</div>
			@endif

		</div>
		<transition enter-active-class="animated fadeInRight" mode="out-in">
			<div class="column" v-if="showNotesAndFilesSidebar">
				{{-- Notes section --}}
				<notes-container url="{{ route('api.purchase-orders.notes', ['id' => $purchase_order->id]) }}"
								 resource-id.number="{{ $purchase_order->id }}"
				>
				</notes-container>

				{{-- Uploads section --}}
				<uploads-container url="{{ route('api.purchase-orders.uploads', ['id' => $purchase_order->id]) }}"
								   resource-id.number="{{ $purchase_order->id }}"
				>
				</uploads-container>
			</div>
		</transition>
	</div>
@endsection
