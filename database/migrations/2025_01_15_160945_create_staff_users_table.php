<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('staff_users', function (Blueprint $table) {
            $table->id('staff_user_id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('staff_user_img')->nullable();
            $table->string('password');
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
        DB::table('staff_users')->insert([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password'=> Hash::make('123456789'),
            'status' => 1
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_users');
    }
};
