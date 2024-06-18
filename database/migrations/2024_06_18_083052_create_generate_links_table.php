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
        Schema::create('generate_links', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('blogger_id');
            $table->unsignedBigInteger('link_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('domain_id');
            $table->string('scenario');
            $table->string('generated_link');

            $table->foreign('blogger_id')->references('id')->on('bloggers')->cascadeOnDelete();
            $table->foreign('link_id')->references('id')->on('links')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('domain_id')->references('id')->on('domains')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('generate_links');
    }
};
