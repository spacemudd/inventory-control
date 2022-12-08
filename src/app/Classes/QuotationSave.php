<?php

namespace App\Classes;

use App\Models\Quotation;
use App\Services\StockService;
use Illuminate\Support\Facades\DB;

class QuotationSave
{
    protected $stockService;

    /**
     * @var \App\Models\MaterialRequest 
     */
    private $quotation;

    static public function new(Quotation $quotation): QuotationSave
    {
        $service = app()->make(StockService::class);
        return new QuotationSave($quotation, $service);
    }

    public function __construct(Quotation $quotation, StockService $stockService)
    {
        $this->stockService = $stockService;
        $this->quotation = $quotation;
    }
    /**
     * Approve the material request.
     *
     */
    public function save()
    {
        //$this->canSaved();
        //$this->exceptionOnNoItems();

        DB::beginTransaction();
            $this->quotation->status = Quotation::SAVED;
            $this->quotation->saved_at = now();
            $this->quotation->saved_by_id = auth()->user()->id;
            $this->quotation->save();
            // Credit the supplier.
            $journal = $this->quotation->vendor->journal;
            if (!$journal) {
                $this->quotation->vendor->initJournal('SAR');
                $this->quotation->vendor->refresh();
            }
            $transaction = $this->quotation->vendor->journal->creditDollars($this->quotation->items()->sum('total_price_inc_vat'));
            $transaction->referencesObject($this->quotation);

            // Update stock status.
            foreach ($this->quotation->items()->get() as $item) {
                $this->stockService->addIn($item->description, $item->qty, $item);
            }
        DB::commit();

        return $this->quotation;
    }

    /**
     * Can approve.
     *
     * @throws \Exception
     */
    public function canSaved()
    {
        if ($this->quotation->saved_at) {
            throw new \Exception('Quotation is already saved.');
        }

        return true;
    }

    /**
     * Throw an exception if no items are available.
     *
     * @throws \Exception
     */
    public function exceptionOnNoItems()
    {
        if ( ! $this->quotation->items()->count() ) {
            throw new \Exception('Quotation cannot be saved without items');
        }
    }
}
