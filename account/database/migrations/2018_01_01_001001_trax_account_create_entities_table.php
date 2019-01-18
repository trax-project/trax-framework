<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TraxAccountCreateEntitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trax_account_entities', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->unique();
            $table->json('data');
            $table->timestamps();

            // Options
            
            $table->string('type_code')->nullable()->index();   // Option: EntityTypes

            // Relations

            $table->unsignedInteger('parent_id')->nullable();   // Parent entity (Entity)
            $table->foreign('parent_id')
                ->references('id')
                ->on('trax_account_entities')
                ->onDelete('cascade');

            $table->unsignedInteger('index_id')->nullable();    // Not used
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trax_account_entities', function (Blueprint $table) {
            $table->dropForeign('trax_account_entities_parent_id_foreign');
        });
        Schema::dropIfExists('trax_account_entities');
    }
}
