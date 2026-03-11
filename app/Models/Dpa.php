<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dpa extends Model
{
    protected $table = 'dpa';
    protected $guarded = ['id'];

    /**
     * Get the SKPD that owns the DPA.
     */
    public function skpd()
    {
        return $this->belongsTo(Skpd::class, 'kode_skpd', 'kode_skpd');
    }
}
