<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('discount_rule_parameters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('discount_rule_id')->constrained()->cascadeOnDelete();
            $table->string('param_key');
            $table->string('param_value');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discount_rule_parameters');
    }
};
