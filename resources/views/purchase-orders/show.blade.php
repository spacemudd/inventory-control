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
											<button type="submit" class="button is-danger is-small">Void</button>
										</form>
									@endif
								@endcan

								@can('delete-purchase-orders')
									@if($purchase_order->is_draft)
										<form class="button is-danger is-small" action="{{ route('purchase-orders.destroy', ['id' => $purchase_order->id]) }}" method="post">
											{{ csrf_field() }}
											<input type="hidden" name="_method" value="delete">
											<button type="submit" class="button is-danger is-small">Delete</button>
										</form>
									@endif
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
											<td><strong>Subject</strong></td>
											<td>
												{{ ucfirst($purchase_order->subject) }}
											</td>
										</tr>
										<tr>
											<td><strong>Approver 1</strong></td>
											<td>
												<select-approver-for-purchase-order
														purchase-order-id="{{ $purchase_order->id }}"
														selected-approver-id="{{ $purchase_order->approver_one_id }}"
														field-name="approver_one_id">
												</select-approver-for-purchase-order>
											</td>
										</tr>
										<tr>
											<td><strong>Approver 2</strong></td>
											<td>
												<select-approver-for-purchase-order
														purchase-order-id="{{ $purchase_order->id }}"
														selected-approver-id="{{ $purchase_order->approver_two_id }}"
														field-name="approver_two_id">
												</select-approver-for-purchase-order>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
							<div class="column is-6">
								<table class="table is-size-7 is-fullwidth">
									<colgroup>
										<col width="50%">
									</colgroup>
									<tbody>
									<tr>
										<td><strong>Supplier</strong></td>
										<td>{{ optional($purchase_order->vendor)->display_name }}</td>
									</tr>
									<tr>
										<td><strong>Quote #</strong></td>
										<td>{{ $purchase_order->quote_reference_number }}</td>
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
						{{--
                        <div class="column has-text-right">
                            @if($purchase_order->is_draft)
                                <new-po-item-from-pr-button :po-id.number="{{ $purchase_order->id }}"></new-po-item-from-pr-button>
                            @endif
                        </div>
                        --}}
					</div>

					{{-- Items --}}
					<div class="box">
						<purchase-order-lines :lines="{{ json_encode($purchase_order->lines()->get()->toArray()) }}"
											  :editable="false"
											  :purchase-order-id="{{ $purchase_order->id }}">
						</purchase-order-lines>
					</div>
				</div>
			</div>
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
