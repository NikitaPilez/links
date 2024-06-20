<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Redirect extends Model
{
    use HasFactory;

    protected $fillable = [
        'generate_link_id',
        'geo',
    ];

    public function generateLink(): BelongsTo
    {
        return $this->belongsTo(GenerateLink::class);
    }
}
