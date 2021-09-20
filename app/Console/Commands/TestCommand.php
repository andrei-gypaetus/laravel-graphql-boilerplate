<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Events\TicketCreatedEvent;
use App\Events\TicketUpdatedEvent;
use App\Events\OrderAddressValidatedEvent;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:test {payload}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test a custom command';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $payload = $this->argument('payload');
        dd($payload);
        return;
    }
}
