<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('coupon_codes', function (Blueprint $table) {
            $table->dropColumn('validity');
        });
    }

    public function down()
    {
        Schema::table('coupon_codes', function (Blueprint $table) {
            $table->string('validity')->nullable();
        });
    }
};

