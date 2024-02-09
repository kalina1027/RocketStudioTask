<?php

namespace App\Http\Controllers;

use App\Models\CV;
use App\Models\Candidate;
use App\Models\Technology;
use Carbon\Carbon;
use Illuminate\Http\Request;



class ReportController extends Controller
{
    public function index()
    {
        return view('reports');
    }

    public function showCVsByBirthDate(Request $request)
    {
        $messages = [
            'start_date.before_or_equal' => 'Началната дата трябва да е преди или същата като крайната дата.',
            'end_date.after_or_equal' => 'Крайната дата трябва да е след или същата като началната дата.'
        ];
        $validated_data = $request->validate([
            'start_date' => 'nullable|date|before_or_equal:end_date',
            'end_date' => 'nullable|date|after_or_equal:start_date'
        ], $messages);     

        $start_date = $validated_data['start_date'];
        $end_date = $validated_data['end_date'];

        $startDate = Carbon::parse($start_date)->format('Y-m-d');
        $endDate = Carbon::parse($end_date)->format('Y-m-d');

        if($start_date != null && $end_date != null) {
            $cvs = CV::with(['candidate', 'university', 'technologies'])->whereHas('candidate', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('birth_date', [$startDate, $endDate]);
            })->get();

            $cvs = $cvs->toArray();
            foreach($cvs as &$cv) {
                $cv['candidate']['birth_date'] = Carbon::parse($cv['candidate']['birth_date'])->format('d.m.Y');
            }
            
            if(count($cvs) == 0) {
                return redirect()->route('reports')->with(['status' => 'fail', 'response' => 'Няма резултати']);
            }

            $startDate = Carbon::parse($start_date)->format('d.m.Y');        
            $endDate = Carbon::parse($end_date)->format('d.m.Y');

            return redirect()->route('reports')->with(['status' => 'success', 'response' => '', 'cvs' => $cvs, 'startDate' => $startDate, 'endDate' => $endDate ]);
        }

        return redirect()->route('reports')->with(['status' => 'fail', 'response' => 'Няма резултати']);
    }
}
