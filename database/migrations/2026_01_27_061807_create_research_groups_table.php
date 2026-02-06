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
        Schema::disableForeignKeyConstraints();

        Schema::create('research_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->nullable()->constrained();
            $table->foreignId('leader_id')->constrained('students');
            $table->string('final_title');
            $table->integer('total_fees')->default(0);
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('research_groups');
    }
};
