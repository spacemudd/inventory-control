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
                <thead>
                <tr>
                    <th>Id</th>
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
                                <div class="buttons">
                                    <a href="{{ route('regions.show', $region) }}"
                                       class="button is-small is-warning"
                                       style="height:20px;">
                                        <span class="icon"><i class="fa fa-eye"></i></span>
                                        <span>View</span>
                                    </a>
                                </div>
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