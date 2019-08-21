@extends('layouts.app', ['title' => $mRequest->number])

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
                <a href="{{ route('material-requests.index') }}">
                    <span class="icon is-small"><i class="fa fa-shopping-cart"></i></span>
                    <span>Material Requests</span>
                </a>
            </li>
            <li class="is-active">
                <a href="#">
                    {{ $mRequest->number }}
                </a>
            </li>
        </ul>
    </nav>
@endsection

@section('content')
<div class="columns">
    <div class="column is-8 is-offset-2">
        <div class="box">
            <div class="columns">
                <div class="column is-6">
                    <span style="margin-left:0" class="tag">{{ $mRequest->status_name }}</span>
                    <p class="is-uppercase"><b>Request details</b></p>
                </div>
                <div class="column is-6 has-text-right">
                        <form class="is-inline-block" method="post" action="{{ route('material-requests.destroy', ['id' => $mRequest->id]) }}">
                            @csrf
                            <input type="hidden" name="_method" value="delete">
                            <button href="{{ route('material-requests.destroy', ['id' => $mRequest->id]) }}" class="button is-small is-danger">Delete</button>
                        </form>

{{--                    @if (!$mRequest->approved_at)--}}
                        <a class="button has-icon is-small" href="{{ route('material-requests.edit', ['id' => $mRequest->id]) }}">
                            <span class="icon"><i class="fa fa-pencil"></i></span>
                            <span>Edit</span>
                        </a>
{{--                    @endif--}}

                    @if (!$mRequest->approved_at)
                        <form action="{{ route('material-requests.approve', ['id' => $mRequest->id]) }}"
                              method="post"
                              class="is-inline">
                            @csrf
                            <button class="button is-success is-small"
                                  {!! $mRequest->items()->count() ? '' : 'disabled' !!}
                                    type="submit" id="materialApprove">Approve</button>
                        </form>
                    @endif

                    <a class="button has-icon is-small" href="{{ route('material-requests.downloadExcel', ['id' => $mRequest->id]) }}">
                        <span class="icon"><i class="fa fa-file-excel-o"></i></span>
                        <span>Excel</span>
                    </a>
                        <a @click="$store.commit('JobOrders/togglePreviewPdf')"
                           class="button has-icon is-small">
                            <span class="icon"><i class="fa fa-eye"></i></span>
                            <span>PDF</span>
                        </a>
                </div>
            </div>

            <div class="columns">
                <div class="column is-12">
                    <preview-pdf-container-for-material-request url="{{ $mRequest->id }}/pdf"
                                                                show-type="JobOrders/previewPdf">
                    </preview-pdf-container-for-material-request>
                </div>
            </div>

            <div class="columns is-multiline">
                <div class="column is-6">
                    <div class="field">
                        <label for="number" class="label">Reference #</label>
                        <input id="number"
                               type="text"
                               class="input"
                               name="number"
                               value="{{ $mRequest->number }}"
                               readonly>
                    </div>
                </div>
                <div class="column is-6">
                    <div class="field">
                        <label for="date" class="label">Date</label>

                        <p class="control">
                            <input id="date" type="text" class="input" value="{{ $mRequest->date->format('d-m-Y') }}" readonly>

                            @if ($errors->has('date'))
                                <span class="help is-danger">
                                {{ $errors->first('date') }}
                            </span>
                            @endif
                        </p>
                    </div>
                </div>
                <div class="column is-6">
                    <div class="field">
                        <div class="field">
                            <label for="cost_center_id" class="label">Cost Center</label>

                            <div class="control">
                                <input type="text" class="input" value="{{ $mRequest->cost_center->display_name }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column is-6">
                    <div class="field">
                        <label for="location" class="label">Location</label>

                        <div class="control">
                            <input type="text" class="input" value="{{ $mRequest->location->name }}" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="columns">
    <div class="column is-8 is-offset-2">
        <div class="box">
            <material-request-items-container
                    :can-edit="{{ $mRequest->can_edit ? 'true' : 'false' }}"
                    :material-request-id.number="{{ $mRequest->id }}">
            </material-request-items-container>
        </div>
    </div>
</div>
@endsection
