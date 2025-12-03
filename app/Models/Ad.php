<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ad extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'category_id', 
        'title',
        'slug',
        'description',
        'price_from',
        'price_to',
        'price_negotiable',
        'location',
        'city_id',
        'region_id',
        'is_futured',
        'futured_end_date',
        'status',
        'views_count',

    ];

    protected function casts(): array
    {
        return [
            'as_company' => 'boolean',
        ];
    }
    
    protected static function booted(): void
    {
        static::creating(function (Ad $add): void {
            if (empty($add->code)) {
                $add->code = self::generateUniqueCode();
            }
        });
    }

    public static function generateUniqueCode(): string
    {
        do {
            $code = Str::random(5);
        } while (self::query()->where('code', $code)->exists());

        return $code;
    }

    public function details()
    {
        return match ($this->category->name) {
            'jobs' => $this->hasOne(AdJob::class),
            'trainings' => $this->hasOne(AdTraining::class),
            'machines' => $this->hasOne(AdMachine::class),
            default => null,
        };
    }

    /**
    * Relationship
    */

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function job()
    {
        return $this->hasOne(AdJob::class);
    }

    public function photos()
    {
        return $this->hasMany(AdPhoto::class, 'ad_id');
    }

    public function machines()
    {
        return $this->hasOne(AdMachine::class);
    }

    public function getSlugAttribute(): string
    {
        $slug = Str::slug((string) $this->title);

        return $slug !== '' ? $slug : (string) $this->id;
    }

    public function training()
    {
        return $this->hasOne(AdTraining::class);
    }
    

}
