<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    /**
     * fillable
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Many to Many Relationship to Product
     */
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
