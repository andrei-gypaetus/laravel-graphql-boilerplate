<?php

use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\SupportTicketTagsSeeder;
use Database\Seeders\IsoCountriesSeeder;
use Database\Seeders\AddressesSeeder;
use Database\Seeders\RepresentantsSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
        ]);
    }
}
