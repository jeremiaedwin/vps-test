<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Product extends Model
{
    
    /**
     * fillable
     * @var array
     */
    protected $fillable = [
        'image',
        'category_id',
        'title',
        'description',
        'price',
        'stock'
    ];

    /**
     * image
     * 
     * @return Attribute
     */
    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn ($image) => url('/storage/products'. $image),
        );
    }


    /**
     * One to Many Relationship to Category
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Many to Many Relationship to Tags
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}
