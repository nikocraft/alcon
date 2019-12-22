<?php

namespace App\Models\Core\Settings;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Spatie\SchemalessAttributes\SchemalessAttributes;

class Setting extends Model
{
    protected $table = "settings";

    protected $fillable = ['key', 'value', 'meta'];

    public $casts = [
        'meta' => 'array',
    ];

    public function getMetaAttribute(): SchemalessAttributes
    {
        $columnName = 'meta';
        return SchemalessAttributes::createForModel($this, $columnName);
    }

    public function scopeWithMeta(): Builder
    {
        $columnName = 'meta';
        return SchemalessAttributes::scopeWithSchemalessAttributes($columnName);
    }
}
