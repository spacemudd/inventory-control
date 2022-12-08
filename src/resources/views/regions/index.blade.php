@extends('layouts.app', ['title' => 'Regions'])

@section('header')
    <nav class="breadcrumb" aria-label="breadcrumbs">
        <ul>
            <li>
                <a href="{{ route('dashboard.index') }}" aria-current="page">
                    <span class="icon is-small"><i class="fa fa-inbox"></i></span>
                    <span>{{ trans('words.dashboard') }}</span>
                </a>
            </li>
            <li class="is-active">
                <a href="{{ route('regions.index') }}">
                    <span class="icon is-small"><i class="fa fa-map"></i></span>
                    <span>Regions</span>
                </a>
            </li>
        </ul>
    </nav>
@endsection

@section('content')
    <div class="columns is-multiline">
        <div class="column is-12">
            <div class="has-text-right">
                <a href="{{ route('regions.create') }}" class="button is-primary is-small">Add new</a>
            </div>
        </div>
        <div class="column">
            <table class="table is-fullwidth is-bordered is-size-7 is-narrow">
                <colgroup>
                    <col style="width:1px;">
                    <col>
                    <col style="width:3rem;">
                </colgroup>
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Description</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @if ($regions)
                    @foreach ($regions as $region)
                        <tr>
                            <td>{{ $region->id }}</td>
                            <td>{{ $region->name }}</td>
                            <td class="has-text-centered">
{{--                                <a href="{{ route('regions.show', $region) }}"--}}
{{--                                   class="button is-small is-warning is-fullwidth"--}}
{{--                                   style="height:20px;">--}}
{{--                                    <span class="icon"><i class="fa fa-eye"></i></span>--}}
{{--                                    <span>View</span>--}}
{{--                                </a>--}}
                                <a href="{{ route('regions.edit', $region) }}"
                                   class="button is-small is-fullwidth is-warning"
                                   style="height:20px;margin-top:5px;">
                                    <span class="icon"><i class="fa fa-pencil"></i></span>
                                    <span>Edit</span>
                                </a>
                                <a href="{{ route('regions.destroy', $region) }}"
                                   class="button is-small is-fullwidth"
                                   onclick="event.preventDefault();document.getElementById('delete-form-{{ $region->id }}').submit()"
                                   style="height:20px;margin-top:10px;">
                                    <span class="icon"><i class="fa fa-trash"></i></span>
                                    <span>Delete</span>
                                </a>
                                <form id="delete-form-{{ $region->id }}" action="{{ route('regions.destroy', ['id' => $region->id]) }}"
                                      method="post"
                                      style="display: none;">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="_method" value="delete">
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="has-text-centered is-italic" colspan="8" style="background-color:#f5f5f5;">No Regions.</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>


    </div>
@endsection
