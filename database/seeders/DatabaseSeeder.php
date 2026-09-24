<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(FilmSeeder::class);

        $admin = User::firstOrNew([
            'email' => env('ADMIN_EMAIL', 'admin@example.com'),
        ]);

        if (! $admin->exists) {
            $adminPassword = env('ADMIN_PASSWORD');

            if (blank($adminPassword)) {
                throw new \RuntimeException('Définissez ADMIN_PASSWORD avant de créer le compte administrateur.');
            }

            $admin->forceFill([
                'name' => 'Administrateur',
                'first_name' => 'Admin',
                'pseudo' => 'admin',
                'password' => $adminPassword,
                'is_admin' => true,
            ])->save();
        }
    }
}
