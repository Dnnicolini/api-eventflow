<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Seed Brazilian locations.
     */
    public function run(): void
    {
        $locations = [
            [
                'name' => 'Sao Paulo Expo',
                'latitude' => -23.6476253,
                'longitude' => -46.6191913,
                'address' => 'Rod. dos Imigrantes, Km 1.5 - Sao Paulo - SP',
            ],
            [
                'name' => 'RioCentro',
                'latitude' => -22.9766188,
                'longitude' => -43.4144452,
                'address' => 'Av. Salvador Allende, 6555 - Rio de Janeiro - RJ',
            ],
            [
                'name' => 'Centro de Convencoes Ulysses Guimaraes',
                'latitude' => -15.7950496,
                'longitude' => -47.8811365,
                'address' => 'Eixo Monumental - Brasilia - DF',
            ],
            [
                'name' => 'Arena Fonte Nova',
                'latitude' => -12.9777445,
                'longitude' => -38.5047225,
                'address' => 'Ladeira da Fonte das Pedras, s/n - Salvador - BA',
            ],
            [
                'name' => 'Mineirao',
                'latitude' => -19.865811,
                'longitude' => -43.971271,
                'address' => 'Av. Antonio Abrahao Caram, 1001 - Belo Horizonte - MG',
            ],
        ];

        foreach ($locations as $location) {
            Location::updateOrCreate(
                ['name' => $location['name']],
                $location
            );
        }
    }
}
