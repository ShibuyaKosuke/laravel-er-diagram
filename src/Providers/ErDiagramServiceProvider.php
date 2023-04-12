<?php

namespace ShibuyaKosuke\LaravelErDiagram\Providers;

use Illuminate\Support\ServiceProvider;
use ShibuyaKosuke\LaravelErDiagram\Console\ErOutputCommand;

/**
 * Class ErDiagramServiceProvider
 * @package ShibuyaKosuke\LaravelErDiagram\Providers
 */
class ErDiagramServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot()
    {
        $this->registerCommands();
    }

    /**
     * @return void
     */
    protected function registerCommands()
    {
        $this->app->singleton('command.shibuyakosuke.er.output', function () {
            return new ErOutputCommand();
        });

        $this->commands([
            'command.shibuyakosuke.er.output'
        ]);
    }

    /**
     * @return array
     */
    public function provides(): array
    {
        return [
            'command.shibuyakosuke.er.output'
        ];
    }
}
