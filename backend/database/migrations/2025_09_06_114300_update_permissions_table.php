<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('permissions') && !Schema::hasColumn('permissions', 'is_protected')) {
            Schema::table('permissions', function (Blueprint $table) {
                $table->boolean('is_protected')->default(false)->after('group');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('permissions', 'is_protected')) {
            Schema::table('permissions', function (Blueprint $table) {
                $table->dropColumn('is_protected');
            });
        }
    }
};
