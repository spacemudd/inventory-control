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

<div style="page-break-inside: avoid !important;">
    <div class="row">
        <div class="col-12-sm" style="margin-top:-10px;">          
            {{-- Signatures block --}}
            @component('pdf.cost-approvals.signatures-block', ['data' => $data])
            @endcomponent
        </div>
    </div>

</div>
</body>
</html>
