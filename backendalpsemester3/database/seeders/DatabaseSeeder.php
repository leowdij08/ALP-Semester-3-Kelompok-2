<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserOrganisasi;
use App\Models\UserPerusahaan;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'email' => 'organisation@example.com',
            'level' => 'organisasi',
        ]);
        User::factory()->create([
            'email' => 'company@example.com',
            'level' => 'perusahaan',
        ]);

        DB::table('user_organisasi')->insert([
            'namaorganisasi' => fake()->name(),
            'kotadomisiliorganisasi' => fake()->randomElement(['Makassar', 'Jakarta', 'Surabaya']),
            'nomorteleponorganisasi' => fake()->phoneNumber(),
            "id_user" => 1,
        ]);

        DB::table('user_perusahaan')->insert([
            'namaperusahaan' => fake()->name(),
            'kotadomisiliperusahaan' => fake()->randomElement(['Makassar', 'Jakarta', 'Surabaya']),
            'nomorteleponperusahaan' => fake()->phoneNumber(),
            "id_user" => 2,
        ]);
    }
}
