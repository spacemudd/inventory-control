{{-- Items --}}
<div class="row">
    <div class="col-12-sm">
        <table class="pure-table pure-table-bordered tight-table" style="border-color:black;">
            <colgroup>
                <col style='width:10px;'>
                <col>
                <col style='width:100px;'>
                <col style='width:100px;'>
                <col style='width:100px;'>
            </colgroup>
            <thead>
            <tr>
                <th style="border-color:black;" class="center">No.</th>
                <th style="border-color:black;" class="center">Description</th>
                <th style="border-color:black;" class="center">Price</th>
                <th style="border-color:black;" class="center">Qty</th>
                <th style="border-color:black;" class="center">Price (SAR)</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data->lines as $counter => $item)
                @if(! ($counter ===  (count($data->lines) - 1) || $counter ===  (count($data->lines) - 2)) )
                    <tr style="border-top:2px black solid;">
                        <td style="border-color:black;" class="center">{{ ++$counter }}</td>
                        <td style="border-color:black;">{{ $item->description }}</td>
                        <td style="border-color:black;" class="right">{{ number_format($item->unit_price, 2) }}</td>
                        <td style="border-color:black;" class="center">{{ $item->lump_sum ? 'LS' : $item->qty }}</td>
                        <td style="border-color:black;" class="right">{{ number_format($item->subtotal, 2) }}</td>
                    </tr>
                @endif
            @endforeach
            </tbody>
        </table>

        <div style="page-break-inside: avoid !important;">
            <table class="pure-table pure-table-bordered tight-table" style="border-color:black;">
                <colgroup>
                    <col style='width:51px;'>
                    <col>
                    <col style='width:100px;'>
                    <col style='width:100px;'>
                    <col style='width:100px;'>
                </colgroup>
                <tbody>
                    @foreach($data->lines as $counter => $item)
                        @if(($counter ===  (count($data->lines) - 1) || $counter ===  (count($data->lines) - 2)))
                            <tr style="border-top:2px black solid;">
                                <td style="border-color:black;" class="center">{{ ++$counter }}</td>
                                <td style="border-color:black;">{{ $item->description }}</td>
                                <td style="border-color:black;" class="center">{{ number_format($item->unit_price, 2) }}</td>
                                <td style="border-color:black;" class="center">{{ $item->lump_sum ? 'LS' : $item->qty }}</td>
                                <td style="border-color:black;" class="right">{{ number_format($item->subtotal, 2) }}</td>
                            </tr>
                        @endif
                    @endforeach
                    <tr style="font-size:12px;">
                        <td style="border-color:black;padding:5px;" colspan="4" class="right">Total</td>
                        <td style="border-color:black;padding:5px;" class="right">{{ number_format($data->lines()->sum('subtotal'), 2) }}</td>
                    </tr>
                    <tr style="font-size:12px;">
                        <td style="border-color:black;padding:5px;" colspan="4" class="right">VAT 5%</td>
                        <td style="border-color:black;padding:5px;" class="right">{{ number_format($data->lines()->sum('vat'), 2) }}</td>
                    </tr>
                    <tr style="font-size:12px;">
                        <td style="border-color:black;padding:5px;" colspan="4" class="right"><strong>Grand Total:</strong></td>
                        <td style="border-color:black;padding:5px;" class="right"><strong>{{ number_format($data->lines()->sum('grand_total'), 2) }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</div>
