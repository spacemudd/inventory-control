@extends('layouts.app', ['title' => $quotation->number])

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
<div class="columns">
    <div class="column is-8 is-offset-2">
        <div class="box">
            {{-- Actions --}}
            <div class="columns">
                <div class="column is-6">
                    <span style="margin-left:0" class="tag">{{ $quotation->status_name }}</span>
                    <p class="is-uppercase"><b>Quotation details</b>
                    </p>
              </div>
                <div class="column is-6 has-text-right">
                    <form  class="is-inline-block" method="post" action="{{ route('quotations.destroy', ['id' => $quotation->id]) }}">
                        @csrf
                        
                        <?php
                        $buttonlabelx = "Delete";
                        $messagex = "Are you sure you want to delete this Quotation? This action is irreversible.";
                        $idx = "deletebutton";
                        ?>
                        <input type="hidden" name="_method" value="delete">
                        <confirmation-prompt
                        :button_label="{{json_encode($buttonlabelx)}}"
                        :message="{{json_encode($messagex)}}"
                        :id="{{json_encode($idx)}}"
                        >
                        
                        </confirmation-prompt>
                        <button style="display:none;" id="deletebutton" href="{{ route('quotations.destroy', ['id' => $quotation->id]) }}" class="button is-small is-danger">Delete</button>
                    </form>

                    <a href="{{ route('quotations.edit', ['quotation_number' => $quotation->id]) }}" class="button is-small is-grey">Edit</a>
                    @if ((int) $quotation->status != \App\Models\Quotation::SAVED)
                        <form action="{{ route('quotations.save', ['id' => $quotation->id]) }}"
                              method="post"
                              class="is-inline">
                            @csrf
                            <button id="quotationSaveItems" class="button is-success is-small"
                                    {!! $quotation->items()->count() ? '' : 'disabled' !!}
                                    type="submit">Save</button>
                        </form>
                    @endif
                    {{--
                    TODO: Add button to show Excel export.
                    <a href="{{ route('material-requests.excel', ['id' => $mRequest->id]) }}"
                       class="button has-icon is-small">
                        <span class="icon"><i class="fa fa-file-excel-o"></i></span>
                        <span>Excel</span>
                    </a>
                    --}}
                </div>
            </div>



            <form class="form" style="margin-top:2rem">

                <div class="field">
                    <div class="field">
                        <label for="material_request_number" class="label">Material Request Number</label>
                        <div class="control">
                            <input type="text" class="input" value="{{ $quotation->material_request->number }}" readonly>
                        </div>
                    </div>
                </div>

                <div class="field">
                    <div class="field">
                        <label for="vendor" class="label">Vendor</label>

                        <div class="control">
                            <input type="text" class="input" value="{{ optional($quotation->vendor)->display_name }}" readonly>
                        </div>
                    </div>
                </div>

                <div class="field">
                    <div class="field">
                        <label for="cost_center_id" class="label">Quotation Number</label>

                        <div class="control">
                            <input type="text" class="input" value="{{ $quotation->vendor_quotation_number }}" readonly>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="columns">
    <div class="column is-8 is-offset-2">
        <div class="box">
            <add-material-request-items-container-quotations-item
                    :quotation-item-id="{{ $quotation->items }}"
                    :can-edit="{{ $mRequest->can_edit ? 'true' : 'false' }}"
                    :material-request-id.number="{{ $mRequest->id }}">
            </add-material-request-items-container-quotations-item>
        </div>
    </div>
</div>
<div class="columns">
    <div class="column is-8 is-offset-2">
        <div class="box">
            {{-- TODO: Can-edit will be disabled when there is a PO attached to it? --}}
            <quotations-items-container
                    material-number="{{ $quotation->material_request->number }}"
                    {{-- :can-edit="{{ $quotation->can_edit ? 'false' : 'true' }}"--}}
                    :can-edit="true"
                    :quotation-id.number="{{ $quotation->id }}">
            </quotations-items-container>
        </div>
    </div>
</div>
@endsection
