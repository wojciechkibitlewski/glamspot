<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdJob extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'ad_id',
        'job_type',
        'employment_form',
        'salary_from',
        'salary_to',
        'experience_level',
        'requirements',
        'benefits'
    ];

    /**
    * Relationship
    */

    public function ad()
    {
        return $this->belongsTo(Ad::class);
    }

    public function specializations()
    {
        return $this->belongsToMany(Industry::class, 'ad_job_specialization', 'ad_job_id', 'specialization_id');
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class, 'job_type', 'slug');
    }
}
