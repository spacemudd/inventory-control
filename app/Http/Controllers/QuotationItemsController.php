<?php

namespace App\Http\Controllers;

use App\Models\MaterialRequest;
use App\Models\MaterialRequestItem;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Services\StockService;
use Brick\Math\RoundingMode;
use Brick\Money\Context\CustomContext;
use Brick\Money\Money;
use Illuminate\Http\Request;

class QuotationItemsController extends Controller
{

    private $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;

    }

    /**
     *
     * @param $id
     * @return mixed
     */
    public function index($id)
    {
        $quote = Quotation::where('id', $id)
            ->with(['items' => function($q) {
                $q->with('stock_template');
            }])
            ->firstOrFail();

        return $quote->items;
    }

    /**
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     * @throws \Brick\Money\Exception\MoneyMismatchException
     * @throws \Brick\Money\Exception\UnknownCurrencyException
     */
    public function store(Request $request)
    {
        $request->validate([
           'quotation_id' => 'required|exists:quotations,id',
           'material_request_item_id' => 'required|exists:material_request_items,id',
           'qty' => 'required|numeric',
           'unit_price' => 'required|numeric',
        ]);

        $request = $request->except('_token');

        $request['vendor_id'] = Quotation::where('id', $request['quotation_id'])->first()->vendor_id;
        $request['description'] = MaterialRequestItem::find($request['material_request_item_id'])->description;

        $totalPriceExVat = Money::of($request['unit_price'], 'SAR', new CustomContext(2), RoundingMode::HALF_UP)
            ->multipliedBy($request['qty'], RoundingMode::HALF_UP);

        $request['unit_price'] = Money::of($request['unit_price'], 'SAR', new CustomContext(2), RoundingMode::HALF_UP)
            ->getMinorAmount()
            ->toInt();

        $request['total_price_ex_vat'] = $totalPriceExVat->getMinorAmount()->toInt();

        $oldVat = 0.05;
        $newSaudiVat = 0.15;

        $d = now()->setDate(2020, 6, 29);

        if (now()->greaterThan($d)) {
            $vat = Money::ofMinor($totalPriceExVat->getMinorAmount()->toInt(), 'SAR')
                ->multipliedBy($newSaudiVat, RoundingMode::HALF_UP);
        } else {
            $vat = Money::ofMinor($totalPriceExVat->getMinorAmount()->toInt(), 'SAR')
                ->multipliedBy($oldVat, RoundingMode::HALF_UP);
        }

        $request['vat'] = $vat->getMinorAmount()
            ->toInt();

        $totalPriceIncVat = Money::ofMinor($totalPriceExVat->getMinorAmount()->toInt(), 'SAR')
            ->plus($vat);

        $request['total_price_inc_vat'] = $totalPriceIncVat
            ->getMinorAmount()
            ->toInt();

        $item = QuotationItem::create($request);
        $item->load('stock_template');

        // If all items have been delivered, mark material request as delivered.
        $this->processDeliveredStatus($request['quotation_id']);

        return $item;
    }

    /**
     * If all items have been delivered, mark material request as delivered.
     *
     * @param $quotationId
     */
    public function processDeliveredStatus($quotationId)
    {
        $quotation = Quotation::find($quotationId);
        $materialRequest = $quotation->material_request;

        $quotationQty = $quotation->items()->sum('qty');
        $materialQty = $materialRequest->items()->sum('qty');

        if (($materialQty === $quotationQty) || ($quotationQty >= $materialQty)) {
            // Mark as delivered.
            $materialRequest->status = MaterialRequest::DELIVERED;
        } else {
            $materialRequest->status = MaterialRequest::PENDING;
        }

        $materialRequest->save();
    }

    /**
     *
     * @param $quotationId
     * @param $id
     * @return array
     */
    public function delete($quotationId, $id)
    {
        QuotationItem::where('id', $id)->firstOrFail()->delete();

        $this->processDeliveredStatus($quotationId);

        return [
            'message' => 'Success',
        ];
    }


    public function getQuotations()
    {

        $quotations = Quotation::where('status', 1)->select('vendor_quotation_number', 'id','vendor_id')->get();

        return $quotations;
    }
}
