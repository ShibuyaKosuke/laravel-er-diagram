<?php

namespace ShibuyaKosuke\LaravelErDiagram;

use Illuminate\Database\Eloquent\Collection;
use ShibuyaKosuke\LaravelErDiagram\Models\KeyColumnUsage;
use ShibuyaKosuke\LaravelErDiagram\Models\Table;

/**
 * Class ErDiagram
 * @package ShibuyaKosuke\LaravelErDiagram
 */
class ErDiagram
{
    /**
     * @var Collection
     */
    private Collection $tables;

    /**
     * @param Collection $tables
     */
    public function __construct(Collection $tables)
    {
        $this->tables = $tables;
    }

    /**
     * @return void
     */
    public function output()
    {
        $class_diagrams = $this->tables->map(function (Table $table) {
            return $table->class_diagram;
        })->implode("\n\n");

        $keyColumnUsages = KeyColumnUsage::all();
        $usages = $keyColumnUsages->map(function (KeyColumnUsage $columnUsage) {
            return $columnUsage->relation;
        })->implode("\n    ");

        $output = sprintf("erDiagram\n%s\n\n    %s", $class_diagrams, $usages);
        \Storage::put('er.md', $output);
    }
}
