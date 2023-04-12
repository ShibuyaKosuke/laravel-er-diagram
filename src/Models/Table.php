<?php

namespace ShibuyaKosuke\LaravelErDiagram\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Schema;

/**
 * Class Table
 * @package ShibuyaKosuke\LaravelErDiagram\Models
 * @property Column[] $columns
 * @property KeyColumnUsage[] $keyColumnUsages
 * @property string TABLE_CATALOG
 * @property string TABLE_SCHEMA
 * @property string TABLE_NAME
 * @property string TABLE_TYPE
 * @property string ENGINE
 * @property int VERSION
 * @property string ROW_FORMAT
 * @property int TABLE_ROWS
 * @property int AVG_ROW_LENGTH
 * @property int DATA_LENGTH
 * @property int MAX_DATA_LENGTH
 * @property int INDEX_LENGTH
 * @property int DATA_FREE
 * @property int AUTO_INCREMENT
 * @property string CREATE_TIME
 * @property string UPDATE_TIME
 * @property string CHECK_TIME
 * @property string TABLE_COLLATION
 * @property int CHECKSUM
 * @property string CREATE_OPTIONS
 * @property string TABLE_COMMENT
 * @property string $class_diagram
 */
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
        })->implode("\n  ");
        return sprintf(" %s {\n  %s\n }", $this->TABLE_NAME, $columns);
    }
}
