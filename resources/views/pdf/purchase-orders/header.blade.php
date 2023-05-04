<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @include('pdf.style')
    </style>
</head>
<body>

<div class="row big-font" style="margin-bottom:0px;padding-bottom:0">
    <div class="col-2-sm">
        <div class="center" style="width: 225px;">
{{--            <img style="width: 150px;" src="{{ asset('img/brand/brand_pdf_new_logo.jpg') }}">--}}
        </div>
    </div>
    <div class="col-10-sm" style="text-align: right;padding-bottom:0">
{{--        <h3 style="margin-bottom:0px;padding-bottom:0"><span>--}}
{{--        إدارة المباني والخدمـات الإدارية<br/>--}}
{{--Premises & Admin. Services Dept.--}}
{{--        </span></h3>--}}
    </div>
</div>

<div class="row" style="margin-top:50px;font-size:20px;">
  <div class="col-6-sm">
      Our Ref. No. <b>{{ $data->number ? $data->number : 'DRAFT' }}</b> @if($data->is_void) <span style="background-color:red;font-weight: bold;padding:0 10px;"><b>VOID</b></span> @endif
  </div>
    <div class="col-6-sm" style="text-align: right">
        Date: <strong><span>{{ optional($data->date)->format('d F Y') }}</span></strong>
    </div>
</div>
<div class="row">
  <div class="col-12-sm">
  <table class="pure-table tight-table" style="width:100%;border:0;border-top:2px solid black;border-bottom:2px solid black;font-size:20px;" cellspacing="0" cellpadding="0">
  <colgroup>
  <col style="width:10%;">
  </colgroup>
      <tbody>
        <tr>
            <td style="border:0"><b>To</b></td>
            <td style="border:0">: {{ optional($data->vendor)->name }}</td>
        </tr>
        <tr>
            <td style="border:0"><b>Subject</b></td>
            <td style="border:0">: {{ ucfirst($data->subject) }}
        </tr>
        <tr>
            <td style="border:0"><b>Location</b></td>
            <td style="border:0">: {{ optional($data->cost_center)->display_name }}</td>
        </tr>
      </tbody>
  </table>
  </div>
</div>

</body>
</html>
