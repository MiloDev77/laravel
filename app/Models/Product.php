<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Category;
use App\Models\Company;
use App\Models\Review;

class Product extends Model
{
    protected $guarded = ['id'];
    protected $casts = ['is_hidden' => 'boolean', 'gross_weight' => 'decimal:3', 'net_weight' => 'decimal:3'];

    public function scopeVisible(Builder $query)
    {
        return $query->where('is_hidden', false)->whereHas('company', function ($q) {
            $q->where('is_active', true);
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'product_id');
    }
}
