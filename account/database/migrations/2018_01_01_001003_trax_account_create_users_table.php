<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TraxAccountCreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trax_account_users', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->unique();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('admin')->default(0);
            $table->boolean('active')->default(1);
            $table->json('data');
            $table->rememberToken();
            $table->timestamps();

            // Options

            $table->string('source_code')->default('internal')->index();  // Option: UserSources
            $table->string('entity_type_code')->nullable()->index();      // Option: EntityTypes

            // Relations

            $table->unsignedInteger('role_id')->nullable();         // User role (Role)
            $table->foreign('role_id')
                ->references('id')
                ->on('trax_account_roles')
                ->onDelete('restrict');

            $table->unsignedInteger('organization_id')->nullable(); // User organization (Entity)
            $table->foreign('organization_id')
                ->references('id')
                ->on('trax_account_entities')
                ->onDelete('restrict');

            // Entity relation
            $table->unsignedInteger('entity_id')->nullable();       // User entity (Entity)
            $table->foreign('entity_id')
                ->references('id')
                ->on('trax_account_entities')
                ->onDelete('restrict');
            
            // "->>" does not work!
            //$table->string('firstname')->virtualAs('data->>"$.firstname"');
            //$table->string('lastname')->virtualAs('data->>"$.lastname"');
            $table->string('firstname')->virtualAs('JSON_UNQUOTE(JSON_EXTRACT(data, "$.firstname"))');
            $table->string('lastname')->virtualAs('JSON_UNQUOTE(JSON_EXTRACT(data, "$.lastname"))');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trax_account_users', function (Blueprint $table) {
            $table->dropForeign('trax_account_users_role_id_foreign');
            $table->dropForeign('trax_account_users_organization_id_foreign');
            $table->dropForeign('trax_account_users_entity_id_foreign');
        });
        Schema::dropIfExists('trax_account_users');
    }
}
