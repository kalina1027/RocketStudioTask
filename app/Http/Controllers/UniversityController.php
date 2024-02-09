<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\University;

class UniversityController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string',
            'rating' => 'numeric|nullable'
        ]);
        
        $university = University::create($validatedData);
        return response()->json(['success' => 'Университетът е добавен.', 'id' => $university->id, 'title' => $university->title]);
    }
}
