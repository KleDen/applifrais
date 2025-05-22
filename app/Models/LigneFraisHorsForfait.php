<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LigneFraisHorsForfait extends Model
{
    use HasFactory;

    protected $table = 'ligne_frais_hors_forfait';

    protected $fillable = [
        'fiche_frais_id', 'date', 'libelle', 'montant', 'justificatif'
    ];

    public function ficheFrais()
    {
        return $this->belongsTo(FichesFrais::class, 'fiche_frais_id');
    }
}
