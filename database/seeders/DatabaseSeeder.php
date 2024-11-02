<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Mutation;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Buat 5 produk
        $products = Product::factory()->count(5)->create();

        // Buat user admin
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
        ]);

        // Buat 3 mutasi untuk admin
        Mutation::factory()->count(3)->create([
            'user_id' => $admin->id,
            'product_id' => $products->random()->id, // Ambil produk secara acak
        ]);

        // Buat user 1
        $user1 = User::create([
            'name' => 'User 1',
            'email' => 'user1@gmail.com',
            'password' => bcrypt('password'),
        ]);

        // Buat 4 mutasi untuk user 1
        Mutation::factory()->count(4)->create([
            'user_id' => $user1->id,
            'product_id' => $products->random()->id, // Ambil produk secara acak
        ]);
    }
}
