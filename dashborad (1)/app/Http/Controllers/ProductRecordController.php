<?php

namespace App\Http\Controllers;

use App\Models\ProductRecord;
use Illuminate\Http\Request;

class ProductRecordController extends Controller
{
    public function index()
    {
        $records = ProductRecord::latest()->get();
        return view('product-records.index', compact('records'));
    }

    public function show($id)
    {
        $record = ProductRecord::findOrFail($id);
        return response()->json($record);
    }
}
