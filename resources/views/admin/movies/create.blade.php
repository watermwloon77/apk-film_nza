@extends('templates.index')

@section('content')
<div class="container" style="padding: 35px 0 55px;">
    <div class="clearfix" style="margin-bottom: 20px;">
        <h2 class="pull-left" style="margin-top: 0;">Tambah Film</h2>
        <a href="{{ route('admin.movies.index') }}" class="btn btn-default pull-right">Kembali ke Dashboard</a>
    </div>

    @include('admin.movies.form', [
        'film' => null,
        'genres' => $genres,
        'action' => route('admin.movies.store'),
        'submitLabel' => 'Simpan Film',
    ])
</div>
@endsection
