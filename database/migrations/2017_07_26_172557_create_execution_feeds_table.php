<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExecutionFeedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('execution_feeds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('execution_id')->nullable()->unsigned();
            $table->string('type', 1);
            $table->string('title');
            $table->text('message');
            $table->timestamps();
            $table->foreign('execution_id')->references('id')->on('executions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('execution_feeds');
    }
}
