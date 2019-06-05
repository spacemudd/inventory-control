<?php


namespace App\Classes;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;

class StockExcel
{
    protected $fileName = "StockExcel";

    protected  $col = [
        'id',
        'category',
        'Description',
        'Available quantity'
    ];


    public function downloadStockExcel($data)
    {
        $excel = Excel::create($this->fileName, function($excel) use ($data){
            $excel->sheet('mySheet', function($sheet) use ($data)
            {
                $sheet->appendRow($this->col);
                $data->each(function($item) use ($sheet) {
                    $sheet->appendRow($this->StockForCsv($item));
                });
            });
        });

        return $excel->download('xlsx');
    }

    public function StockForCsv($item)
    {


        if($item['category_id'] != ''){
            $itemName = Category::find($item->category_id)->name;
        } else {
            $itemName = '';
        }
        return [
            $item->id,
            $itemName,
            $item->description,
            $item->on_hand_quantity,
        ];
    }
}