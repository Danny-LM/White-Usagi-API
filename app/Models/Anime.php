<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Anime extends Model
{
    use HasFactory;

    /**
     * 
     */
    protected $fillable = [
        'title',
        'release_date',
        'end_date',
        'season',
        'synopsis',
        'poster_url',
    ];

    /**
     * 
     */
    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class, 'anime_genre');
    }

    /**
     * 
     */
    public function studios(): BelongsToMany
    {
        return $this->belongsToMany(Studio::class, 'anime_studio');
    }

    /**
     * 
     */
    public function episodes(): HasMany
    {
        return $this->hasMany(Episode::class);
    }
}
