<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    @include("pdf.style")
</head>
<body>
<div class="container">
    <div class="row big-font">
        <div class="col-sm-2">
            <div style="width: 100px; height: 70px;margin-top:10px;">
                <img style="width: 100px;" src="{{ public_path('img/brand/brand_pdf_logo.png') }}">
            </div>
        </div>
        <div class="col-8-sm center">
            <p><span style="border:2px solid black;padding:1rem;font-weight:bold;">MAINTENANCE JOB ORDER</span></p>
        </div>
    </div>

    <div class="row" style="font-size:11px;">
        <div class="col-6-sm">
            <table class="pure-table-bordered">
                <colgroup>
                    <col style="width:50%;">
                    <col style="width:50%;">
                </colgroup>
                <tbody>
                <tr>
                    <td><b>Job Order No:</b></td>
                    <td>{{ $jobOrder->job_order_number }}</td>
                </tr>
                <tr>
                    <td><b>Name of Requester:</b></td>
                    <td>{{ $jobOrder->employee->name }}</td>
                </tr>
                <tr>
                    <td><b>Department:</b></td>
                    <td>{{ $jobOrder->department->description }}</td>
                </tr>
                <tr>
                    <td><b>Ext:</b></td>
                    <td>{{ $jobOrder->ext }}</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="col col-6-sm">
            <table class="pure-table-bordered">
                <colgroup>
                    <col style="width:50%;">
                    <col style="width:50%;">
                </colgroup>
                <tbody>
                <tr>
                    <td><b>Date:</b></td>
                    <td>{{ $jobOrder->date->format('d-m-Y') }}</td>
                </tr>
                <tr>
                    <td><b>Emp ID:</b></td>
                    <td>{{ $jobOrder->employee->code }}</td>
                </tr>
                <tr>
                    <td><b>Location:</b></td>
                    <td>{{ $jobOrder->department->description }}</td>
                </tr>
                <tr>
                    <td><b>CC:</b></td>
                    <td>{{ $jobOrder->department->code }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col col-12-sm">
            <div>
                <span style="font-weight:bold;">Requested through:</span>
                <ul style="list-style: none;display: inline;">
                    <li style="display: inline;">{!! $jobOrder->requested_through_type === 'email' ? '<b>&#9746</b>' : '&#x2610' !!} Email</li>
                    <li style="display: inline;margin-left:2rem;">{!! $jobOrder->requested_through_type === 'phone_call' ? '<b>&#9746</b>' : '&#x2610' !!} Phone Call</li>
                    <li style="display: inline;margin-left:2rem;">{!! $jobOrder->requested_through_type === 'breakdown' ? '<b>&#9746</b>' : '&#x2610' !!} Breakdown</li>
                    <li style="display: inline;margin-left:2rem;">{!! $jobOrder->requested_through_type === 'ppm' ? '<b>&#9746</b>' : '&#x2610' !!} PPM</li>
                </ul>
            </div>

            <hr style="margin-top:1rem;margin-bottom:1rem;">

            <p><b>Job Description</b></p>
            <p style="background-color:#f5f5f5;padding:1rem;">
                {{ $jobOrder->job_description }}
            </p>
        </div>
    </div>


    {{-- Technicians --}}
    <div class="row">
        <div class="col-6-sm">
            <b>Name of Technicians:</b>
            <table class="pure-table pure-table-bordered tight-table" style="margin-top:1rem;">
                <colgroup>
                    <col style='width:1%;'>
                    <col>
                    <col style="width:25%;">
                </colgroup>
                <thead>
                <tr>
                    <th class="center">#</th>
                    <th class="center">Technician</th>
                    <th class="center">Emp. ID</th>
                </tr>
                </thead>
                <tbody>
                @foreach($jobOrder->technicians as $counter => $employee)
                    <tr>
                        <td class="center">{{ ++$counter }}</td>
                        <td>{{ $employee->name }}</td>
                        <td>{{ $employee->code }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-12-sm">
            <p><b>Materials used:</b></p>
            <p style="background-color:#f5f5f5;padding:1rem;">
                {{ $jobOrder->materials_used }}
            </p>
        </div>

        <div class="col-12-sm">
            <p><b>Job Duration:</b> {{ $jobOrder->time_start->format('H:i') }} to {{ $jobOrder->time_end->format('H:i') }}</p>
        </div>

        <div class="col-12-sm">
            <span><b>Status:</b></span>
            <ul style="list-style: none;display: inline;">
                <li style="display: inline;">{!! strtolower($jobOrder->status) === 'pending' ? '<b>&#9746</b>' : '&#x2610' !!} Pending</li>
                <li style="display: inline;margin-left:2rem;">{!! strtolower($jobOrder->status) === 'completed' ? '<b>&#9746</b>' : '&#x2610' !!} Completed</li>
            </ul>
        </div>

        <div class="col-12-sm">
            <p><b>Remark:</b></p>
            <p style="background-color:#f5f5f5;padding:1rem;">
                {{ $jobOrder->remark }}
            </p>
        </div>
    </div>

</div>

<div style="page-break-inside: avoid !important;">
    <div class="row" style="position: absolute; bottom: 5rem;">
        <div class="col-12-sm" style="margin-top:-10px;">
            @component('pdf.job-order.signatures')
            @endcomponent
        </div>
    </div>
</div>

</body>
</html>
