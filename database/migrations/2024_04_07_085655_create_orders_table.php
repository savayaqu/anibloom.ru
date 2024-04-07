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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('address', 255);
            $table->dateTime('dateOrder');
            $table->foreignId('payment_id')->constrained('payments', 'id')->onUpdate('cascade');
            $table->foreignId('user_id')->constrained('users', 'id')->onUpdate('cascade');
            $table->foreignId('status_id')->constrained('statuses', 'id')->onUpdate('cascade');
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
        Schema::dropIfExists('orders');
    }
};
