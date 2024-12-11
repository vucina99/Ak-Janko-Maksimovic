<?php

namespace App\Imports;

use App\Models\_Case;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ImportParnicniExcelData implements ToModel, WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

        $existingCase = _Case::where('number_office', $row[0])->first();

        if ($existingCase) {
            // Ako zapis već postoji, preskoči unos
            return null;
        }

        return new _Case([
            "case_type_id" => str_contains(strtolower($row[1]), 'prekršaj') ? 3 :  1,
            "number_office" =>  $row[0],
            "prosecutor" => count(explode("-" , $row[1])) > 1 ? (explode("-" , $row[1]))[0] : $row[1],
            "defendants" => count(explode("-" , $row[1])) > 1 ? (explode("-" , $row[1]))[1] : null,
            "mark" => $row[2],
        ]);
    }
    public function startRow(): int
    {
        return 2;
    }
}
