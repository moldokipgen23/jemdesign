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
        User::factory()->create([
            'name'  => 'Jem Admin',
            'email' => 'admin@jemdesigns.com',
        ]);

        $this->call([
            CategorySeeder::class,
            CollectionSeeder::class,
            HomepageSectionSeeder::class,
            SiteSettingsSeeder::class,
            AttributeSeeder::class,
            DemoProductSeeder::class,
            MarketingContentSeeder::class,
        ]);
    }
}
