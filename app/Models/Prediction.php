<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prediction extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'predicted_calories', 'trend_alert', 'period_start', 'period_end'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

