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
    public function run()
    {
        $this->call([
            UserSeeder::class,
            ItemSeeder::class,
        ]);

        \App\Models\Customer::factory(1000)->create();

        $items = \App\Models\Item::all();
        $itemCount = $items->count();

        Purchase::factory(100)->create()->each(function (Purchase $purchase) use ($items, $itemCount) {
            $itemsArray = $items->random(rand(1, $itemCount))->pluck('id')->all();
            forEach($itemsArray as $id) {
                $purchase->items()->attach(
                    $id,
                    ['quantity' => rand(1, 100)]
                );
            }
            
        });

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
