<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class MenuItemsSeeder extends Seeder
{
    public function run()
    {
        DB::table('menu_items')->insert([
            [
                'name' => 'Bspring Rolls',
                'description' => 'Crispy rolls stuffed with vegetables.',
                'highPrice' => 4.99,
                'realPrice' => 3.99,
                'type' => 'starter',
                'image_path' => '/assets/images/menu/menu-1.webp',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Chicken Wings',
                'description' => 'Spicy grilled chicken wings.',
                'highPrice' => 6.99,
                'realPrice' => 5.99,
                'type' => 'starter',
                'image_path' => '/assets/images/menu/menu-2.webp',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Tomato Soup',
                'description' => 'Rich and creamy tomato soup.',
                'highPrice' => 3.99,
                'realPrice' => 2.99,
                'type' => 'starter',
                'image_path' => '/assets/images/menu/menu-3.webp',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Grilled Chicken',
                'description' => 'Juicy grilled chicken breast.',
                'highPrice' => 12.99,
                'realPrice' => 10.99,
                'type' => 'mains',
                'image_path' => '/assets/images/menu/menu-4.webp',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Beef Steak',
                'description' => 'Tender and flavorful beef steak.',
                'highPrice' => 15.99,
                'realPrice' => 13.99,
                'type' => 'mains',
                'image_path' => '/assets/images/menu/menu-5.webp',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Vegetable Stir Fry',
                'description' => 'Mixed vegetables sautéed in a savory sauce.',
                'highPrice' => 10.99,
                'realPrice' => 8.99,
                'type' => 'mains',
                'image_path' => '/assets/images/menu/menu-6.webp',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Chicken Momo',
                'description' => 'Steamed dumplings filled with minced chicken.',
                'highPrice' => 8.99,
                'realPrice' => 7.49,
                'type' => 'momo',
                'image_path' => '/assets/images/menu/menu-1.webp',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Vegetable Momo',
                'description' => 'Dumplings stuffed with fresh veggies.',
                'highPrice' => 7.99,
                'realPrice' => 6.49,
                'type' => 'momo',
                'image_path' => '/assets/images/menu/menu-1.webp',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Chocolate Milkshake',
                'description' => 'Rich and creamy chocolate milkshake.',
                'highPrice' => 4.99,
                'realPrice' => 3.99,
                'type' => 'beverage',
                'image_path' => '/assets/images/menu/menu-three-4.webp',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Lemonade',
                'description' => 'Refreshing homemade lemonade.',
                'highPrice' => 2.99,
                'realPrice' => 1.99,
                'type' => 'beverage',
                'image_path' => '/assets/images/menu/menu-three-1.webp',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Chicken Nuggets',
                'description' => 'Crispy chicken nuggets for kids.',
                'highPrice' => 5.99,
                'realPrice' => 4.49,
                'type' => 'kids',
                'image_path' => '/assets/images/menu/menu-three-2.webp',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Mac and Cheese',
                'description' => 'Creamy macaroni and cheese for kids.',
                'highPrice' => 6.49,
                'realPrice' => 5.49,
                'type' => 'kids',
                'image_path' => '/assets/images/menu/menu-three-3.webp',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
