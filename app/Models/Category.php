<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    

    /**
     * fillable
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * One to Many Relationship to Product
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
