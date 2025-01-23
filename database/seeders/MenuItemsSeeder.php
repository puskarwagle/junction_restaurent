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
                'name' => 'Spring Rolls',
                'description' => 'Crispy rolls stuffed with vegetables.',
                'price' => 4.99,
                'type' => 'starter',
                'image_path' => '/assets/images/menu/menu-1.webp',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Chicken Wings',
                'description' => 'Spicy grilled chicken wings.',
                'price' => 6.99,
                'type' => 'starter',
                'image_path' => '/assets/images/menu/menu-2.webp',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Tomato Soup',
                'description' => 'Rich and creamy tomato soup.',
                'price' => 3.99,
                'type' => 'starter',
                'image_path' => '/assets/images/menu/menu-3.webp',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Grilled Chicken',
                'description' => 'Juicy grilled chicken breast.',
                'price' => 12.99,
                'type' => 'mains',
                'image_path' => '/assets/images/menu/menu-4.webp',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Beef Steak',
                'description' => 'Tender and flavorful beef steak.',
                'price' => 15.99,
                'type' => 'mains',
                'image_path' => '/assets/images/menu/menu-5.webp',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Vegetable Stir Fry',
                'description' => 'Mixed vegetables sautéed in a savory sauce.',
                'price' => 10.99,
                'type' => 'mains',
                'image_path' => '/assets/images/menu/menu-6.webp',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Chicken Momo',
                'description' => 'Steamed dumplings filled with minced chicken.',
                'price' => 8.99,
                'type' => 'momo',
                'image_path' => '/assets/images/menu/menu-1.webp',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Vegetable Momo',
                'description' => 'Dumplings stuffed with fresh veggies.',
                'price' => 7.99,
                'type' => 'momo',
                'image_path' => '/assets/images/menu/menu-1.webp',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Chocolate Milkshake',
                'description' => 'Rich and creamy chocolate milkshake.',
                'price' => 4.99,
                'type' => 'beverage',
                'image_path' => '/assets/images/menu/menu-three-4.webp',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Lemonade',
                'description' => 'Refreshing homemade lemonade.',
                'price' => 2.99,
                'type' => 'beverage',
                'image_path' => '/assets/images/menu/menu-three-1.webp',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Chicken Nuggets',
                'description' => 'Crispy chicken nuggets for kids.',
                'price' => 5.99,
                'type' => 'kids',
                'image_path' => '/assets/images/menu/menu-three-2.webp',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Mac and Cheese',
                'description' => 'Creamy macaroni and cheese for kids.',
                'price' => 6.49,
                'type' => 'kids',
                'image_path' => '/assets/images/menu/menu-three-3.webp',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);        
    }
}

