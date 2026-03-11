<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aturan extends Model
{
    protected $table = 'aturan';

    protected $fillable = [
        'kategori',
        'judul',
        'nomor',
        'tahun',
        'file',
        'fungsi',
    ];
}
