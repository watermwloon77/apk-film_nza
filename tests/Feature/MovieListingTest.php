<?php

namespace Tests\Feature;

use App\Models\Cast;
use App\Models\Film;
use App\Models\Genre;
use App\Models\Kritik;
use App\Models\Peran;
use App\Models\Profile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MovieListingTest extends TestCase
{
    use RefreshDatabase;

    public function test_movies_page_displays_the_film_list(): void
    {
        $genre = Genre::create(['name' => 'Drama']);
        Film::create([
            'title' => 'Film Drama',
            'sinopsis' => 'Sinopsis film.',
            'year' => 2026,
            'poster' => 'posters/drama.jpg',
            'genre_id' => $genre->id,
        ]);

        $this->get(route('movies'))
            ->assertOk()
            ->assertSee('Film Drama');
    }

    public function test_genre_page_displays_only_films_in_that_genre(): void
    {
        $drama = Genre::create(['name' => 'Drama']);
        $comedy = Genre::create(['name' => 'Komedi']);

        Film::create([
            'title' => 'Film Drama',
            'sinopsis' => 'Sinopsis drama.',
            'year' => 2026,
            'poster' => 'posters/drama.jpg',
            'genre_id' => $drama->id,
        ]);
        Film::create([
            'title' => 'Film Komedi',
            'sinopsis' => 'Sinopsis komedi.',
            'year' => 2026,
            'poster' => 'posters/komedi.jpg',
            'genre_id' => $comedy->id,
        ]);

        $this->get(route('genre', $drama->id))
            ->assertOk()
            ->assertSee('Movies Of Genre Drama')
            ->assertSee('Film Drama')
            ->assertDontSee('Film Komedi');
    }

    public function test_movie_detail_displays_cast_name_instead_of_cast_id(): void
    {
        $genre = Genre::create(['name' => 'Drama']);
        $film = Film::create([
            'title' => 'Film dengan Cast',
            'sinopsis' => 'Sinopsis film.',
            'year' => 2026,
            'poster' => 'posters/drama.jpg',
            'genre_id' => $genre->id,
        ]);
        $cast = Cast::create([
            'name' => 'Aktor Bernama',
            'age' => 30,
            'bio' => 'Bio aktor.',
        ]);
        Peran::create([
            'actor' => 'Tokoh Utama',
            'film_id' => $film->id,
            'cast_id' => $cast->id,
        ]);

        $this->get(route('movies.show', $film))
            ->assertOk()
            ->assertSee('<td class="w3-list-info">Aktor Bernama</td>', false)
            ->assertDontSee('<td class="w3-list-info">'.$cast->id.'</td>', false);
    }

    public function test_movie_detail_displays_comment_text_and_author(): void
    {
        $genre = Genre::create(['name' => 'Drama']);
        $film = Film::create([
            'title' => 'Film dengan Komentar',
            'sinopsis' => 'Sinopsis film.',
            'year' => 2026,
            'poster' => 'posters/drama.jpg',
            'genre_id' => $genre->id,
        ]);
        $role = Role::create(['name' => 'user']);
        $profile = Profile::create([
            'umur' => 25,
            'bio' => 'Pengguna uji',
            'alamat' => 'Jakarta',
        ]);
        $user = User::create([
            'name' => 'Nabila Zahra',
            'email' => 'nabila@example.com',
            'password' => 'password',
            'profile_id' => $profile->id,
            'role_id' => $role->id,
        ]);
        Kritik::create([
            'film_id' => $film->id,
            'user_id' => $user->id,
            'comment' => 'Film ini sangat bagus dan ceritanya menarik.',
            'rating' => 5,
        ]);

        $this->get(route('movies.show', $film))
            ->assertOk()
            ->assertSee('Nabila Zahra')
            ->assertSee('Film ini sangat bagus dan ceritanya menarik.')
            ->assertSee('color: #555; white-space: pre-wrap;', false);
    }
}
