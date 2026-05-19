<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

// Seeder principal para la base de datos que llama a otros seeders específicos para poblar las tablas con datos de ejemplo
class DatabaseSeeder extends Seeder
{
    /**
     * Este método se ejecuta para sembrar la base de datos con datos de ejemplo utilizando los seeders definidos en el
     * proyecto. En este caso, se llaman a UserSeeder y ServiceSeeder para poblar las tablas de usuarios y servicios
     * respectivamente.
     * @return void
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            ServiceSeeder::class,
            HistoricalDataSeeder::class,
        ]);
    }
}
