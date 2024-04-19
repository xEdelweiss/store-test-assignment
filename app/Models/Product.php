<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static \Database\Factories\ProductFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product query()
 * @method static Builder|Product maxPrice(float $maxPrice)
 * @method static Builder|Product minPrice(float $minPrice)
 * @method static Builder|Product titleContains(string $title)
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $image
 * @property string $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static Builder|Product whereCreatedAt($value)
 * @method static Builder|Product whereDescription($value)
 * @method static Builder|Product whereId($value)
 * @method static Builder|Product whereImage($value)
 * @method static Builder|Product wherePrice($value)
 * @method static Builder|Product whereTitle($value)
 * @method static Builder|Product whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Product extends Model
{
    use HasFactory;

    public function scopeMinPrice(Builder $query, float $minPrice): void
    {
        $query->where('price', '>=', $minPrice);
    }

    public function scopeMaxPrice(Builder $query, float $maxPrice): void
    {
        $query->where('price', '<=', $maxPrice);
    }

    public function scopeTitleContains(Builder $query, string $title): void
    {
        $query->where('title', 'like', "%{$title}%");
    }
}
