<?php

namespace Modules\MovieSeeder\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\MovieSeeder\Entities\Movie;
use Modules\MovieSeeder\Transformers\MovieResource;

class MovieSeederController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $category_id = $request->category_id;

        $movies_query = Movie::when($category_id, function ($query) use ($category_id){
            return $query->wherehas('categories', function ($que) use ($category_id){
                $que->where('category_id', $category_id);
            });
        });

        $movies_query = $this->sortMovies($movies_query, $request->except('page'));
        $movies = Cache::remember('movies', 60*60*24, function () use ($movies_query){
            return $movies_query->get();
        });

        return  MovieResource::collection($movies);
//        $movies = $movies_query->paginate(10, ['*'], 'page', $request->page ?? 1);
//
//        $paginatedMovies = $movies->toArray();
//
//
//        return ['data' => MovieResource::collection($movies),
//            'per_page' => $paginatedMovies['per_page'],
//            'last_page' => $paginatedMovies['last_page'],
//            'total' => $paginatedMovies['total']];
    }

    /**
     * sort movies according to rate or popularity
     * @param $query
     * @param $request
     */
    protected function sortMovies($query, $request)
    {
        $sort_by = ['popular' => 'popularity', 'rated' => 'vote_average'];

        foreach($request as $key => $value)
        {
            if(strpos($key, '|'))
            {
                $sort = explode('|', $key);
                if(isset($sort_by[$sort[0]]) && in_array($sort[1], ['desc', 'asc'])){
                    $query->orderBy($sort_by[$sort[0]], $sort[1]);
                }
            }
        }
        return $query;
    }
}
