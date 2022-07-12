<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conta extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero',
        'saldo',
    ];

    public function extratos(): HasMany
    {
        return $this->hasMany(Extrato::class, 'conta_origem_id')->orderBy('created_at', 'desc');
    }
}
