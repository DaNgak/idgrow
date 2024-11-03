<?php

namespace Database\Seeders;

use App\Models\Mutation;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DummySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Set allow foreign key 
        DB::statement('SET FOREIGN_KEY_CHECKS=0;'); // Disable foreign key checks
        Mutation::truncate();
        Product::truncate();
        User::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;'); // Re-enable foreign key checks

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
