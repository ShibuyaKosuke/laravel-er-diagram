<?php

namespace ShibuyaKosuke\LaravelErDiagram\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Builder;

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
