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
        Schema::create('assets', function (Blueprint $table) {
            $table->bigInteger("id")->autoIncrement();
            $table->string("name");
            $table->json("data");
            $table->bigInteger("created_by")->nullable();
            $table->bigInteger("asset_type_id");
            $table->enum("state", ["Available", "Occupied", "Corrupted", "Repaire Required"])->default("Available");
            $table->timestamps();

            $table->foreign("created_by")->references("id")
                ->on("users")
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreign("asset_type_id")->references("id")
                ->on("asset_types")
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
