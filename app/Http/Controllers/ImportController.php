<?php

namespace App\Http\Controllers;
use App\Imports\ImportParnicniExcelData;
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
}
