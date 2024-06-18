<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Blogger extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'alias',
        'comment',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function generateLinks(): HasMany
    {
        return $this->hasMany(GenerateLink::class);
    }
}
