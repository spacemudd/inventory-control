<div>
    {{-- <strong>For and on behalf of Arab National Bank</strong> --}}
    <div class="row" style="margin-top:80px;letter-spacing: 0.5px;">
        @if ($data->approver_one)
            <div class="col-4-sm center">
                <div style="border-top:2px solid black;width:100%;font-size:20px;">
                    <strong>{{ optional($data->approver_one)->name }}</strong><br/>
                    <strong><span style="font-size:12px;">{{ optional($data->approver_one)->designation }}</span></strong>
                </div>
            </div>
        @endif
        <div class="col-4-sm center">
        </div>
        @if ($data->approver_two)
        <div class="col-4-sm center">
            <div style="border-top:2px solid black;width:100%;font-size:20px;">
                <strong>
                    {{ optional($data->approver_two)->name }}
                    <br/>
                    <span style="font-size:12px;">{{ optional($data->approver_two)->designation }}</span>
                </strong>
            </div>
        </div>
        @endif
    </div>
    {{-- <p class="center" style="font-size:11px;">The accompanying Terms and Conditions form an integral part of this Purchase Order</p>
    <p class="center" style="font-size:10px;">Prepared by {{ $data->created_by->username }} - {{ $data->created_by->name }}</p> --}}
</div>
