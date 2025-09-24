<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up()
    {
        Schema::table('ticket_hours', function (Blueprint $table) {
            $table->longText('comment')->nullable();
        });
    }

    public function down()
    {
        Schema::table('ticket_hours', function (Blueprint $table) {
            $table->dropColumn('comment');
        });
    }
};
