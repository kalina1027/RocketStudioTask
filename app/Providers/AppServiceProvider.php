<?php

namespace App\Providers;

use App\Models\University;
use App\Models\Technology;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);


        $allUniversities = University::all();
        View::share('allUniversities', $allUniversities);

        $allTechnologies = Technology::all();
        View::share('allTechnologies', $allTechnologies);

    }
}
