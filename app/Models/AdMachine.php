<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdMachine extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'add_id', 
        'state',
        'availability_type',
        'price_unit',
        'deposit_required',
        'subcategory_id'
    ];

    /**
    * Relationship
    */
    public function add()
    {
        return $this->belongsTo(Ad::class);
    }
}
