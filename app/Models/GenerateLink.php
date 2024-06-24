<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function redirects(): HasMany
    {
        return $this->hasMany(Redirect::class);
    }

    public function generateLinkCount(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->redirects->count(),
        );
    }
}
