<?php

use App\Models\Resource;
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
        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->string('address');
            $table->string('name', 128);
            $table->integer('utc_offset_seconds'); // Seconds
            $table->string('open_time_local', 8); // HH:mm:ss
            $table->string('close_time_local', 8); // HH:mm:ss
            $table->timestamps();
            $table->boolean('deleted')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facilities');
    }
};
