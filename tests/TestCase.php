<?php

namespace ShibuyaKosuke\LaravelErDiagram\Test;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use ShibuyaKosuke\LaravelJetAdminlte\Providers\EventServiceProvider;
use ShibuyaKosuke\LaravelJetAdminlte\Providers\ServiceProvider;

/**
 * Class TestCase
 * @package ShibuyaKosuke\LaravelJetAdminlte\Test
 */
abstract class TestCase extends OrchestraTestCase
{
    /**
     * @param \Illuminate\Foundation\Application $app
     * @return string[]
     */
    protected function getPackageProviders($app)
    {
        return [
            ServiceProvider::class,
            EventServiceProvider::class,
        ];
    }
}
