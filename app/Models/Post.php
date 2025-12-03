<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_category_id', 
        'title',
        'slug',
        'code',
        'lead',
        'description',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'is_published',
        'published_at',
        'featured_image_url'
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Post $post): void {
            if (empty($post->code)) {
                $post->code = self::generateUniqueCode();
            }
        });

        static::saving(function (Post $post): void {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
        });
    }

    public static function generateUniqueCode(): string
    {
        do {
            $code = Str::random(6);
        } while (self::query()->where('code', $code)->exists());

        return $code;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(PostCategory::class, 'post_category_id');
    }

    public function tags(): HasMany
    {
        return $this->hasMany(Tag::class);
    }

    public function getUrlAttribute(): string
    {
        return route('blog.show', ['code' => $this->code, 'slug' => $this->slug]);
    }

    public function getFeaturedImageUrlAttribute(): ?string
    {
        if ($this->featured_image) {
            return asset('storage/' . $this->featured_image);
        }

        return null;
    }

}
