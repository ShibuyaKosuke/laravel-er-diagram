<?php

namespace ShibuyaKosuke\LaravelErDiagram\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Schema;

class Table extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'INFORMATION_SCHEMA.TABLES';

    /**
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope('table_schema', static function (Builder $builder) {
            $builder->where([
                ['TABLE_SCHEMA', '=', Schema::getConnection()->getDatabaseName()],
                ['TABLE_CATALOG', '=', 'def']
            ]);
        });
    }

    /**
     * @return HasMany
     */
    public function columns(): HasMany
    {
        return $this->hasMany(Column::class, 'TABLE_NAME', 'TABLE_NAME');
    }

    /**
     * @return HasMany
     */
    public function keyColumnUsages(): HasMany
    {
        return $this->hasMany(KeyColumnUsage::class, 'TABLE_NAME', 'TABLE_NAME');
    }

    /**
     * @return string
     */
    public function getClassDiagramAttribute()
    {
        $columns = $this->columns->map(function (Column $column) {
            return sprintf('%s %s', $column->DATA_TYPE, $column->COLUMN_NAME);
        })->implode("\n        ");
        return sprintf("    %s {\n        %s\n    }", $this->TABLE_NAME, $columns);
    }
}
