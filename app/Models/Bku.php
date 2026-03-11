<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bku extends Model
{
    protected $table = 'bku';
    protected $fillable = [
        'kode_skpd',
        'bulan',
        'tahun',
    ];

    public function skpd(): BelongsTo
    {
        return $this->belongsTo(Skpd::class, 'kode_skpd', 'kode_skpd');
    }

    public function details(): HasMany
    {
        return $this->hasMany(BkuDetail::class);
    }
}
