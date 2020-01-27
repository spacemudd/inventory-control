{{-- Items --}}
<div class="row">
    <div class="col-12-sm">
        <table class="pure-table pure-table-bordered tight-table" style="font-size:12px;">
            <colgroup>
            <col style='width:10.67%;'>
            <col style='width:16.67%;'>
            <col style='width:31.67%;'>
            <col style='width:16.67%;'>
            <col style='width:7.67%;'>
            <col style='width:16.67%;'>
        </colgroup>
        <thead>
            <tr>
                <th class="center">No.</th>
                <th class="center">Quotation No.</th>
                <th class="center">Description</th>
                <th class="center">Price</th>
                <th class="center">Qty</th>
                <th class="center">
                    T/Price (SAR)
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach($data->lines as $counter => $item)
            @if(! ($counter ===  (count($data->lines) - 1) || $counter ===  (count($data->lines) - 2)) )
            <tr style="border-top:2px #cbcbcb solid;font-size:12px;">
                <td style="padding:10px;" class="center">{{ ++$counter }}</td>
                <td style="padding:10px;">{{ $data->quotation_number }}</td>
                <td style="padding:10px;">{{ $item->description }}</td>
                <td style="padding:10px;" class="right">{{ number_format($item->unit_price, 2) }}</td>
                <td style="padding:10px;" class="right">{{ $item->qty }}</td>
                <td style="padding:10px;" class="right">{{ $item->total_price }}</td>
            </tr>
            @endif
            @endforeach
        </tbody>
    </table>

    <div style="page-break-inside: avoid !important;display:block;">
        <table class="pure-table pure-table-bordered tight-table">
            <colgroup>
                <col style='width:10.67%;'>
                <col style='width:16.67%;'>
                <col style='width:31.67%;'>
                <col style='width:16.67%;'>
                <col style='width:7.67%;'>
                <col style='width:16.67%;'>
            </colgroup>
            <tbody>
                @foreach($data->lines as $counter => $item)
                @if(($counter ===  (count($data->lines) - 1) || $counter ===  (count($data->lines) - 2)))
                <tr style="border-top:2px #cbcbcb solid;font-size:12px;">
                    <td style="padding:10px;" class="center">{{ ++$counter }}?</td>
                    <td style="padding:10px;">{{ $data->quotation_number }}</td>
                    <td style="padding:10px;">{{ $item->description }}</td>
                    <td style="padding:10px;" class="right">{{ number_format($item->unit_price, 2) }}</td>
                    <td style="padding:10px;" class="right">{{ $item->qty }}</td>
                    <td style="padding:10px;" class="right">{{ $item->total_price }}</td>
                </tr>
                @endif
                @endforeach
                <tr style="font-size:12px;">
                    <td style="padding:5px;" colspan="5" class="right">Total</td>
                    <td style="padding:5px;" class="right">{{ number_format($data->total_price, 2) }}</td>
                </tr>
                <tr style="font-size:12px;">
                    <td style="padding:5px;" colspan="5" class="right">VAT 5%</td>
                    <td style="padding:5px;" class="right">{{ number_format($data->vat, 2) }}</td>
                </tr>
                <tr style="font-size:12px;">
                    <td style="padding:5px;" colspan="5" class="right"><strong>Grand Total</strong></td>
                    <td style="padding:5px;" class="right"><strong>{{ number_format($data->grand_total, 2) }}</strong></td>
                </tr>
                <tr>
                    <td colspan="6"><p style="font-size:10px;"><b>Purpose of request:</b> {{ $data->purpose_of_request }}</p></td>
                </tr>
            </tbody>
        </table>
        @if (!$data->due_diligence_approved)
            <p style="margin-top:0px;font-size:10px;"><b>Due diligence approved</b></p>
        @endif

        <div class="row">
            <div class="col-12-sm" style="margin-top:-10px;">          
                {{-- Signatures block --}}
                @component('pdf.cost-approvals.signatures-block', ['data' => $data])
                @endcomponent
            </div>
        </div>
    </div>

</div>
</div>
