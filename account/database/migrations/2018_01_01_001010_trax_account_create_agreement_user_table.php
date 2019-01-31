<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TraxAccountCreateAgreementUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trax_account_agreement_user', function (Blueprint $table) {

            // Group relation
            $table->unsignedInteger('agreement_id');
            $table->foreign('agreement_id')
                ->references('id')
                ->on('trax_account_agreements')
                ->onDelete('cascade');

            // User relation
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('trax_account_users')
                ->onDelete('cascade');

            $table->timestamp('created_at');
            $table->primary(['agreement_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trax_account_agreement_user', function (Blueprint $table) {
            $table->dropForeign('trax_account_agreement_user_agreement_id_foreign');
            $table->dropForeign('trax_account_agreement_user_user_id_foreign');
        });
        Schema::dropIfExists('trax_account_agreement_user');
    }
}
