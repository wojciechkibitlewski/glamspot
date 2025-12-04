<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'name', 
        'slug'
    ];

    protected static function booted(): void
    {
        static::saving(function (Category $category): void {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    /**
    * Relationship
    */

    public function subcategories()
    {
        return $this->hasMany(Subcategory::class);
    }
}
