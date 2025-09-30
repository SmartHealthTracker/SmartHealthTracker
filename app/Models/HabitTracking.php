<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HabitTracking extends Model
{
    use HasFactory;

    // Spécifie le nom exact de la table
    protected $table = 'habit_tracking';

    protected $fillable = ['habit_id', 'user_id', 'date', 'progress', 'note', 'state'];

    public function habit() {
        return $this->belongsTo(Habit::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
