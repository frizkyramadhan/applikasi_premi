<?php

namespace App\Exports;

use App\Models\Unit;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UnitExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Unit::select('*')->with('unit_model', 'project')->orderBy('unit_no', 'asc')->get();
    }

    public function headings(): array
    {
        return [
            'id',
            'unit_no',
            'unit_desc',
            'unit_model_id',
            'project_id'
        ];
    }
}
