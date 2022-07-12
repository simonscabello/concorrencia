<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Extrato extends Model
{
    use HasFactory;

    protected $fillable = [
        'conta_origem_id',
        'conta_destino_id',
        'operacao',
        'valor',
    ];

    public function conta(): BelongsTo
    {
        return $this->belongsTo(Conta::class);
    }
}
