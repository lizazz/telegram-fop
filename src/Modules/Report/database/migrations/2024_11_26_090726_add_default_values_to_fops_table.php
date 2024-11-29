<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Report\Enums\FopStepEnum;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('fops', function (Blueprint $table) {
            $table->string('name')->default('')->change();
            $table->string('fop_id')->nullable()->change();
            $table->integer('tax_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fops', function (Blueprint $table) {

        });
    }
};
