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
        Schema::create('eployees', function (Blueprint $table) {
            $table->id();
            $table->string("first_name");
            $table->string("middle_name");
            $table->string("last_name");
            $table->string("address");
            $table->char("zip_code");
            $table->date("birth_date");
            $table->date("date_hired");
            // Foriegn Keys
            $table->foreignId("country_id")
                ->nullable()
                ->references("id")
                ->on("countries")
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId("state_id")
                ->nullable()
                ->references("id")
                ->on("states")
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId("city_id")
                ->nullable()
                ->references("id")
                ->on("cities")
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId("department_id")
                ->nullable()
                ->references("id")
                ->on("departments")
                ->cascadeOnUpdate()->cascadeOnDelete();
                $table->foreignId("team_id")
                ->nullable()
                ->references("id")
                ->on("teams")
                ->cascadeOnUpdate()->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eployees');
    }
};
