<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_buku',
        'kategori_id',
        'gambar'
    ];

    public function kategori(){
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }
}
