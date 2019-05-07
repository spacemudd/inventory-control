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
            <div class="col-5-sm">
                <div style="width: 100px; height: 50px;">
                    <img style="width: 100px;" src="{{ public_path('img/brand/brand_pdf_logo.png') }}">
                </div>
            </div>
            <div class="col-7-sm">
                <strong>MAINTENANCE JOB ORDER</strong>
            </div>
        </div>
            
        <div class="row" style="font-size:11px;">
            <div class="col col-6-sm">
                <p class="">Job Order No: {{ $jobOrder->job_order_number }}</p>
                <p class="">Name Of Requester: {{ $jobOrder->employee->name }}</p>
                <p class="">Department: {{ $jobOrder->department->description }}</p>
                <p class="">Ext: {{ $jobOrder->ext }}</p>
            </div>
            <div class="col col-6-sm">
                <p class="">Date: {{ $jobOrder->date->format('d-m-Y') }}</p>
                <p class="">Empl ID: {{ $jobOrder->employee->code }}</p>
                <p class="">Location: {{ $jobOrder->location->name }}</p>
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

    <div style="page-break-inside: avoid !important;">
        <div class="row">
            <div class="col-12-sm" style="margin-top:-10px;">
                @component('pdf.job-order.signatures')
                @endcomponent
            </div>
        </div>
    
        
        <div class="row" style="font-size:11px;">
            <div class="col-6-sm">
                <strong>Arab national bank Saudi Stock Co. Paid up Capital S.R. 10,000 Million</strong><br/>
                P.O. Box 56921, Riyadh 11564 - Kingdom of Saudi Arabia<br/>
                Tel +966 11 402 9000 Fax +966 11 402 7747<br/>
            </div>
            <div class="col-6-sm right" dir="rtl">
                <strong>البنك العربي الوطني شركة مساهمة سعودية رأس المال المدفوع 10,000 مليون ريال</strong><br/>
                ص.ب. 56921، الـــريـــــــــــاض 11574 - المملــكــة العــربـــيـــة الـسـعـوديـــة<br/>
                تلفون <span dir="ltr">+966 11 402 9000</span> فاكس <span dir="ltr">+966 11 402 7747</span>
            </div>
        </div>
    </div>
    
</body>
</html>
