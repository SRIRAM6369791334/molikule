<?php

namespace App\Http\Controllers;

use App\Models\Insurance;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InsuranceController extends Controller
{
    /**
     * Display a listing of insurance quotes
     */
    public function index(Request $request)
    {
        $query = Insurance::query();

        // Search functionality
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $insurances = $query->recent()->paginate(15);

        return view('insurance.index', compact('insurances'));
    }

    /**
     * Display a single insurance quote
     */
    public function show(Insurance $insurance)
    {
        return view('insurance.show', compact('insurance'));
    }

    /**
     * Download insurance quote as PDF
     */
    public function print(Insurance $insurance)
    {
        $pdf = Pdf::loadView('insurance-pdf', compact('insurance'))
                  ->setPaper('a4', 'portrait')
                  ->setOptions(['defaultFont' => 'sans-serif']);

        $filename = 'insurance_quote_' . $insurance->quote_number . '.pdf';

        return $pdf->download($filename);
    }
}
