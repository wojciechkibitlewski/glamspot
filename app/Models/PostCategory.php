<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class PostCategory extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'category', 
        'slug',
        'description',
        'seo_title',
        'seo_description',
        'seo_keywords',
    ];

    protected static function booted(): void
    {
        static::saving(function (PostCategory $category): void {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->category);
            }
        });
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'post_category_id');
    }
}
