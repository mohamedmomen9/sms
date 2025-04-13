<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('efinance_method', function (Blueprint $table) {
            $table->string('Method_iso');
            $table->string('Method_name');
            $table->boolean('default_prerecit')->default(false); // Assuming boolean value.
            $table->primary('Method_iso');  //Assuming Method_iso is a unique identifier.
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('efinance_method');
    }
};
