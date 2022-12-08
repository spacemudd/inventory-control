<?php


namespace App\Classes;

use App\Models\Stock;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;

class StockExcel
{
    protected $fileName = "StockExcel";

    protected  $col = [
        'code',
        //'category',
        'description',
        'rack_number',
        'qty',
        'recommended_qty',
    ];

    public function StockForCsv($item)
    {
        return [
            $item->code,
            //optional($item->category)->name,
            $item->description,
            $item->rack_number,
            $item->on_hand_quantity,
            $item->recommended_qty,
        ];
    }

    /**
     *
     * @return mixed
     */
    public function downloadStockExcel()
    {
        $stockByCategory = Stock::with('category')->get()->groupBy('category_id');

        $excel = Excel::create($this->fileName.now()->format('-Y-m-d'), function($excel) use ($stockByCategory) {
            foreach ($stockByCategory as $category_id => $stocks) {
                if ($stocks) {
                    $excel->sheet(optional($stocks->first()->category)->name ?: 'Uncategorized', function($sheet) use ($stocks) {
                        $sheet->appendRow($this->col);

                        $stocks->each(function($item) use ($sheet) {
                            $sheet->appendRow($this->StockForCsv($item));
                        });
                    });
                }
            }
        });

        return $excel->download('xlsx');
    }
}
