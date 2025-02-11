<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->decimal('realPrice', 8, 2)->change();
        });
    }

    public function down() {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->integer('realPrice')->change(); // Adjust if needed
        });
    }
};
