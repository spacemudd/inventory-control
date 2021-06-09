<div style="font-family:Calibri;">
    <div class="row" style="margin-top:15px;">
        <div class="col-4-sm">
            @if (now()->isBefore(now()->setDate(2021, 6, 9)))
                <p style="font-size:11px;padding-bottom:50px;font-family: Calibri">Prepared by:</p>
                <div class="center" style="border-top:2px solid black;width:100%;font-size:10px;">
                    <strong style="font-family:Calibri;">
                        Ashraf Saeed<br/>
                        Premises Centre
                    </strong>
                </div>
            @else
            <p style="font-size:11px;padding-bottom:50px;font-family: Calibri">Noted by:</p>
            <div class="center" style="border-top:2px solid black;width:100%;font-size:10px;">
                <strong style="font-family:Calibri;">
                    Engr. Haitham AbdulRahman Al Koblan<br/>
                    Head of Premises Centre
                </strong>
            </div>
            @endif
        </div>
        @if ($data->approver_two_id)
            <div class="col-4-sm"></div>
            <div class="col-4-sm center">
                <div style="border-top:2px solid black;width:100%;font-size:10px;margin-top:90px;white-space:nowrap;">
                    <strong style="font-family:Calibri;">
                        {{ $data->approver_two->name }}
                        <br/>
                        {!! $data->approver_two->designation !!}
                    </strong>
                </div>
            </div>
        @endif
    </div>
    <div class="row" style="margin-top:30px;">
        @if ($data->approver_one_id || $data->approver_two_id || $data->approver_three_id)
            <div style="font-size:11px;margin-left:10px;padding-bottom:50px;font-family: Calibri">Approved by:</div>
        @endif
        @if ($data->approver_one_id)
            <div class="col-4-sm center">
                <div style="border-top:2px solid black;width:100%;font-size:10px;white-space:nowrap">
                    <strong style="font-family:Calibri">
                        {{ $data->approver_one->name }}
                        <br/>
                        {!! $data->approver_one->designation !!}
                    </strong>
                </div>
            </div>
        @endif

        @if ($data->approver_three_id)
            <div class="col-4-sm center"></div>
            <div class="col-4-sm center">
                <div style="border-top:2px solid black;width:100%;font-size:10px;white-space:nowrap">
                    <strong style="font-family:Calibri">
                        {{ $data->approver_three->name }}
                        <br/>
                        {!! $data->approver_three->designation !!}
                    </strong>
                </div>
            </div>
        @endif

    </div>
</div>
