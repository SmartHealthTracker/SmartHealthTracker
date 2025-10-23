<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HabitSaif extends Model
{
    protected $table = 'habitsaif'; // ✅ match your migration exactly

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'category',
        'target_value',
        'unit',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function logs()
    {
        return $this->hasMany(HabitLog::class);
    }
}
