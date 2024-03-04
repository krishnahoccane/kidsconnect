<?php

namespace App\Exports;

use App\Models\Registration;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RegistrationsExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Registration::all();
    }
    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Email',
            'Profile Created On',
        ];
    }
}
