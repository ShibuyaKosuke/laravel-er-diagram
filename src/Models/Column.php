<?php

namespace ShibuyaKosuke\LaravelErDiagram\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Schema;

/**
 * Class Column
 * @package ShibuyaKosuke\LaravelErDiagram\Models
 * @property string $TABLE_CATALOG
 * @property string $TABLE_SCHEMA
 * @property string $TABLE_NAME
 * @property string $COLUMN_NAME
 * @property int $ORDINAL_POSITION
 * @property string $COLUMN_DEFAULT
 * @property string $IS_NULLABLE
 * @property string $DATA_TYPE
 * @property string $CHARACTER_MAXIMUM_LENGTH
 * @property string $CHARACTER_OCTET_LENGTH
 * @property string $NUMERIC_PRECISION
 * @property string $NUMERIC_SCALE
 * @property string $DATETIME_PRECISION
 * @property string $CHARACTER_SET_NAME
 * @property string $COLLATION_NAME
 * @property string $COLUMN_TYPE
 * @property string $COLUMN_KEY
 * @property string $EXTRA
 * @property string $PRIVILEGES
 * @property string $COLUMN_COMMENT
 * @property string $GENERATION_EXPRESSION
 * @property Table $table
 * @property string $class_diagram
 */
class Column extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'INFORMATION_SCHEMA.COLUMNS';

    /**
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope('table_schema', static function (Builder $builder) {
            $builder->where([
                ['TABLE_SCHEMA', '=', Schema::getConnection()->getDatabaseName()],
                ['TABLE_CATALOG', '=', 'def']
            ])->orderBy('ORDINAL_POSITION');
        });
    }

    /**
     * @return BelongsTo
     */
    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class, 'TABLE_NAME', 'TABLE_NAME');
    }

    /**
     * @param string $value
     * @return boolean
     */
    public function getIsNullableAttribute($value): bool
    {
        return $value === 'YES';
    }
}
