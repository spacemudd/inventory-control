@extends('layouts.forPdf')
        <!doctype html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    {{--            mainStyle      --}}
    @include('pdf.mainStyle')

</head>
<body>
<section align="center">
    <img style="width: 100px;" src="<?php echo e(public_path('img/brand/brand_pdf_logo.png')); ?>">
</section>
<h2 align="center">PREMISES AND ADMINISTRATION DEPARTMENT</h2>
<section class="mainSection">
    <div>
        <div class="dateDiv">
            DATE: {{ date("d/m/y") }}
        </div>
        <div class="ref">
            REF #: {{ date("d/m/y") }}-{{ $mRequest->cost_center->description }}
        </div>
    </div>
    <div class="infoDiv">
        <div class="smallDiv transformText">
            From: Premises & Admin Dept.
        </div>
        <div class="smallDiv" style="margin-left: 40px;">
            Maintenance section
        </div>
        <div class="smallDiv transformText">
            Subject: <b>Materials Requisitions</b>
        </div>
        <div class="smallDiv">
            Dear Sir.
        </div>
        <div class="smallDiv">
            Please provide the following materials for maintenance purposes
        </div>
    </div>
</section>
<section class="mainTable">
    <table class="table" style="border-collapse:collapse" border="1">
        <tr style="border-bottom: 1px solid;">
            <th>
                No.
            </th>
            <th>
                Materials Description
            </th>
            <th>
                QTY
            </th>
            <th style="border-right: 1px solid;">
                Remarks
            </th>
        </tr>

        @foreach($mRequest->items as $item)
            <tr>
                <td>
                    {{ $number }}
                </td>
                <td style="width: 600px;">
                    {{ $item->description }}
                </td>
                <td style="width: 200px">
                    {{ number_format($item->qty) }}
                </td>
                <td style="width: 300px;">

                </td>
            </tr>
        @endforeach

        @if(count($mRequest->items) < 5)
            @for($i = 0; $i <= 5; $i++)
                <tr>
                    <td>{{ $number++ }}</td>
                    <td></td>
                    <td></td>
                    <td style="width: 300px;"></td>
                </tr>
            @endfor
        @endif
    </table>
</section>
<footer>
    <div class="footerDiv">
        <span>
            Purpose: {{ $mRequest->purpose }}
        </span>
    </div>
    <div class="footerDiv">
        <span class="transformText">
            Requested by:
        </span>
    </div>
    <div class="footerDiv">
        <span class="transformText">
            Approved by:
        </span>
    </div>
</footer>
</body>
</html>
