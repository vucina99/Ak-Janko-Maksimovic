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
    public $startrow = 1;
    public function model(array $row)
    {
        if(  $this->startrow > 1103){
            $this->startrow++;

            return null;
        }else{
            $this->startrow++;

            return new _Case([
                'prosecutor' => $row[4],
                'mark' => $row[2],
                'fail_day' => $this->parseDate($row[5] , [10,11,12]),
                'brand' => $row[3],
                'date_send_to_expert' => $this->parseDate($row[6], [10,11,12]),
                'date_send_to_mail' => $this->parseDate($row[7], [10,11,12]),
                'date_of_findings' =>$this->parseDate($row[8], [11,12]),
                'date_of_reporting_to_insurance' => $this->parseDate($row[10], [11,12]),
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


    function parseDate($date, $array)
    {
        if (!$date || $date == "/") {
            return null;
        }

        $formats = ['d.m.Y']; // Dodaj formate koji su mogući
        foreach ($formats as $format) {
            try {
                // Kreiraj Carbon instancu iz formata
                $carbonDate = Carbon::createFromFormat($format, $date);

                // Formatiraj datum kao 'Y-m-d'
                $formattedDate = $carbonDate->format('Y-m-d');

                // Izvuci mesec
                $month = $carbonDate->format('m');

                // Proveri da li je mesec u nizu
                if (in_array($month, $array)) {
                    return $formattedDate;
                }

                return null;
            } catch (\Exception $e) {
                continue;
            }
        }

        return null;
    }
}
