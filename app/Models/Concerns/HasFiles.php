<?php

namespace App\Models\Concerns;

use App\Models\File;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasFiles
{
    protected function scopeWithFiles($query): void
    {
        $query->with('files');
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class);
    }
}
