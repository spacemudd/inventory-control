<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @include('pdf.style')
    </style>
</head>
<body>

<div class="row big-font">
    <div class="col-2-sm">
        <div class="center" style="width: 225px;">
            <!--<img style="width: 150px;" src="{{ asset('img/brand/brand_pdf_logo.png') }}">-->
        </div>
    </div>
    <div class="col-12-sm" style="text-align: right">
        <h3><span>
        إدارة المباني والخدمـات الإدارية<br/>
Premises & Admin. Services Dept.
        </span></h3>
    </div>
</div>

<div class="row big-font">
    <div class="col-12-sm" style="text-align: right">
        <h3>
        <span>
          COST APPROVAL
        </span>
        </h3>
    </div>
</div>

<div class="row">
  <div class="col-6-sm">
      Ref. No. {{ $data->number ? $data->number : 'DRAFT' }}
  </div>
</div>
<hr>
<div class="row">
  <div class="col-12-sm">
  <table class="pure-table tight-table" style="width:100%;border:0;"  cellspacing="0" cellpadding="0">
  <colgroup>
  <col style="width:25%;">
  <col style="width:25%;">
  <col style="width:25%;">
  <col style="width:25%;">
  </colgroup>
      <tbody>
        <tr>
            <td style="border:0"><b>Requested by:</b></td>
            <td style="border:0">{{ optional($data->requested_by)->display_name }}</td>
            <td style="border:0"><b>Cost Center:</b></td>
            <td style="border:0">{{ optional($data->cost_center)->display_name }}</td>
        </tr>
        <tr>
          <td style="border:0;"><b>Project location:</b></td>
          <td style="border:0;">{{ $data->project_location }}</td>
          <td style="border:0;"><b>Date:</b></td>
          <td style="border:0;">{{ $data->date->format('d-m-Y') }}</td>
        </tr>
      </tbody>
  </table>
  </div>
</div>

</body>
</html>
