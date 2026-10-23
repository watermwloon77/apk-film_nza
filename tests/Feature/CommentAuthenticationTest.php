<?php

namespace Tests\Feature;

use App\Models\Film;
use App\Models\Genre;
use App\Models\Profile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_must_log_in_before_submitting_a_comment(): void
    {
        $film = $this->createFilm();

        $this->get(route('movies.show', $film))
            ->assertOk()
            ->assertSee(route('login'))
            ->assertSee(route('register'))
            ->assertDontSee(route('kritik.store', $film));

        $this->post(route('kritik.store', $film), [
            'comment' => 'Komentar dari tamu.',
        ])->assertRedirect(route('login'));

        $this->assertDatabaseCount('kritiks', 0);
    }

    public function test_authenticated_user_can_submit_a_comment(): void
    {
        $film = $this->createFilm();
        $user = $this->createUser();

        $this->actingAs($user)
            ->post(route('kritik.store', $film), [
                'comment' => 'Komentar anggota.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('kritiks', [
            'film_id' => $film->id,
            'user_id' => $user->id,
            'comment' => 'Komentar anggota.',
            'rating' => 0,
        ]);
    }

    private function createFilm(): Film
    {
        $genre = Genre::create(['name' => 'Drama']);

        return Film::create([
            'title' => 'Film Uji',
            'sinopsis' => 'Sinopsis film uji.',
            'year' => 2026,
            'poster' => 'posters/film.jpg',
            'genre_id' => $genre->id,
        ]);
    }

    private function createUser(): User
    {
        $role = Role::create(['name' => 'user']);
        $profile = Profile::create([
            'umur' => 25,
            'bio' => 'Anggota uji',
            'alamat' => 'Jakarta',
        ]);

        return User::create([
            'name' => 'Pengguna Uji',
            'email' => 'user@example.com',
            'password' => 'password',
            'profile_id' => $profile->id,
            'role_id' => $role->id,
        ]);
    }
}
