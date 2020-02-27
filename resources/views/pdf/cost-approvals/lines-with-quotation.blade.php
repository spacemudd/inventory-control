
{{-- Items --}}
<div class="row">
{{--    <div class="col-12-sm" style="margin-left:1px;">--}}
        <table class="pure-table pure-table-bordered tight-table" style="font-size:12px;border-color:black;width:100%;border-collapse: collapse">
            <colgroup>
                <col style='width:10%;'>
                <col style='width:12%;'>
                <col>
                <col style='width:12%;'>
                <col style='width:10%;'>
                <col style='width:14%;'>
        </colgroup>
        <thead>
            <tr>
                <th style="border-color:black;" class="center">No.</th>
                <th style="border-color:black;" class="center">Quote</th>
                <th style="border-color:black;" class="center">Description</th>
                <th style="border-color:black;" class="center">Price</th>
                <th style="border-color:black;" class="center">Qty</th>
                <th style="border-color:black;" class="center">
                    T/Price (SAR)
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach($data->lines as $counter => $item)
            @if(! ($counter ===  (count($data->lines) - 1) || $counter ===  (count($data->lines) - 2)) )
            <tr style="border-top:2px black solid;font-size:12px;">
                <td style="border-color:black;padding:10px;" class="center">{{ ++$counter }}</td>
                <td style="border-color:black;padding:10px;">{{ $item->quotation_number }}</td>
                <td style="border-color:black;padding:10px;">{{ $item->description }}</td>
                <td style="border-color:black;padding:10px;" class="right">{{ number_format($item->unit_price, 2) }}</td>
                <td style="border-color:black;padding:10px;" class="right">
                    @if ($item->lump_sum)
                        LS
                    @else
                        {{ floatval($item->qty) }}
                    @endif
                </td>
                <td style="border-color:black;padding:10px;padding-right:5px;" class="right">{{ $item->total_price }}</td>
            </tr>
            @endif
            @endforeach
        </tbody>
    </table>

    <div style="page-break-inside: avoid !important;display:block;">
        <table class="pure-table pure-table-bordered tight-table" style="border-color:black;border-collapse:collapse;margin-top:-2px;">
            <colgroup>
                <col style='width:10%;'>
                <col style='width:12%;'>
                <col>
                <col style='width:12%;'>
                <col style='width:10%;'>
                <col style='width:14%;'>
            </colgroup>
            <tbody>
                @foreach($data->lines as $counter => $item)
                @if(($counter ===  (count($data->lines) - 1) || $counter ===  (count($data->lines) - 2)))
                <tr style="font-size:12px;border-collapse: collapse">
                    <td style="border-color:black;padding:10px;" class="center">{{ ++$counter }}</td>
                    <td style="border-color:black;padding:10px;">{{ $item->quotation_number }}</td>
                    <td style="border-color:black;padding:10px;">{{ $item->description }}</td>
                    <td style="border-color:black;padding:10px;" class="right">{{ number_format($item->unit_price, 2) }}</td>
                    <td style="border-color:black;padding:10px;" class="right">
                        @if ($item->lump_sum)
                            LS
                        @else
                            {{ floatval($item->qty) }}
                        @endif
                    </td>
                    <td style="border-color:black;padding:10px;padding-right:5px;" class="right">{{ $item->total_price }}</td>
                </tr>
                @endif
                @endforeach
                <tr style="font-size:12px;">
                    <td style="border-color:black;padding:5px;" colspan="5" class="right">Total</td>
                    <td style="border-color:black;padding:5px;" class="right">{{ number_format($data->total_price, 2) }}</td>
                </tr>
                <tr style="font-size:12px;">
                    <td style="border-color:black;padding:5px;" colspan="5" class="right">VAT 5%</td>
                    <td style="border-color:black;padding:5px;" class="right">{{ number_format($data->vat, 2) }}</td>
                </tr>
                <tr style="font-size:12px;">
                    <td style="border-color:black;padding:5px;" colspan="5" class="right"><strong>Grand Total:</strong> {{ $data->grandTotalInWords() }}</td>
                    <td style="border-color:black;padding:5px;" class="right"><strong>{{ number_format($data->grand_total, 2) }}</strong></td>
                </tr>
                <tr>
                    <td colspan="6"><p style="font-size:10px;"><b>Purpose of request:</b> {{ $data->purpose_of_request }}</p></td>
                </tr>
            </tbody>
        </table>
        @if ($data->due_diligence_approved)
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

{{--</div>--}}
</div>