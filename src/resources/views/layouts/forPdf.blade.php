<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="description" content="Purchasing management - Clarimount">
    {{-- <meta name="description" content="Always know where your assets are, with complete detailed logs, warranty alerts, service contracts alerts, time tracking, and more."> --}}

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="permissions" content='{!! auth()->user()->exposed_permissions !!}'>

    <title>{{ isset($title) ? $title . ' | Purchase Management' : 'Purchase Management' }}</title>

    <link rel="shortcut icon" type="image/x-icon" href="{{ url('/favicon.ico') }}" />

    </head>
    <body>
        <div id="app">
          @yield('content')
        </div>
        <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>
