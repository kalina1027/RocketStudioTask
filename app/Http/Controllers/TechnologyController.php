<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Technology;

class TechnologyController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string',
        ]);
        
        $technology = Technology::create($validatedData);
        return response()->json(['success' => 'Технологията е добавена.', 'id' => $technology->id, 'title' => $technology->title]);
    }
}
