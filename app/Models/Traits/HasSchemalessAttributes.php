<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
use Spatie\SchemalessAttributes\SchemalessAttributes;

trait HasSchemalessAttributes
{
    public function getSettingsAttribute(): SchemalessAttributes
    {
        return SchemalessAttributes::createForModel($this, 'settings');
    }

    public function scopeWithSettings(): Builder
    {
        return SchemalessAttributes::scopeWithSchemalessAttributes('settings');
    }
}