<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Habit extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'icon',
        'user_id',
        'type',
        'duration',
        'schedule_time',
        'description'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function trackings()
    {
        return $this->hasMany(HabitTracking::class, 'habit_id', 'id');
    }
}
