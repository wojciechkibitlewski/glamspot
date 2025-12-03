<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class AdTrainingDate extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'ad_training_id',
        'start_date',
        'end_date',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'end_date' => 'datetime',
        ];
    }

    /**
    * Relationship
    */

    public function adTraining()
    {
        return $this->belongsTo(AdTraining::class, 'ad_training_id');
    }

}
