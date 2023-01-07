<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Purchase;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */

    // public function applyItems($instance, $items, $numberOfItems)
    // {
    //     $itemsArray = $items->random(rand(1, $numberOfItems))->pluck('id')->all();
    //     foreach ($itemsArray as $id) {
    //         $instance->items()->attach(
    //             $id,
    //             ['quantity' => rand(1, 100)]
    //         );
    //     }
    // }

    public function run()
    {

        \App\Models\Customer::factory(1000)->create();

        $this->call([
            UserSeeder::class,
            ItemSeeder::class,
            PurchaseSeeder::class,
            RankSeeder::class,
        ]);

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
