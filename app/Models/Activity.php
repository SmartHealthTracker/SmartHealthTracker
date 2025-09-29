<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'calories_per_hour'];

    public function logs()
    {
        return $this->hasMany(ActivityLog::class);
    }
}
