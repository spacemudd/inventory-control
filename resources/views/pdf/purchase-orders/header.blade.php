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

<div class="row">
  <div class="col-6-sm">
      Our Ref. No. MA/ <b>{{ $data->number ? $data->number : 'DRAFT' }}</b> /{{ $data->date->format('Y') }} @if($data->is_void) <span style="background-color:red;font-weight: bold;padding:0 10px;"><b>VOID</b></span> @endif
  </div>
    <div class="col-6-sm" style="text-align: right">
        Date: <strong><span>{{ optional($data->date)->format('d F Y') }}</span></strong>
    </div>
</div>
<div class="row">
  <div class="col-12-sm">
  <table class="pure-table tight-table" style="width:100%;border:0;border-top:2px solid black;border-bottom:2px solid black;" cellspacing="0" cellpadding="0">
  <colgroup>
  <col style="width:10%;">
  </colgroup>
      <tbody>
        <tr>
            <td style="border:0">To</td>
            <td style="border:0">: {{ optional($data->vendor)->name }}</td>
        </tr>
        <tr>
          <td style="border:0">Subject</td>
          <td style="border:0">: {{ ucfirst($data->subject) }}
        </tr>
        <tr>
          <td style="border:0">Location</td>
          <td style="border:0">: {{ optional($data->cost_center)->display_name }}</td>
        </tr>
      </tbody>
  </table>
  </div>
</div>

</body>
</html>
