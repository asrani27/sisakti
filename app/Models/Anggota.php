<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Anggota extends Model
{
    use HasFactory;

    protected $table = 'anggota';

    protected $fillable = [
        'nip',
        'nama',
        'telp',
        'user_id',
        'foto',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the route key for the model.
     * This tells Laravel to use 'id' for route parameter binding
     * when the route uses {anggotum} parameter name.
     */
    public function getRouteKeyName(): string
    {
        return 'id';
    }
}