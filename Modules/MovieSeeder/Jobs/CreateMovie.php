<?php

namespace Modules\MovieSeeder\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\MovieSeeder\Entities\Movie;

class CreateMovie implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $movie;

    /**
     * CreateMovie constructor.
     * @param $movie
     */
    public function __construct($movie)
    {
        $this->movie = $movie;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = $this->movie;
        $genres = $data['genre_ids'];
        //create movie if not exist
        $movie = Movie::find($data['id']);
        if($movie)
        {
            $movie->update(['popularity' => $data['popularity'],
                'vote_average' => $data['vote_average']]);
        }else{
            Movie::Create([
                'id' => $data['id'],
                'title' => $data['title'],
                'overview' => $data['overview'],
                'poster' => $data['poster_path'],
                'release_date' => $data['release_date'],
                'popularity' => $data['popularity'],
                'vote_average' => $data['vote_average'],
            ]);
            $movie = Movie::find($data['id']);
        }
        $movie->categories()->sync($genres);

    }
}
