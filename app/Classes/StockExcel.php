<?php


namespace App\Classes;

use App\Models\Category;
use App\Models\Stock;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;

class StockExcel
{
    protected $fileName = "StockExcel";

    protected  $col = [
        'id',
        'category',
        'description',
        'qty',
    ];

    /**
     *
     * @return mixed
     */
    public function downloadStockExcel()
    {
        $stockByCategory = Stock::with('category')->get()->groupBy('category_id');

        $excel = Excel::create($this->fileName, function($excel) use ($stockByCategory) {
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

    public function StockForCsv($item)
    {
        $itemName = '';

        if($item['category_id']) {
            $category = Category::find($item->category_id);
            $itemName = $category ? $category->name : '';
        }

        return [
            $item->id,
            $itemName,
            $item->description,
            $item->on_hand_quantity,
        ];
    }
}
