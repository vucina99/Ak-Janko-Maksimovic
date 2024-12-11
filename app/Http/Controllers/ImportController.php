<?php

namespace App\Http\Controllers;
use App\Imports\ImportParnicniExcelData;
use App\Imports\ImportVansudskiExcelData;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    public function importParnicni(Request $request)
    {
        $file = public_path("/excel/pravilanreg.ods");
        if (!file_exists($file)) {
            dd("nema fajla");
        }

        Excel::import(new ImportParnicniExcelData, $file);

        dd("uspesno");
    }

    public function importVansudski(Request $request)
    {
        $file = public_path("/excel/evidencijasteta.ods");
        if (!file_exists($file)) {
            dd("nema fajla");
        }

        Excel::import(new ImportVansudskiExcelData(), $file);

        dd("uspesno");
    }

}
