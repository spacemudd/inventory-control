<?php

namespace App\Classes;

use App\Models\MaterialRequest;
use App\Models\MaterialRequestItem;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;

class MaterialRequestExcel
{
    /**
     * @var
     */
    protected $rows;

    protected $materialRequests;
    protected $materialRequest;

    /**
     * @var string
     */
    protected $fileName;
    protected $mRequestCol = [
        'description',
        'qty'
    ];
    protected $columns = [
        'Location',
        'Item',
        'Quantity',
        'Code',
        'Location_Name',
        'Cost Center',
        'Status',
    ];

    protected $column = [
        'Location',
        'Item',
        'Location_Name',
        'Status',
    ];

    public function __construct(MaterialRequest $materialRequest = null)
    {
        $this->materialRequest = $materialRequest;

        if($materialRequest) {
            $this->fileName = str_slug($this->materialRequest->number);
        } else {
            $this->fileName = now()->format('Y-m-d').'-material-requests';
        }
    }

    /**
     *
     * @param $id
     * @return MaterialRequestExcel
     */
    static public function find($id): MaterialRequestExcel
    {
        $materialRequest = MaterialRequest::find($id);
        return new MaterialRequestExcel($materialRequest);
    }

    public function download()
    {
        $excel = Excel::create($this->fileName, function($excel) {
            $excel->sheet('Material Request', function ($sheet) {
                $sheet->appendRow($this->columns);
                $this->materialRequest->items()->get()->each(function(MaterialRequestItem $item) use ($sheet) {
                    $sheet->appendRow($this->itemForExcel($item));
                });
            });
        });

        return $excel->download('csv');
    }

    /**
     *
     * @param \App\Models\MaterialRequestItem $item
     * @return array
     */
    public function itemForExcel(MaterialRequestItem $item): array
    {
        return [
            $this->materialRequest->number,
            $this->materialRequest->location->name,
            $this->materialRequest->cost_center->display_name,
            $item->description,
            $item->qty,
        ];
    }

    public function downloadMaterialRequests($data, $type)
    {
        $excel = Excel::create($this->fileName, function($excel) use ($data){
            $excel->sheet('mySheet', function($sheet) use ($data)
            {
                $sheet->appendRow($this->column);
                $data->each(function($item) use ($sheet) {
                    $sheet->appendRow($this->itemForCsv($item));
                });
            });
        });

        return $excel->download($type);
    }

    public function itemForCsv($item): array
    {
        return [
            $item->number,
            $item->location->name,
            $item->cost_center->display_name,
            $item->status_name,
        ];
    }

    public function downloadMaterialRequestExcel($data, $type)
    {
        $excel = Excel::create($this->fileName, function($excel) use ($data){
            $excel->sheet('mySheet', function($sheet) use ($data)
            {
                $sheet->appendRow($this->mRequestCol);
                $data->each(function($item) use ($sheet) {
                    $sheet->appendRow($this->itemRequestForCsv($item));
                });
            });
        });

        return $excel->download($type);
    }

    public function itemRequestForCsv($item): array
    {

        return [
            $item->description,
            $item->qty,
        ];
    }
}
