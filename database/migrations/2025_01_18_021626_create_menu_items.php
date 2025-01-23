<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuItems extends Migration
{
    public function up()
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name'); // Name of the menu item
            $table->text('description')->nullable(); // Optional description
            $table->decimal('price', 8, 2); // Price with up to 8 digits and 2 decimal places
            $table->string('type'); // Type of the menu item (e.g., starter, mains, beverage)
            $table->string('image_path')->nullable(); // Path to the item's image
            $table->timestamps(); // Created at and updated at timestamps
        });
    }

    public function down()
    {
        Schema::dropIfExists('menu_items'); // Drops the table if rolled back
    }
}
