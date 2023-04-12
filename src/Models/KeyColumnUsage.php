<?php

namespace ShibuyaKosuke\LaravelErDiagram\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Schema;

/**
 * Class KeyColumnUsage
 * @package ShibuyaKosuke\LaravelErDiagram\Models
 *
 * @property string $relation
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
 * @property string $REFERENCED_TABLE_SCHEMA
 * @property string $REFERENCED_TABLE_NAME
 * @property string $REFERENCED_COLUMN_NAME
 * @property Table $table
 * @property Column $column
 * @property Column $referenced_column
 * @property Table $referenced_table
 * @property string $class_diagram
 */
class KeyColumnUsage extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'INFORMATION_SCHEMA.KEY_COLUMN_USAGE';

    /**
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope('table_schema', static function (Builder $builder) {
            $builder->where([
                ['TABLE_SCHEMA', '=', Schema::getConnection()->getDatabaseName()],
                ['TABLE_CATALOG', '=', 'def']
            ])->whereNotNull('REFERENCED_TABLE_NAME')->orderBy('ordinal_position');
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
     * @return BelongsTo
     */
    public function column(): BelongsTo
    {
        return $this->belongsTo(Column::class, 'COLUMN_NAME', 'COLUMN_NAME');
    }

    /**
     * @return BelongsTo
     */
    public function referencedColumn(): BelongsTo
    {
        return $this->belongsTo(Column::class, 'REFERENCED_COLUMN_NAME', 'COLUMN_NAME');
    }

    /**
     * @return string
     */
    public function getRelationAttribute(): string
    {
        $column = $this->column->IS_NULLABLE ? '}o' : '}|';
        $referencedColumn = $this->referencedColumn->IS_NULLABLE ? 'o|' : '||';

        return sprintf(
            '%s %s %s : "%s:%s"',
            $this->TABLE_NAME,
            "{$column}--{$referencedColumn}",
            $this->REFERENCED_TABLE_NAME,
            $this->COLUMN_NAME,
            $this->REFERENCED_COLUMN_NAME
        );
    }
}
