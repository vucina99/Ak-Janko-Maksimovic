<?php

namespace App\Imports;

use App\Models\_Case;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ImportVansudskiExcelData implements ToModel, WithStartRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        if( $row[3] == "" &&  $row[4] == "" &&  $row[5] == ""){
            return null;
        }else{
            return new _Case([
                'prosecutor' => $row[4],
                'mark' => $row[2],
//                'fail_day' => $this->parseDate($row[5]),
                'brand' => $row[3],
//                'date_send_to_expert' => $this->parseDate($row[6]),
//                'date_send_to_mail' => $this->parseDate($row[7]),
//                'date_of_findings' =>$this->parseDate($row[8]),
//                'date_of_reporting_to_insurance' => $this->parseDate($row[10]),
                'requested_amount' => $row[13],
                'paid_amount' => $row[17],
                'status' => $row[14],
                'mup_note' => $row[15],
                'damage_number' => $row[16],
                'commission' => $row[18],
                'at' => $row[19],
                'expert_costs' => $row[9],
                'lawsuit' => $row[20],
                'note' => $row[21],
                'case_type_id' => 5,
            ]);
        }

    }
    public function startRow(): int
    {
        return 2;
    }


    function parseDate($date)
    {
        if (!$date) {
            return null;
        }

        $formats = ['d/m/Y', 'd-m-Y', 'Y-m-d', 'm/d/Y']; // Dodaj formate koji su mogući
        foreach ($formats as $format) {
            try {
                return Carbon::createFromFormat($format, $date)->format('Y-m-d');
            } catch (\Exception $e) {
                continue;
            }
        }

        // Ako nijedan format ne radi, pokušaj sa nativnim PHP strtotime()
        try {
            $timestamp = strtotime($date);
            if ($timestamp !== false) {
                return Carbon::parse($timestamp)->format('Y-m-d');
            }
        } catch (\Exception $e) {
            // Ako strtotime takođe ne uspe, vrati null
        }


        // Ako nijedan format ne radi, vrati null ili prijavi grešku
        return null;
    }
}
