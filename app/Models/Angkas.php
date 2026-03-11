<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Angkas extends Model
{
    protected $table = 'angkas';
    protected $guarded = ['id'];

    /**
     * Get the SKPD that owns the Angkas.
     */
    public function skpd(): BelongsTo
    {
        return $this->belongsTo(Skpd::class, 'kode_skpd', 'kode_skpd');
    }
}
