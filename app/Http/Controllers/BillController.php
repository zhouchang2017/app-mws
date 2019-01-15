<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Resources\Bill as BillResource;

class BillController extends Controller
{
    public static $resource = BillResource::class;

    public static $indexViewName = 'bills.index';

    public function getBalance()
    {
        if (erpRequest()->isSupplier()) {
            $balance = auth()->user()->supplier->balance;
        }
        $balance = $balance ?? 0;
        return response()->json(
            $balance
        );
    }
}
