<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'image',
        'slug'
    ];

    /**
     * Get all the crowdfunding campaigns of this category
     *
     * @return HasMany
     */
    public function donationCampaigns(): HasMany
    {
        return $this->hasMany(DonationCampaign::class, 'category_id');
    }

    /**
     * Returns the parent model of this catgeory
     * @return BelongsTo
     */
    public function parent() : BelongsTo
    {
        return $this->belongsTo(Category::class, 'id', 'parent_id');
    }

    /**
     * Returns the children of this category.
     * @return HasMany
     */
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
}
