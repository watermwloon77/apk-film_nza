<?php

namespace Tests\Feature;

use App\Models\Film;
use App\Models\Genre;
use App\Models\Profile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_admin_users_can_access_the_dashboard(): void
    {
        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('login'));

        $user = $this->makeUser('user');

        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_admin_can_view_dashboard_and_manage_films(): void
    {
        Storage::fake('public');

        $admin = $this->makeUser('admin');
        $genre = Genre::create(['name' => 'Drama']);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Dashboard Admin')
            ->assertSee('Total Film');

        $this->get(route('admin.movies.create'))
            ->assertOk()
            ->assertSee('Tambah Film');

        $this->post(route('admin.movies.store'), [
            'title' => 'Film Baru',
            'sinopsis' => 'Sinopsis film baru.',
            'year' => now()->year,
            'genre_id' => $genre->id,
            'poster' => UploadedFile::fake()->createWithContent(
                'poster.png',
                base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/lN8AAAAASUVORK5CYII=')
            ),
        ])->assertRedirect(route('admin.movies.index'));

        $film = Film::where('title', 'Film Baru')->firstOrFail();
        $this->assertDatabaseHas('films', [
            'id' => $film->id,
            'genre_id' => $genre->id,
        ]);
        Storage::disk('public')->assertExists($film->poster);
        $this->assertStringContainsString('/storage/posters/', $film->posterUrl());

        $this->get(route('admin.movies.edit', $film))
            ->assertOk()
            ->assertSee('Edit Film');

        $this->put(route('admin.movies.update', $film), [
            'title' => 'Film Diperbarui',
            'sinopsis' => 'Sinopsis diperbarui.',
            'year' => now()->year,
            'genre_id' => $genre->id,
        ])->assertRedirect(route('admin.movies.index'));

        $this->assertDatabaseHas('films', [
            'id' => $film->id,
            'title' => 'Film Diperbarui',
        ]);

        $this->delete(route('admin.movies.destroy', $film))
            ->assertRedirect(route('admin.movies.index'));

        $this->assertDatabaseMissing('films', ['id' => $film->id]);
        Storage::disk('public')->assertMissing($film->poster);
    }

    private function makeUser(string $roleName): User
    {
        $role = Role::firstOrCreate(['name' => $roleName]);
        $profile = Profile::create([
            'umur' => 25,
            'bio' => 'Test user',
            'alamat' => 'Jakarta',
        ]);

        return User::create([
            'name' => ucfirst($roleName),
            'email' => $roleName . '@example.com',
            'password' => 'password',
            'profile_id' => $profile->id,
            'role_id' => $role->id,
        ]);
    }
}
