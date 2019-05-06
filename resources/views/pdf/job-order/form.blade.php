<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    @include("pdf.style")
</head>
<body>
    <div class="container">
        <div class="row" style="font-size:11px;">
            <div class="col col-6-sm">
                <p class="">Job Order No: {{ $jobOrder->job_order_number }}</p>
                <p class="">Date: {{ $jobOrder->date->format('d-m-Y') }}</p>
                <p class="">Name Of Requester: {{ $jobOrder->employee->name }}</p>
                <p class="">Department: {{ $jobOrder->department->description }}</p>
                
            </div>
            <div class="col col-6-sm">
                <p class="">Empl ID: {{ $jobOrder->employee->code }}</p>
                <p class="">Location: {{ $jobOrder->location->name }}</p>
                <p class="">Ext: {{ $jobOrder->ext }}</p>
                <p class="">CC: {{ $jobOrder->cc }}</p>
            </div>
        </div>
        <div class="row" style="font-size:11px;">
            <div class="col col-12-sm">
                <p class="">Requested Through: {{ $jobOrder->requested_through_type }}</p>
                <p class="">Job Description: {{ $jobOrder->job_description }}</p>
            </div>
        </div>


        {{-- Technicians --}}
        <div class="row">
            <div class="col-6-sm center">
                <table class="pure-table pure-table-bordered tight-table">
                    <colgroup>
                        <col style='width:50%;'>
                        <col style='width:50%;'>
                    </colgroup>
                    <thead>
                    <tr>
                        <th class="center">#</th>
                        <th class="center">Technician</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($jobOrder->technicians as $counter => $employee)
                            <tr style="border-top:2px #cbcbcb solid;">
                                <td class="center" rowspan="3">{{ ++$counter }}</td>
                                <td rowspan="3">{{ $employee->name }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row" style="font-size:11px;">
            <div class="col col-12-sm">
                <p class="">Materials Used: {{ $jobOrder->materials_used }}</p>
            </div>

            <div class="col col-12-sm">
                <p class="">Job Duration: {{ $jobOrder->time_start->format('H:i') }} to {{ $jobOrder->time_end->format('H:i') }}</p>
            </div>

            <div class="col col-12-sm">
                <p class="">Status: {{ $jobOrder->status }}</p>
            </div>

            <div class="col col-12-sm">
                <p class="">Remark: {{ $jobOrder->remark }}</p>
            </div>
        </div>

    </div>

    {{--  @include('pdf.job-order.signatures')  --}}
</body>
</html>
