@extends('layouts.app', ['title' => $mRequest->number.' Edit Material Requests'])

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
            <li>
                <a href="{{ route('material-requests.show', ['id' => $mRequest->id]) }}">
                    {{ $mRequest->number }}
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
<div class="columns">
    <div class="column is-6">
        <div class="box">
            <p class="is-uppercase"><b>Request details</b></p>
            <form class="form" action="{{ route('material-requests.update', ['id' => $mRequest->id]) }}" method="post" style="margin-top:2rem">
                @csrf
                <input type="hidden" name="_method" value="put">

                <div class="field">
                    <label for="number" class="label">Reference #</label>
                    <input id="number"
                           type="text"
                           class="input {{ $errors->has('number') ? ' is-danger' : '' }}"
                           placeholder="If left blank, reference will be automatically generated"
                           name="number"
                           autocomplete="off"
                           value="{{ $mRequest->number }}">
                    <p class="control">
                        @if ($errors->has('number'))
                            <span class="help is-danger">
                                {{ $errors->first('number') }}
                            </span>
                        @endif
                    </p>
                </div>

                <div class="field">
                    <label for="date" class="label">Date <span class="has-text-danger">*</span></label>

                    <p class="control">
                        <select-date-for-material-request :new-val="{{ $mRequest->date->format('d-m-Y') }}">
                        </select-date-for-material-request>
                        @if ($errors->has('date'))
                            <span class="help is-danger">
                                {{ $errors->first('date') }}
                            </span>
                        @endif
                    </p>
                </div>

                <div class="field">
                    <div class="field">
                        <label for="cost_center_id" class="label">Cost Center <span class="has-text-danger">*</span></label>

                        <div class="control">
                            <select-department name="cost_center_id"
                                               :new-val="{{ json_encode($mRequest->cost_center) }}"
                                               url="{{ route('api.search.department') }}">
                            </select-department>
                            <p class="help">Click here to <a href="#" @click="$store.commit('Department/showNewModal', true)">add new department</a>.</p>
                            @if ($errors->has('cost_center_id'))
                                <span class="help is-danger">
                                   {{ $errors->first('cost_center_id') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>


                <div class="field">
                    <label for="location" class="label">Location <span class="has-text-danger">*</span></label>


                    <div class="control">
                        <div class="select is-fullwidth{{ $errors->has('location') ? ' is-danger' : '' }}">
                            <select name="location_id" id="location" required>
                                <option value=""></option>
                                @foreach ($locations as $location)
                                    <option value="{{ $location->id }}"{{ (int)$location->id===(int)$mRequest->location_id ? ' selected' : '' }}>{{ $location->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <span class="help">
                            Click here to <a target="_blank" @click="$store.commit('Location/showNewModal', true)">add new locations.</a>
                            To manage locations, <a target="_blank" @click="$store.commit('Location/toggleManageLocationsModal')">click here.</a>
                        </span>
                        <select-location name="location_id"
                                         url="{{ route('locations.create') }}">
                        </select-location>
                        @if ($errors->has('location_id'))
                            <span class="help is-danger">
                                {{ $errors->first('location_id') }}
                            </span>
                        @endif
                    </div>
                </div>

                <div class="field">
                    <label for="purpose" class="label">Purpose</label>
                    <input id="purpose"
                           type="text"
                           class="input {{ $errors->has('purpose') ? ' is-danger' : '' }}"
                           name="purpose"
                           value="{{ $mRequest->purpose }}">
                    <p class="control">
                        @if ($errors->has('purpose'))
                            <span class="help is-danger">
                                {{ $errors->first('purpose') }}
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

                <div class="is-flex">
                    <div class="field">
                        <a href="{{ route('material-requests.show', ['id' => $mRequest->id]) }}" class="button is-small is-text">Cancel</a>
                    </div>
                    <div class="field" style="margin-left:10px;">
                        <form method="post" action="{{ route('material-requests.destroy', ['id' => $mRequest->id]) }}" class="is-inline">
                            <input type="hidden" name="_method" value="delete">
                            <button href="{{ route('material-requests.destroy', ['id' => $mRequest->id]) }}" class="button is-small is-danger">Delete</button>
                        </form>
                    </div>
                    <div class="field" style="margin-left:10px;">
                        <button class="button is-small is-primary">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
