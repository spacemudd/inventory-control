<div>
    <div class="row" style="margin-top:80px;">
        <div class="col-4-sm">
            <p style="font-size:11px;padding-bottom:50px;">Prepared by:</p>
            <div class="center" style="border-top:2px solid black;width:100%;font-size:10px;">
                <strong>
                    Ashraf Saeed<br/>
                    Premises Centre
                </strong>
            </div>
        </div>
        @if ($data->approver_two_id)
            <div class="col-4-sm"></div>
            <div class="col-4-sm center">
                <div style="border-top:2px solid black;width:100%;font-size:10px;margin-top:90px;">
                    <strong>
                        {{ $data->approver_two->name }}
                        <br/>
                        {{ $data->approver_two->designation }}
                    </strong>
                </div>
            </div>
        @endif
    </div>
    <div class="row" style="margin-top:80px;">
        @if ($data->approver_one_id || $data->approver_two_id || $data->approver_three_id)
            <div style="font-size:11px;margin-left:10px;padding-bottom:50px;">Approved by:</div>
        @endif
        @if ($data->approver_one_id)
            <div class="col-4-sm center">
                <div style="border-top:2px solid black;width:100%;font-size:10px;">
                    <strong>
                        {{ $data->approver_one->name }}
                        <br/>
                        {{ $data->approver_one->designation }}
                    </strong>
                </div>
            </div>
        @endif

        @if ($data->approver_three_id)
            <div class="col-4-sm center"></div>
            <div class="col-4-sm center">
                <div style="border-top:2px solid black;width:100%;font-size:10px;">
                    <strong>
                        {{ $data->approver_three->name }}
                        <br/>
                        {{ $data->approver_three->designation }}
                    </strong>
                </div>
            </div>
        @endif

    </div>
</div>
