<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('company')->nullable();
            $table->string('counterparty')->nullable();
            $table->string('InOrOut')->nullable();
            $table->date('dateOfDoc')->nullable();
            $table->string('typeOfDoc')->nullabe();
            $table->integer('numberOnDoc')->nullable();
            $table->text('description')->nullable();
            $table->string('file')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('documents');
    }
}
