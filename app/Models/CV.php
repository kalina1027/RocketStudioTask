<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CV extends Model
{
    use HasFactory;
    
    protected $table = 'cvs';

    protected $fillable = ['candidate_id', 'university_id'];

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class, 'candidate_id');
    }

    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class, 'university_id');
    }

    public function technologies()
    {
        return $this->belongsToMany(Technology::class, 'cv_technologies', 'cv_id', 'technology_id')
            ->as('technologies')
            ->withTimestamps();
    }

}
