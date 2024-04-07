<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 64);
            $table->string('surname', 64);
            $table->string('patronymic', 64)->nullable();
            $table->string('login', 64)->unique();
            $table->string('password', 64);
            $table->date('birth');
            $table->string('email')->unique();
            $table->bigInteger('telephone')->unique();
            $table->string('api_token', 128)->nullable()->unique();
            $table->foreignId('role_id')->constrained('roles', 'id')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
