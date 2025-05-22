<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FraisForfait extends Model
{
    use HasFactory;

    protected $table = 'frais_forfait';

    protected $fillable = [
        'libelle', 'montant', 'unite'
    ];

    public function lignesForfait()
    {
        return $this->hasMany(LigneFraisForfait::class, 'frais_forfait_id');
    }
}
