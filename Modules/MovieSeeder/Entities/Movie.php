<?php

namespace Modules\MovieSeeder\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'title', 'overview', 'poster', 'release_date', 'popularity', 'vote_average'];

    protected static function newFactory()
    {
        return \Modules\MovieSeeder\Database\factories\MovieFactory::new();
    }

    /**
     * The movies that belong to genres.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'movies_categories');
    }
}
