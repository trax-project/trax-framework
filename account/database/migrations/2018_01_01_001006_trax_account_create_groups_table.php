<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TraxAccountCreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trax_account_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->unique();
            $table->json('data');
            $table->timestamps();
            
            // To enabled search
            $table->string('name')->virtualAs('JSON_UNQUOTE(JSON_EXTRACT(data, "$.name"))');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trax_account_groups');
    }
}
