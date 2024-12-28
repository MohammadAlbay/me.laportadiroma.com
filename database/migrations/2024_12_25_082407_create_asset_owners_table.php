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
        Schema::create('asset_owners', function (Blueprint $table) {
            $table->bigInteger("id")->autoIncrement();
            $table->bigInteger("asset_id");
            $table->bigInteger("user_id");
            $table->enum("state", ["Active", "Inactive"])->default("Active");
            $table->timestamp("start_date")->useCurrent();
            $table->timestamp("end_date")->nullable();
            $table->timestamps();

            $table->foreign("asset_id")->references("id")
                ->on("assets")
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_owners');
    }
};
