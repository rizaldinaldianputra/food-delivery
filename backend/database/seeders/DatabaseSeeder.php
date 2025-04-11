<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        DB::table('roles')->insert([
            ['name' => 'customer', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'merchant', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'courier', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);

        DB::table('users')->insert([
            [
                'name' => 'Customer User',
                'email' => 'customer@example.com',
                'password' => Hash::make('password'),
                'role_id' => 1,
                'address' => 'Customer Address',
                'phone' => '081234567890',
                'gender' => 'male',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Merchant User',
                'email' => 'merchant@example.com',
                'password' => Hash::make('password'),
                'role_id' => 2,
                'address' => 'Merchant Address',
                'phone' => '081234567891',
                'gender' => 'female',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ]);

        DB::table('categories')->insert([
            ['name' => 'Food', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Drinks', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);

        DB::table('products')->insert([
            [
                'merchant_id' => 2,
                'category_id' => 1,
                'name' => 'Burger',
                'description' => 'Delicious beef burger',
                'price' => 50000,
                'image' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'merchant_id' => 2,
                'category_id' => 2,
                'name' => 'Coca Cola',
                'description' => 'Refreshing soft drink',
                'price' => 15000,
                'image' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ]);
    }
}
