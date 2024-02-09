<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Technology extends Model
{
    use HasFactory;
    
    protected $fillable = ['title'];

    public function cvs()
    {
        return $this->belongsToMany(CV::class, 'cv_technologies', 'technology_id', 'cv_id')
            ->as('cvs')
            ->withTimestamps();
    }



}
