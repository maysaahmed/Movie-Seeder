# Schedule Movie Seeder API Service Laravel
a schedule movie seeder that get top rated movies and genres from [themoviedb](https://www.themoviedb.org/) and an endpoint to list movies from the Database with the ability to filter with genres and sort by popularity and rates.


# Getting started

## Installation

Please check the official laravel installation guide for server requirements before you start. [Laravel Documentation](https://laravel.com/docs/8.x/installation)

Clone the repository

    git clone https://github.com/maysaahmed/Movie-Seeder.git

Switch to the repo folder

    cd Movie-Seeder

Install all the dependencies using composer

    composer install

Generate a new application key

    php artisan key:generate


Run the database migrations (**Set the database connection in .env before migrating**)

    php artisan migrate


## Integration 
create an account on [themoviedb](https://www.themoviedb.org/) and get api_key 

Add to **.env**

    THEMOVIEDB_API_KEY=xxxxxxxxxxxxxxxxxxxx

## Seed Movies

Put the number of movies you want to be seeded 
by default it will be 100 records

    NUM_OF_RECORDS=100


If you want to run seeder manually by command you can run this command 

    php artisan seed:movies

that will get all genres and top rated movies from themoviedb and save it to the database


Put the cron expression for the seeder task schedule 

    CRON_EXP="* * * * *"

## Movies Endpoint
you can list all movies you have in the database through

    {base_url}/api/movies

you can filter movies by genres

    {base_url}/api/movies?category_id=32

you can also sort movies by poularity and|or rates

    {base_url}/api/movies?category_id=32&popular|desc&rated|desc

