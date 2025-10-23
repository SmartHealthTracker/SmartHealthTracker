<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    protected $fillable = ['title', 'content', 'category', 'created_by','image'];

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function userResources()
    {
        return $this->hasMany(UserResource::class);
    }

    public function recommendedResources($limit = 5)
{
    // 1. Trouver les utilisateurs qui ont vu cette ressource
    $userIds = $this->userResources()->pluck('user_id');

    // 2. Trouver d'autres ressources vues par ces utilisateurs
    $recommendedIds = UserResource::whereIn('user_id', $userIds)
        ->where('resource_id', '!=', $this->id)
        ->groupBy('resource_id')
        ->selectRaw('resource_id, COUNT(*) as views_count')
        ->orderByDesc('views_count')
        ->take($limit)
        ->pluck('resource_id');

    // 3. Retourner les objets Resource
    return Resource::whereIn('id', $recommendedIds)->get();
}

}

