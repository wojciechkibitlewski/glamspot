<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdPhoto extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'ad_id', 
        'photo'
    ];

    /**
    * Relationship
    */

    public function add()
    {
        return $this->belongsTo(Ad::class);
    }
}
