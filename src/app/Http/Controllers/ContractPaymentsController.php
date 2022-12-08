<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\ContractPayment;
use Brick\Math\RoundingMode;
use Brick\Money\Context\CustomContext;
use Brick\Money\Money;
use Illuminate\Http\Request;

class ContractPaymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $contract = Contract::findOrFail($id);
        return view('contract-payments.create', compact('contract'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    	
        $request->validate([
            'issued_at' => 'required',
            'reference' => 'required',
            'contract_id' => 'required',
            'cost' => 'required',
        	'invoice_period_from' => 'required',
        	'invoice_period_to' => 'required',
        	'proceeded_date' => 'required',
        	'invoice_no' => 'required',
            'tax_percentage' => 'required',
        ]);

        $payment = $request->except('_token', 'tax_percentage');
        $payment['invoice_tax_amount'] = Money::of($request->cost, 'AUD', new CustomContext(2), RoundingMode::HALF_UP)
            ->multipliedBy($request->tax_percentage, RoundingMode::HALF_UP)
            ->getAmount()
            ->toFloat();

        ContractPayment::create($payment);

        return redirect()->route('contracts.show', $request->contract_id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
