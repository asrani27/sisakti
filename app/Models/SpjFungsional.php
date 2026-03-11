<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpjFungsional extends Model
{
    protected $table = 'spj_fungsional';
    protected $guarded = ['id'];

    public function skpd()
    {
        return $this->belongsTo(Skpd::class, 'kode_skpd', 'kode_skpd');
    }
}
