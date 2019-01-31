<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TraxNotificationCreateNotificationUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trax_notification_user', function (Blueprint $table) {
            $table->increments('id');
            $table->json('data');
            $table->timestamps();

            // Notification relation
            $table->unsignedInteger('notification_id');
            $table->foreign('notification_id')
                ->references('id')
                ->on('trax_notification')
                ->onDelete('cascade');

            // User relation
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('trax_account_users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trax_notification_user', function (Blueprint $table) {
            $table->dropForeign('trax_notification_user_notification_id_foreign');
            $table->dropForeign('trax_notification_user_user_id_foreign');
        });
        Schema::dropIfExists('trax_notification_user');
    }
}
