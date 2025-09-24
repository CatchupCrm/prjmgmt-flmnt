<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up()
    {
        Schema::table('ticket_statuses', function (Blueprint $table) {
            $table->integer('order')->default(1);
        });
    }

    public function down()
    {
        Schema::table('ticket_statuses', function (Blueprint $table) {
            $table->dropColumn('order');
        });
    }
};
