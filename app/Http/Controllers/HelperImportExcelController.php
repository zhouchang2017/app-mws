<?php

namespace App\Http\Controllers;

use App\Imports\TaxonsImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\Finder\Finder;

class HelperImportExcelController extends Controller
{
    public function getFilePath($name)
    {
        return storage_path('app/excels').DIRECTORY_SEPARATOR.$name;
    }

    public function import()
    {
        Excel::import(new TaxonsImport(),'excels/attr.xlsx','local');
    }
}
