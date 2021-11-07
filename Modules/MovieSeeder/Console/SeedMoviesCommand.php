<?php

namespace Modules\MovieSeeder\Console;

use Illuminate\Console\Command;
use Modules\MovieSeeder\Entities\Category;
use Modules\MovieSeeder\Jobs\CreateMovie;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;


class SeedMoviesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed:movies';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'get categories and movies from themoviedb ';

    /**
     * themoviedb api key
     * @var string
     */
    protected $api_key;
    /**
     * number of records to be seed
     * @var integer
     */
    protected $num_of_records;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->api_key = env('THEMOVIEDB_KEY'); // the movie db api key
        $this->num_of_records = env('NUM_OF_RECORDS'); // number of movies to be seeded
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // seed genres
        $this->seedCategories();

        //seed recently movies
        $this->seedRecentlyMovies();

        //seed top rated movies
        $this->seedTopRatedMovies();
        $this->info('seed movies from themoviedb');

    }

    /**
     * seed genres
     */
    private function seedCategories()
    {
        $response = Http::get('https://api.themoviedb.org/3/genre/movie/list?api_key='.$this->api_key.'&language=en-US');
        $categories = $response->ok() ? $response->json()['genres'] : [];
        foreach ($categories as $category)
        {
            Category::firstOrCreate([
                'id' => $category['id'],
                'name' => $category['name']
                ]);
        }
    }

    private function seedRecentlyMovies()
    {
//        $response = Http::get('https://api.themoviedb.org/3/movie/latest?api_key='.$this->api_key.'&language=en-US');

    }

    /**
     * seed top rated movies
     */
    private function seedTopRatedMovies(){
        $pages = $this->num_of_records > 20 ? (int) ceil($this->num_of_records / 20)  : 1; // count of required pages according to required num of records
        $num_of_last_items = $this->num_of_records % 20;

        for ($i = 1; $i <= $pages; $i++) {
            $response = Http::get('https://api.themoviedb.org/3/movie/top_rated?api_key='.$this->api_key.'&language=en-US&page='.$i);
            $movies = $response->ok() ? $response->json()['results'] : [];

            //if last iteration and need less than 20 movie
            if($i == $pages && $num_of_last_items > 0) {
                $movies = array_slice($movies, 0, $num_of_last_items);
            }

            foreach ($movies as $movie)
            {
                dispatch(new CreateMovie($movie));
            }
        }
        Cache::forget('movies');
    }
    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['example', InputArgument::REQUIRED, 'An example argument.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];
    }
}
