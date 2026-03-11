<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BkuDetail extends Model
{

    protected $table = 'bku_detail';
    protected $fillable = [
        'bku_id',
        'tanggal',
        'nomor_bukti',
        'uraian',
        'penerimaan',
        'pengeluaran',
        'saldo',
        'status_ocr',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'penerimaan' => 'integer',
        'pengeluaran' => 'integer',
        'saldo' => 'integer',
    ];

    /**
     * Get the BKU that owns the detail.
     */
    public function bku(): BelongsTo
    {
        return $this->belongsTo(Bku::class);
    }
}
