<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    // Create table
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();                       // Primary key
            $table->string('name');             // User name
            $table->string('email')->unique();  // Email (unique)
            $table->string('password');         // Password

            // Security fields
            $table->integer('failed_attempts')->default(0); // Wrong password count
            $table->timestamp('locked_until')->nullable();  // Lock time

            $table->timestamps();               // created_at & updated_at
        });
    }

    // Drop table
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
