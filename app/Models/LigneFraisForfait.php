<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LigneFraisForfait extends Model
{
    use HasFactory;

    protected $table = 'ligne_frais_forfait';

    protected $fillable = [
        'fiche_frais_id', 'frais_forfait_id', 'quantite'
    ];

    public function ficheFrais()
    {
        return $this->belongsTo(FichesFrais::class, 'fiche_frais_id');
    }

    public function fraisForfait()
    {
        return $this->belongsTo(FraisForfait::class, 'frais_forfait_id');
    }
    public function forfait()
{
    return $this->belongsTo(FraisForfait::class, 'frais_forfait_id');
}

}
