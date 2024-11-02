<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'name',
        'code',
        'category',
        'location',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($model) {
            if (!$model->uuid) {
                $model->uuid = Str::uuid();
            }
        });
    }

    /**
     * Get all of the mutations for the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function mutations(): HasMany
    {
        return $this->hasMany(Mutation::class);
    }
}
