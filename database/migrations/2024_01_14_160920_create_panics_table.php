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
        Schema::create('panics', function (Blueprint $table) {
            $table->id();
            $table->string('longitude');
            $table->string('latitude');
            $table->string('panic_type')->nullable();
            $table->text('details')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->bigInteger('wayne_enterprise_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('panics');
    }
};
