<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call('Database\Seeders\HospitalSeeder'::class);
        $this->call('Database\Seeders\ConsultaSeeder'::class);
        $this->call('Database\Seeders\PacienteSeeder'::class);
    }
}
