<?php

namespace App\Http\Controllers;

use App\Models\{
    Film,
    Genre,
    Kritik,
    User,
};
use App\Http\Requests\StoreFilmRequest;
use App\Http\Requests\UpdateFilmRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FilmController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function movies()
    {

        $genreFilm = null;
        $films = Film::select('id','title', 'poster','year')
                ->orderByDesc('year')
                ->OrderBy('created_at', 'asc')
                ->paginate(18);
        return view('components.movies', compact('films', 'genreFilm'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $genres = Genre::orderBy('name')->get();

        return view('admin.movies.create', compact('genres'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFilmRequest $request)
    {
        // Validasi data sudah diurus oleh StoreFilmRequest, kita tinggal simpan
        $data = $request->validated();

        $data['poster'] = $request->file('poster')->store('posters', 'public');
        Film::create($data);

        return redirect()->route('admin.movies.index')->with('success', 'Film berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Film $film)
    {
        //
        $filmByGenre    = Film::select('id','title', 'poster','year', 'sinopsis')
                        ->where('genre_id', '=', $film->genre_id)
                        ->OrderBy('created_at', 'asc')
                        ->limit(7)
                        ->get();
        $filmByRelease  = Film::select('id','title', 'poster','year')
                        ->where('year', '=', Carbon::now()->format('Y'))
                        ->OrderBy('created_at', 'asc')
                        ->limit(9)
                        ->get();
        $comments        = Kritik::with('user')->select('comment', 'user_id')
                        ->where('film_id', '=', $film->id)
                        ->orderBy('created_at', 'asc')
                        ->limit(10)
                        ->get();
        $perans = $film->perans()->with('cast')->get();

        return view('components.movie-show', compact('film','filmByGenre','filmByRelease','comments', 'perans'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Film $film)
    {
        $genres = Genre::orderBy('name')->get();

        return view('admin.movies.edit', compact('film', 'genres'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFilmRequest $request, Film $film)
    {
        $data = $request->validated();
        $previousPoster = $film->posterStoragePath();

        if ($request->hasFile('poster')) {
            $data['poster'] = $request->file('poster')->store('posters', 'public');
        }

        $film->update($data);

        if (isset($data['poster']) && $previousPoster && Storage::disk('public')->exists($previousPoster)) {
            Storage::disk('public')->delete($previousPoster);
        }

        return redirect()->route('admin.movies.index')->with('success', 'Film berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Film $film)
    {
        $posterPath = $film->posterStoragePath();

        DB::transaction(function () use ($film) {
            $film->kritiks()->delete();
            $film->perans()->delete();
            $film->delete();
        });

        if ($posterPath && Storage::disk('public')->exists($posterPath)) {
            Storage::disk('public')->delete($posterPath);
        }

        return redirect()->route('admin.movies.index')->with('success', 'Film berhasil dihapus!');
    }

    public function movieHome()
    {
        $films = Film::select('id','title', 'poster','year')
                ->OrderBy('created_at', 'asc')
                ->limit(9)->get();
        $filmFutured = Film::select('id','title', 'poster','year')
                        ->orderByDesc('year')
                        ->OrderBy('created_at', 'asc')
                        ->limit(6)->get();
        $filmRecentAdded = Film::select('id','title', 'poster','year')
                            ->orderByDesc('created_at', 'asc')
                            ->limit(6)->get();
        $filmSlidey = Film::select('title', 'poster','sinopsis')
                        ->orderByDesc('year')
                        ->OrderBy('created_at', 'asc')
                        ->limit(6)->get();

        // Ubah dari 'components/home' menjadi 'home' di bawah ini:
        return view('home', compact('filmSlidey','films','filmFutured','filmRecentAdded'));
    }

    public function moviesByGenre($genre)
    {
        //
        $genreFilm = Genre::where('id', '=', $genre)->first();
        $films = Film::select('id','title', 'poster','year')
                ->where('genre_id', '=', $genre)
                ->orderByDesc('year')
                ->OrderBy('created_at', 'asc')
                ->paginate(18);
        return view('components.movies', compact('films','genreFilm'));
    }

    public function adminIndex()
    {
        $films = Film::with('genre')->orderByDesc('created_at')->paginate(10);
        $stats = [
            'films' => Film::count(),
            'genres' => Genre::count(),
            'users' => User::count(),
            'reviews' => Kritik::count(),
        ];

        return view('admin.movies.index', compact('films', 'stats'));
    }
}
