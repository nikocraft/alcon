<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
use Spatie\SchemalessAttributes\SchemalessAttributes;

trait HasSchemalessAttributes
{
    public function getSettingsAttribute(): SchemalessAttributes
    {
        $columnName = isset($this->schemalessAttributesColumn) ? $this->schemalessAttributesColumn : 'settings';
        return SchemalessAttributes::createForModel($this, $columnName);
    }

    public function scopeWithSettings(): Builder
    {
        $columnName = isset($this->schemalessAttributesColumn) ? $this->schemalessAttributesColumn: 'settings';
        return SchemalessAttributes::scopeWithSchemalessAttributes($columnName);
    }
}