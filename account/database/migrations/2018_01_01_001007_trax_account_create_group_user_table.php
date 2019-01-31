<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TraxAccountCreateGroupUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trax_account_group_user', function (Blueprint $table) {

            // Group relation
            $table->unsignedInteger('group_id');
            $table->foreign('group_id')
                ->references('id')
                ->on('trax_account_groups')
                ->onDelete('cascade');

            // User relation
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('trax_account_users')
                ->onDelete('restrict');

            // Key
            $table->primary(['group_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trax_account_group_user', function (Blueprint $table) {
            $table->dropForeign('trax_account_group_user_group_id_foreign');
            $table->dropForeign('trax_account_group_user_user_id_foreign');
        });
        Schema::dropIfExists('trax_account_group_user');
    }
}
