<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('game_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('realm_id')->constrained('realms');
            $table->integer('account_id'); // ID аккаунта в базе auth AzerothCore
            $table->string('username'); // Имя аккаунта в AzerothCore
            $table->string('status')->default('active');
            $table->timestamp('last_login')->nullable();
            $table->timestamps();

            // Уникальный индекс для предотвращения дублирования привязок
            $table->unique(['user_id', 'realm_id', 'account_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('game_accounts');
    }
}; 