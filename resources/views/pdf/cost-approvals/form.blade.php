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

@if ($data->adhoc_quotations->count() > 1)
    @component('pdf.cost-approvals.lines-with-quotation', ['data' => $data])
    @endcomponent
@else
    @component('pdf.cost-approvals.lines', ['data' => $data])
    @endcomponent
@endif

</body>
</html>
