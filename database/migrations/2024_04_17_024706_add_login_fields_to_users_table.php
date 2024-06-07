<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('last_login_ip', 20)->nullable()->after('remember_token');
            $table->datetime('last_login_at')->nullable()->after('last_login_ip');
            $table->tinyInteger('status')->nullable()->default(0)->after('last_login_at');
            $table->unsignedInteger('user_created')->nullable()->default(0)->after('status');
            $table->unsignedInteger('user_updated')->nullable()->default(0)->after('user_created');
            $table->unsignedInteger('user_deleted')->nullable()->default(0)->after('user_updated');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
