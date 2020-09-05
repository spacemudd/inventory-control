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

{{-- Purchase and Supplier Information --}}
<div class="row">
    <div class="col-12-sm">
        <p style="font-size:20px;"><b>Sir,</b></p>
        @if ($data->adhoc_quotations()->count())
            @if ($data->adhoc_quotations()->count() > 1)
                <p style="font-size:20px;">With reference to your quotations:</p>
                <ul>
                    @foreach ($data->adhoc_quotations()->get() as $aq)
                        <li>{{ $aq->quotation_number }} - {{ $aq->created_at }}</li>
                    @endforeach
                </ul>
                <p style="font-size:20px;">for the below mentioned we confirm the acceptance for your offer amounting as follows,</p>
            @else
                <p style="font-size:20px;">With reference to your quotation <b>{{ $data->adhoc_quotations()->first()->quotation_number }}</b> dated on <b>{{ $data->adhoc_quotations()->first()->created_at }}</b> for the below mentioned we confirm the acceptance for your offer amounting as follows,</p>
            @endif
        @else
            @if ($data->quotation)
            <p style="font-size:20px;">With reference to your quotation <b>{{ $data->quotation->vendor_quotation_number }}</b> dated on <b>{{ $data->quotation->created_at->format('d-m-Y') }}</b> for the below mentioned we confirm the acceptance for your offer amounting as follows,</p>
            @elseif ($data->quote_reference_number)
                <p style="font-size:20px;">With reference to your quotation <b>{{ $data->quote_reference_number }}</b> for the below mentioned we confirm the acceptance for your offer amounting as follows,</p>
            @endif
        @endif
    </div>
</div>

@component('pdf.purchase-orders.lines', ['data' => $data])
@endcomponent

<div style="page-break-inside: avoid !important;">
    <div class="row">
        <div class="col-12-sm">
            <p style="font-size:20px;padding-bottom:0px;margin-bottom:0px;">Please deliver immediately,</p>
            <p style="font-size:20px;margin-top:10px;">Thanks and best regards,</p>
            

            {{-- Signatures block --}}
            @component('pdf.purchase-orders.signatures-block', ['data' => $data])
            @endcomponent
        </div>
    </div>

    {{-- Footer --}}
    {{--
    <div class="row" style="font-size:11px;">
        <div class="col-6-sm">
            <strong>arab national bank Saudi Stock Co. Paid up Capital S.R. 10,000 Million</strong><br/>
            P.O. Box 56921, Riyadh 11564 - Kingdom of Saudi Arabia<br/>
            Tel +966 11 402 9000 Fax +966 11 402 7747<br/>
        </div>
        <div class="col-6-sm right" dir="rtl">
            <strong>البنك العربي الوطني شركة مساهمة سعودية رأس المال المدفوع 10,000 مليون ريال</strong><br/>
            ص.ب. 56921، الـــريـــــــــــاض 11574 - المملــكــة العــربـــيـــة الـسـعـوديـــة<br/>
            تلفون <span dir="ltr">+966 11 402 9000</span> فاكس <span dir="ltr">+966 11 402 7747</span>
        </div>
    </div>
    --}}

{{-- End of page-break-inside: avoid --}}
</div>

</body>
</html>
