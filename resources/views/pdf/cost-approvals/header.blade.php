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
        <h3><span style="font-size:18px!important;">
        إدارة المباني والخدمـات الإدارية</span><br/>
<span style="font-size:14px;">Premises & Admin. Services Dept.</span>
        </span></h3>
    </div>
</div>

<div class="row big-font" style="margin-top:30px;">
    <div class="col-12-sm" style="font-size:19px;">
        <h3 style="margin:0;padding:0;">
          COST APPROVAL
        </h3>
    </div>
</div>

<div class="row" style="margin-top:-10px;padding:0;">
  <div class="col-6-sm">
      <p style="font-size:11px;padding:0;margin:0;">Ref. No. {{ $data->number ? $data->number : 'DRAFT' }}</p>
  </div>
</div>
<hr>
<div class="row">
  <div class="col-12-sm">
  <table class="pure-table tight-table" style="width:100%;border:0;font-size:12px;"  cellspacing="0" cellpadding="0">
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
            <td style="border:0">{{ optional($data->cost_center)->code }}</td>
        </tr>
        <tr>
          <td style="border:0;"><b>Date:</b></td>
          <td style="border:0;">{{ $data->date->format('d-m-Y') }}</td>
          <td style="border:0;"><b>Vendor:</b></td>
          <td style="border:0;">{{ optional($data->vendor)->display_name }}</td>
        </tr>
      </tbody>
  </table>
  </div>
</div>

</body>
</html>