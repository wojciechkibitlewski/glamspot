<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdTrainingSpecialization extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'name', 
        'slug'
    ];
    
}
