<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FichesFrais extends Model
{
    use HasFactory;

    protected $table = 'fiches_frais';

    protected $fillable = [
        'mois', 'annee', 'nb_justificatifs', 'montant_valide', 'date_modif', 'id_visiteur', 'id_etat'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_visiteur');
    }

    public function lignesForfait()
    {
        return $this->hasMany(LigneFraisForfait::class, 'fiche_frais_id');
    }

    public function lignesHorsForfait()
    {
        return $this->hasMany(LigneFraisHorsForfait::class, 'fiche_frais_id');
    }

    public function etat()
    {
        return $this->belongsTo(Etat::class, 'id_etat');
    }
}
