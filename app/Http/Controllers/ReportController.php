<?php

namespace App\Http\Controllers;

use App\Models\CV;
use App\Models\Candidate;
use App\Models\Technology;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;



class ReportController extends Controller
{
    public function index()
    {
        return view('reports');
    }

    public function showCVsByBirthDate(Request $request)
    {
        $titleResponse = 'CV-та на кандидати родени в период';
        $dates = $this->validateDates($request);
        $start_date = $dates['start_date'];
        $end_date = $dates['end_date'];
        $startDate = $dates['startDate'];
        $endDate = $dates['endDate'];

        if($start_date != null && $end_date != null) {
            $cvs = CV::with(['candidate', 'university', 'technologies'])->whereHas('candidate', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('birth_date', [$startDate, $endDate]);
            })->get();

            $cvs = $cvs->toArray();
            foreach($cvs as &$cv) {
                $cv['candidate']['birth_date'] = Carbon::parse($cv['candidate']['birth_date'])->format('d.m.Y');
            }
            
            if(count($cvs) == 0) {
                return redirect()->route('reports')->with(['status' => 'fail', 'response' => 'Няма резултати', 'title' => $titleResponse]);
            }

            $startDate = Carbon::parse($start_date)->format('d.m.Y');        
            $endDate = Carbon::parse($end_date)->format('d.m.Y');

            return redirect()->route('reports')->with(['status' => 'success', 'response' => '', 'cvs' => $cvs, 'startDate' => $startDate, 'endDate' => $endDate, 'title' => $titleResponse ]);
        }

        return redirect()->route('reports')->with(['status' => 'fail', 'response' => 'Няма резултати', 'title' => $titleResponse]);
    }

    public function showByAgeTechnologies(Request $request)
    {
        $titleResponse = 'Брой кандидати групирани по възраст със списък от умения за период';
        $dates = $this->validateDates($request);
        $start_date = $dates['start_date'];
        $end_date = $dates['end_date'];
        $startDate = $dates['startDate'];
        $endDate = $dates['endDate'];
    
        if($start_date != null && $end_date != null) {
            $cvs = CV::select(DB::raw('TIMESTAMPDIFF(YEAR, candidates.birth_date, CURDATE()) as age'), DB::raw('COUNT(DISTINCT(candidates.id)) as count'), DB::raw('GROUP_CONCAT(DISTINCT technologies.title ORDER BY technologies.title SEPARATOR ", ") AS technologies'))
                ->join('candidates', 'candidates.id', '=', 'cvs.candidate_id')
                ->join('cv_technologies', 'cvs.id', '=', 'cv_technologies.cv_id')
                ->join('technologies', 'cv_technologies.technology_id', '=', 'technologies.id')
                ->whereBetween('cvs.created_at', [$startDate, $endDate])
                ->groupBy('age')
                ->get();

            $cvs = $cvs->toArray();

            if(count($cvs) == 0) {
                return redirect()->route('reports')->with(['status' => 'fail', 'response' => 'Няма резултати', 'title' => $titleResponse]);
            }

            $startDate = Carbon::parse($start_date)->format('d.m.Y');        
            $endDate = Carbon::parse($end_date)->format('d.m.Y');

            return redirect()->route('reports')->with(['status' => 'success', 'response' => '', 'cvs' => $cvs, 'startDate' => $startDate, 'endDate' => $endDate, 'title' => $titleResponse ]);
        }

        return redirect()->route('reports')->with(['status' => 'fail', 'response' => 'Няма резултати', 'title' => $titleResponse]);
    }

    private function validateDates($request)
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

        $startDate = Carbon::parse($start_date)->format('Y-m-d h:i:s');
        $endDate = Carbon::parse($end_date)->format('Y-m-d h:i:s');

        return [
            'start_date' => $start_date,
            'end_date' => $end_date,
            'startDate' => $startDate,
            'endDate' => $endDate
        ];
    }
}
