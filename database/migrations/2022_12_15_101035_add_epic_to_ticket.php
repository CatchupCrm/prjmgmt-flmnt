<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->foreignId('epic_id')->nullable()->constrained('epics');
        });
    }

    public function down()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign(['epic_id']);
            $table->dropColumn('epic_id');
        });
    }
};
