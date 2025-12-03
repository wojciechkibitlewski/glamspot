<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdTraining extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'ad_id', 
        'type', 
        'seats',
        'has_certificate',
        'duration_hours',
        'organizer',
    ];

    /**
    * Relationship
    */
    public function ad()
    {
        return $this->belongsTo(Ad::class);
    }

    public function dates()
    {
        return $this->hasMany(AdTrainingDate::class, 'ad_training_id');
    }

    public function specializations()
    {
        return $this->belongsToMany(AdTrainingSpecialization::class, 'ad_training_specialization', 'ad_training_id', 'specialization_id');
    }
}
