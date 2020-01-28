<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    @include('pdf.generalPOStyle')
</head>
<body>
    <section>
        <div  class="is-right">
            Premises & Admin. Service dept
        </div>
    </section>

    <section>
        <div class="is-left">
            Our Ref. No MA/{{ $request->ref }}/2019
        </div>
        <div class="is-right">
            Date: {{$request->date}}
        </div>
    </section>

    <section class="is-top-border is-bottom-border">
        <div class="spanDivs">
            <div class="is-margin-right is-left t">To </div>
            <div class="is-left "> : </div>
        </div>
        <div class="spanDivs is-flex">
            <div class="is-margin-right is-left ">
                Subject
            </div>
            <div class=" is-left ">
                :
            </div>
        </div>
        <div class="spanDivs is-flex">
           <div class="is-margin-right is-left ">
               Location
           </div>
            <div class="is-left ">
                : ANM - Branch Name (CC Cost Center)
            </div>
        </div>
    </section>

    <section>
        <div>
            Sir,
        </div>
        <div style="margin-top:30px;">
            With reference to your quotation {{$request->ref}}  dated on {{$request->date}} for the below mentioned we confirm the
            acceptance for your offer   amounting as follows
        </div>
    </section>

    <section>
        <table>
            <thead>
            <tr>
                <th class="is-top-border ">No.</th>
                <th class="is-top-border " style="width:500px;">Description.</th>
                <th class="is-top-border " style="width:200px;">U/Price</th>
                <th class="is-top-border " style="width:100px;">Qty</th>
                <th class="is-top-border is-right-border" style="width:200px;">T/Price(SAR)</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td class="is-top-border is-bottom-border">1</td>
                <td class="is-top-border is-bottom-border"></td>
                <td class="is-top-border is-bottom-border"></td>
                <td class="is-top-border is-bottom-border"></td>
                <td class="is-top-border is-right-border is-bottom-border"></td>
            </tr>
            <tr>
                <td colspan="4" class="is-margin-left " align="right">Total</td>
                <td class=" is-right-border "></td>
            </tr>
            <tr>
                <td colspan="4" align="right">VAT 5%</td>
                <td class="is-right-border is-top-border "></td>
            </tr>
            <tr>
                <td colspan="4" class="is-bottom-border is-margin-left" align="right"><b>Grand Total</b></td>
                <td  class="is-right-border is-top-border  is-bottom-border"></td>
            </tr>
            </tbody>
        </table>
    </section>
    <section>
        Please proceed immediately
    </section>
    <section>
        Thanks and Best Regards
    </section>
    <footer>
        <div>
            <div class="is-left">
                <p>
                    <b>
                        Engr. Saleh N. Al-Zunaidi
                    </b>
                </p>
                <p>
                    Head of Premises and Administration Services
                </p>
            </div>
            <div class="is-right">
                <p>
                    <b>
                        Ashraf Saeed
                    </b>
                </p>
                <p>
                    Premises Center
                </p>
            </div>
        </div>
    </footer>

</body>
</html>


