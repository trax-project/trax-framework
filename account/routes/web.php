<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::middleware(['web', 'auth', 'locale'])->namespace('Trax\Account\Http\Controllers')->group(function () {
    
    /**
     * Views routes.
     */

    // Users
    Route::get('trax/ui/account/my-profile', 'AccountViewsController@myProfile')->name('trax.ui.account.my-profile');
    if (config('trax-account.services.users')) {
        Route::get('trax/ui/account/user/crud', 'AccountViewsController@userCrud')->name('trax.ui.account.user.crud');
        Route::get('trax/ui/account/user/edit/{id}', 'AccountViewsController@userEdit')->name('trax.ui.account.user.edit');
    }

    // Entities
    if (config('trax-account.services.entities')) {
        Route::get('trax/ui/account/entity/crud', 'AccountViewsController@entityCrud')->name('trax.ui.account.entity.crud');
    }

    // Roles
    if (config('trax-account.services.roles')) {
        Route::get('trax/ui/account/role/crud', 'AccountViewsController@roleCrud')->name('trax.ui.account.role.crud');
        Route::get('trax/ui/account/role/edit/{id}', 'AccountViewsController@roleEdit')->name('trax.ui.account.role.edit');
    }

    // Groups
    if (config('trax-account.services.groups')) {
        Route::get('trax/ui/account/group/crud', 'AccountViewsController@groupCrud')->name('trax.ui.account.group.crud');
        Route::get('trax/ui/account/group/edit/{id}', 'AccountViewsController@groupEdit')->name('trax.ui.account.group.view');
    }

    // Agreements
    if (config('trax-account.services.agreements')) {
        Route::get('trax/ui/account/agreement/edit', 'AccountViewsController@agreementEdit')->name('trax.ui.account.agreement.edit');
        Route::get('trax/ui/account/agreement/approve', 'AccountViewsController@agreementApprove')->name('trax.ui.account.agreement.approve');
        Route::get('trax/ui/account/agreement/view', 'AccountViewsController@agreementView')->name('trax.ui.account.agreement.view');
    }

    // Basic HTTP Clients
    if (config('trax-account.services.basic-clients')) {
        Route::get('trax/ui/account/basic-client/crud', 'AccountViewsController@basicClientCrud')->name('trax.ui.account.basic-client.crud');
    }

});
