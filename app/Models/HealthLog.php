<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'water',           // eau bue en litres
        'weight',          // poids en kg
        'height',          // taille en cm
        'steps',           // nombre de pas
        'food_name',       // nom de l'aliment
        'calories',        // calories consommées
        'protein',         // protéines en grammes
        'carbs',           // glucides en grammes
        'fat',             // lipides en grammes
        'sleep_hours',     // heures de sommeil
        'heart_rate',      // rythme cardiaque
        'blood_pressure',  // pression artérielle
        'date'             // date du log
    ];

    protected $dates = [
        'date',            // permet de manipuler facilement les dates
        'created_at',
        'updated_at'
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
