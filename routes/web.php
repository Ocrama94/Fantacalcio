<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExcelController;

Route::get('/', [ExcelController::class, 'welcome']);

// $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
// $reader->setLoadSheetsOnly(["Sheet 1", "Fantacalcio"]);
// $spreadsheet = $reader->load("../storage/app/voti.xlsx");
// dd($spreadsheet);
// return view('welcome');