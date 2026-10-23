<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
    use HasFactory;
    protected $table = 'films';
    protected $fillable = ['title','sinopsis','year','poster','genre_id'];

    // Relasi ke tabel Peran / Cast
    public function perans()
    {
        return $this->hasMany(Peran::class, 'film_id');
    }

    // Relasi ke tabel Kritik / Komentar
    public function kritiks()
    {
        return $this->hasMany(Kritik::class, 'film_id');
    }

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }

    public function posterUrl(): ?string
    {
        if (!$this->poster) {
            return null;
        }

        $path = str_starts_with($this->poster, 'storage/')
            ? $this->poster
            : 'storage/' . $this->poster;

        return asset($path);
    }

    public function posterStoragePath(): ?string
    {
        if (!$this->poster) {
            return null;
        }

        return str_starts_with($this->poster, 'storage/')
            ? substr($this->poster, strlen('storage/'))
            : $this->poster;
    }
}