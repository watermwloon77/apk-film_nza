<div class="panel panel-default">
    <div class="panel-body">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul style="margin-bottom: 0;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ $action }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($film))
                @method('PUT')
            @endif

            <div class="form-group">
                <label for="title">Judul Film</label>
                <input id="title" name="title" class="form-control" value="{{ old('title', $film?->title) }}" required maxlength="255">
            </div>

            <div class="form-group">
                <label for="sinopsis">Sinopsis</label>
                <textarea id="sinopsis" name="sinopsis" class="form-control" rows="5" required>{{ old('sinopsis', $film?->sinopsis) }}</textarea>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="year">Tahun</label>
                        <input id="year" type="number" name="year" class="form-control" value="{{ old('year', $film?->year) }}" min="1888" max="{{ now()->year + 5 }}" required>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="genre_id">Genre</label>
                        <select id="genre_id" name="genre_id" class="form-control" required>
                            <option value="">Pilih genre</option>
                            @foreach($genres as $genre)
                                <option value="{{ $genre->id }}" @selected(old('genre_id', $film?->genre_id) == $genre->id)>{{ $genre->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="poster">Poster {{ isset($film) ? '(opsional, pilih jika ingin mengganti)' : '' }}</label>
                <input id="poster" type="file" name="poster" class="form-control" accept="image/*" {{ isset($film) ? '' : 'required' }}>
                <p class="help-block">Format gambar, maksimal 4 MB.</p>
                @if(isset($film) && $film->poster)
                    <img src="{{ $film->posterUrl() }}" alt="Poster saat ini" style="max-height: 150px; margin-top: 10px;">
                @endif
            </div>

            <button type="submit" class="btn btn-primary">{{ $submitLabel }}</button>
            <a href="{{ route('admin.movies.index') }}" class="btn btn-default">Batal</a>
        </form>
    </div>
</div>
