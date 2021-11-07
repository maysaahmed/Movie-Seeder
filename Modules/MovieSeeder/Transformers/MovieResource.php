<?php

namespace Modules\MovieSeeder\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class MovieResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
//            'overview' => $this->overview,
            'poster' => 'https://image.tmdb.org/t/p/w500'.$this->poster,
            'release_date' => $this->release_date,
            'popularity' => $this->popularity,
            'rate'  => $this->vote_average,
            'genres' => CategoryResource::collection($this->categories)
        ];
    }
}
