<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\ParallelTesting;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\ClientRepository;

trait CreatesApplication
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__ . '/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        // Setup default database to use sqlite :memory:
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
        $app['config']->set('database.default', 'testbench');

        return $app;
    }

    /**
     * Create a passport client for testing.
     */
    public function createClient()
    {
        $this->artisan('migrate', ['--database' => 'testbench']);
        $client = app(ClientRepository::class)->createPasswordGrantClient(null, 'test', 'http://localhost');
        config()->set('lighthouse-graphql-passport.client_id', $client->id);
        config()->set('lighthouse-graphql-passport.client_secret', $client->secret);
    }
}
