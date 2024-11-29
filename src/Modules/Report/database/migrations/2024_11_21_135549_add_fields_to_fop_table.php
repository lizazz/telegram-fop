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
        Schema::table('fops', function (Blueprint $table) {
            $table->string('step')->default('start');
            $table->bigInteger('chat_id');
            $table->integer('tax_id');
            $table->integer('fop_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fops', function (Blueprint $table) {
            $table->dropColumn('step');
            $table->dropColumn('chat_id');
            $table->dropColumn('tax_id');
            $table->dropColumn('fop_id');
        });
    }
};
