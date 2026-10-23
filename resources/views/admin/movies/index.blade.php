@extends('templates.index')

@push('style')
<style>
    .admin-page { padding: 35px 0 55px; }
    .admin-heading { margin: 0 0 6px; color: #222; font-weight: 700; }
    .admin-subtitle { color: #777; margin-bottom: 24px; }
    .admin-stat { border: 0; border-radius: 3px; color: #fff; min-height: 112px; }
    .admin-stat .panel-body { padding: 20px; }
    .admin-stat .stat-label { display: block; font-size: 13px; opacity: .9; text-transform: uppercase; }
    .admin-stat .stat-value { display: block; font-size: 30px; font-weight: 700; margin-top: 5px; }
    .admin-stat-blue { background: #e85a24; }
    .admin-stat-dark { background: #252525; }
    .admin-stat-green { background: #3b8b72; }
    .admin-stat-purple { background: #73549a; }
    .admin-table img { height: 64px; width: 46px; object-fit: cover; }
    .admin-table > thead > tr > th { background: #252525; color: #fff; }
</style>
@endpush

@section('content')
<div class="container admin-page">
    <div class="clearfix">
        <div class="pull-left">
            <h2 class="admin-heading">Dashboard Admin</h2>
            <p class="admin-subtitle">Kelola film dan pantau konten One Movies.</p>
        </div>
        <div class="pull-right">
            <a href="{{ route('admin.movies.create') }}" class="btn btn-primary">
                <i class="fa fa-plus"></i> Tambah Film
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <div class="col-sm-6 col-md-3">
            <div class="panel admin-stat admin-stat-blue">
                <div class="panel-body"><span class="stat-label">Total Film</span><span class="stat-value">{{ $stats['films'] }}</span></div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="panel admin-stat admin-stat-dark">
                <div class="panel-body"><span class="stat-label">Genre</span><span class="stat-value">{{ $stats['genres'] }}</span></div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="panel admin-stat admin-stat-green">
                <div class="panel-body"><span class="stat-label">Pengguna</span><span class="stat-value">{{ $stats['users'] }}</span></div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="panel admin-stat admin-stat-purple">
                <div class="panel-body"><span class="stat-label">Ulasan</span><span class="stat-value">{{ $stats['reviews'] }}</span></div>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading clearfix">
            <strong class="pull-left">Daftar Film</strong>
            <a href="{{ route('movies') }}" class="pull-right">Lihat halaman publik <i class="fa fa-arrow-right"></i></a>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped admin-table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Poster</th>
                            <th>Judul Film</th>
                            <th>Genre</th>
                            <th>Tahun</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($films as $index => $film)
                            <tr>
                                <td>{{ $films->firstItem() + $index }}</td>
                                <td>
                                    @if($film->poster)
                                        <img src="{{ $film->posterUrl() }}" alt="Poster {{ $film->title }}">
                                    @else
                                        <span class="text-muted">Tidak ada</span>
                                    @endif
                                </td>
                                <td>{{ $film->title }}</td>
                                <td>{{ $film->genre?->name ?? '-' }}</td>
                                <td>{{ $film->year }}</td>
                                <td>
                                    <a href="{{ route('admin.movies.edit', $film) }}" class="btn btn-warning btn-xs">Edit</a>
                                    <form action="{{ route('admin.movies.destroy', $film) }}" method="POST" class="admin-delete-form" onsubmit="return confirm('Yakin ingin menghapus film ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-xs">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted">Belum ada data film.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $films->links() }}
        </div>
    </div>
</div>
<style>
    .admin-delete-form { display: inline-block; margin-left: 4px; }
</style>
@endsection
