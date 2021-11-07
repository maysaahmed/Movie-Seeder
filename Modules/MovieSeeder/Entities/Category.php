<?php

namespace Modules\MovieSeeder\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'name'];

    protected static function newFactory()
    {
        return \Modules\MovieSeeder\Database\factories\CategoryFactory::new();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function movies()
    {
        return $this->belongsToMany(Movie::class, 'movies_categories');
    }

}
