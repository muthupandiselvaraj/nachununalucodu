<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClaimAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('claim_attachments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('claims_id')->unsigned();
            $table->string('source');
            $table->foreign('claims_id')->references('id')->on('claims');
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
        Schema::drop('claim_attachments');
    }
}
