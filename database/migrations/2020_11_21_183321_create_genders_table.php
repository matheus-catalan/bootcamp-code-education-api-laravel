<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGendersTable extends Migration
{

    public function up()
    {
        Schema::create('genders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('genders');
    }
}
