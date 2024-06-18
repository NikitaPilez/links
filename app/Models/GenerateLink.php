<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GenerateLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'domain_id',
        'blogger_id',
        'user_id',
        'link_id',
        'scenario',
        'generated_link',
    ];

    public function domain(): BelongsTo
    {
        return $this->belongsTo(Domain::class);
    }

    public function blogger(): BelongsTo
    {
        return $this->belongsTo(Blogger::class);
    }

    public function link(): BelongsTo
    {
        return $this->belongsTo(Link::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
