<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $data->code }}</title>
    <style>
        @include('pdf.style')
    </style>
</head>
<body>

@component('pdf.cost-approvals.lines', ['data' => $data])
@endcomponent

</body>
</html>
