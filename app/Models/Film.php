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
}