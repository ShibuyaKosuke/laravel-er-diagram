<?php

namespace ShibuyaKosuke\LaravelErDiagram\Console;

use Illuminate\Console\Command;
use ShibuyaKosuke\LaravelErDiagram\ErDiagram;
use ShibuyaKosuke\LaravelErDiagram\Models\Table;

class ErOutputCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'output:er';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Output ER diagram for Mermaid.';

    /**
     * @return void
     */
    public function handle(): void
    {
        $tables = Table::with(['columns'])->get();
        $erDiagram = new ErDiagram($tables);
        $erDiagram->output();
    }
}
