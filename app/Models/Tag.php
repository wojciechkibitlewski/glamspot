<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Tag extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'post_id', 
        'slug',
        'tag',
        'seo_title',
        'seo_description',
        'seo_keywords',
    ];

    protected static function booted(): void
    {
        static::saving(function (Tag $tag): void {
            if (empty($tag->slug)) {
                $tag->slug = Str::slug($tag->tag);
            }
        });
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
