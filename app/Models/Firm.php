<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Firm extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'user_id',
        'firm_name',
        'firm_city',
        'firm_postalcode',
        'firm_address',
        'firm_region',
        'firm_region_id',
        'firm_nip',
        'firm_www',
        'firm_email',
        'firm_phone',
        'firm_logo',
        'avatar',
    ];

    /**
    * Relationship
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class, 'firm_region_id');
    }
}
