<?php

namespace App\Http\Controllers;

use App\Models\CV;
use App\Models\Candidate;
use App\Models\Technology;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;


class CVController extends Controller
{
    public function store(Request $request)
    {
        $messages = [
            'first_name.required' => 'Име е задължително поле.',
            'last_name.required' => 'Фамилия е задължително поле.',
            'email.required' => 'Имейл адрес е задължително поле.',
            'email.email' => 'Имейл адресът е неправилен.',
            'birth_date.required' => 'Дата на раждане е задължително поле.',
            'birth_date.date' => 'Неправилно въведена дата.',
            'birth_date.before_or_equal' => 'Датата трябва да е преди или равна на '.date("d.m.Y").' г.',
            'birth_date.after_or_equal' => 'Датата трябва да е след или равна на 01.01.1900 г.',
            'university.required' => 'Моля изберете университет.',
            'university.numeric' => 'Моля изберете университет.',
            'technologies.required' => 'Моля изберете технологии.',
            'technologies.*.integer' => 'Неправилно зададени технологии.'
        ];
        $validated_data = $request->validate([
            'first_name' => 'required|string',
            'middle_name' => 'nullable|string',
            'last_name' => 'required|string',
            'email' => 'required|email:rfc,dns',
            'birth_date' => 'required|date|before_or_equal:'.date("d.m.Y").'|after_or_equal:01.01.1900',
            'university' => 'required|numeric',
            'technologies' => 'required|array',
            'technologies.*' => 'integer'
        ], $messages);

        $email = $validated_data['email'];
        if (Candidate::where('email', $email)->exists()) {
            $candidate = Candidate::where('email', $email)->first();
            $this->cvData($candidate, $validated_data);
            return redirect()->route('/')->with(['status' => 'success', 'response' => 'CV e записано успешно!']);
            
        } else {
            $candidate_data = Arr::only($validated_data, ['first_name', 'middle_name', 'last_name', 'email','birth_date']);
            $candidate = Candidate::create($candidate_data);
            $this->cvData($candidate, $validated_data);
            return redirect()->route('/')->with(['status' => 'success', 'response' => 'CV e записано успешно!']);
        }

        return redirect()->route('/')->with(['status' => 'fail', 'response' => 'Грешка']);
    }

    private function cvData($candidate, $validated_data)
    {
        $candidate_id = ['candidate_id' => $candidate->id];
        $uni_data = ['university_id' => $validated_data['university']];

        $cv_data = array_merge($candidate_id, $uni_data);
        $cv = CV::create($cv_data);

        $technologies = $validated_data['technologies'];
        $cv->technologies()->attach($technologies);
    }


}
