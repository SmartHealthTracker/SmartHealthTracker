<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HabitLog extends Model
{
    protected $fillable = [
        'habit_saif_id',
        'user_id',
        'value',
        'logged_at',
    ];

    // Add this mutator to map habit_id to habit_saif_id
    public function setHabitIdAttribute($value)
    {
        $this->attributes['habit_saif_id'] = $value;
    }

    // Add this accessor for reading
    public function getHabitIdAttribute()
    {
        return $this->habit_saif_id;
    }

    // Relation vers l'habitude associée
    public function habit()
    {
        return $this->belongsTo(HabitSaif::class, 'habit_saif_id');
    }

    // Relation vers l'utilisateur qui a créé le log
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}