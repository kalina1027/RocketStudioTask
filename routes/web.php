<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CVController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TechnologyController;
use App\Http\Controllers\UniversityController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () { 
    return view('cv_form'); 
})->name('/');

// Route::get('/reports', function () { 
//     return view('reports'); 
// })->name('reports');
Route::get('/reports', [ReportController::class, 'index'])->name('reports');

Route::post('/university-store', [UniversityController::class, 'store'])->name('university.store');
Route::post('/technology-store', [TechnologyController::class, 'store'])->name('technology.store');
Route::post('/cv-store', [CVController::class, 'store'])->name('cv.store');
Route::post('/reports-birthdate', [ReportController::class, 'showCVsByBirthDate'])->name('report.birthdate');
Route::post('/reports-age-technologies', [ReportController::class, 'showByAgeTechnologies'])->name('report.age-technologies');