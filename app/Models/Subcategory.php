<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Subcategory extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'category_id', 
        'name', 
        'slug'
    ];

    protected static function booted(): void
    {
        static::saving(function (Subcategory $subcategory): void {
            if (empty($subcategory->slug)) {
                $subcategory->slug = Str::slug($subcategory->name);
            }
        });
    }

    /**
    * Relationship
    */
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
