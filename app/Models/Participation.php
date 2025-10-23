<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Challenge;
use App\Models\User;

class Participation extends Model
{
    use HasFactory;

    // Fillable fields
    protected $fillable = [
        'challenge_id',
        'user_id',
        'status',
        'age',
        'weight', // poid
    ];

    // Default attributes
    protected $attributes = [
        'status' => 'pending',
    ];

    /**
     * Participation belongs to a challenge
     */
    public function challenge()
    {
        return $this->belongsTo(Challenge::class);
    }

    /**
     * Participation belongs to a user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
