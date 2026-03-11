<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RekeningKoran extends Model
{
    protected $table = 'rekening_koran';
    protected $fillable = [
        'kode_skpd',
        'bulan',
        'tahun',
    ];

    /**
     * Get the SKPD that owns the rekening koran.
     */
    public function skpd(): BelongsTo
    {
        return $this->belongsTo(Skpd::class, 'kode_skpd', 'kode_skpd');
    }
}
