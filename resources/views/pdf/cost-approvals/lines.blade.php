{{-- Items --}}
<div class="row">
    <div class="col-12-sm">
        <table class="pure-table pure-table-bordered tight-table">
            <colgroup>
            <col style='width:10.67%;'>
            <col style='width:16.67%;'>
            <col style='width:22.67%;'>
            <col style='width:16.67%;'>
            <col style='width:16.67%;'>
            <col style='width:16.67%;'>
        </colgroup>
        <thead>
            <tr>
                <th class="center">No.</th>
                <th class="center">Vendor</th>
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
            <tr style="border-top:2px #cbcbcb solid;">
                <td class="center">{{ ++$counter }}</td>
                <td>{{ optional($data->vendor)->display_name }}</td>
                <td>{{ $item->description }}</td>
                <td class="right"> {{ number_format($item->unit_price, 2) }}</td>
                <td class="right">{{ $item->qty }}</td>
                <td class="right">{{ $item->total_price }}</td>
            </tr>
            @endif
            @endforeach
        </tbody>
    </table>

    <div style="page-break-inside: avoid !important;">
        <table class="pure-table pure-table-bordered tight-table">
            <colgroup>
            <col style='width:10.67%;'>
            <col style='width:16.67%;'>
            <col style='width:22.67%;'>
            <col style='width:16.67%;'>
            <col style='width:16.67%;'>
            <col style='width:16.67%;'>
        </colgroup>
        <tbody>
            @foreach($data->lines as $counter => $item)
            @if(($counter ===  (count($data->lines) - 1) || $counter ===  (count($data->lines) - 2)))
            <tr style="border-top:2px #cbcbcb solid;">
                <td class="center">{{ ++$counter }}</td>
                <td>{{ optional($data->vendor)->display_name }}</td>
                <td>{{ $item->description }}</td>
                <td class="right"> {{ $item->unit_price }}</td>
                <td class="right">{{ $item->qty }}</td>
                <td class="right">{{ $item->total_price }}</td>
            </tr>
            @endif
            @endforeach
            <tr>
                <td colspan="5" class="right"><strong>Total</strong></td>
                <td class="right"><strong>{{ number_format($data->total_price, 2) }}</strong></td>
            </tr>
            <tr>
                <td colspan="5" class="right"><strong>VAT 5%</strong></td>
                <td class="right"><strong>{{ number_format($data->vat, 2) }}</strong></td>
            </tr>
            <tr>
                <td colspan="5" class="right"><strong>Grand Total</strong></td>
                <td class="right"><strong>{{ number_format($data->grand_total, 2) }}</strong></td>
            </tr>
            <tr>
                <td colspan="6"><b>Purpose of request:</b> {{ $data->purpose_of_request }}</td>
            </tr>
        </tbody>
    </table>
    @if (!$data->due_diligence_approved)
        <p style="margin-top:0px;"><b>Due diligence approved</b></p>
    @endif
</div>

</div>
</div>
