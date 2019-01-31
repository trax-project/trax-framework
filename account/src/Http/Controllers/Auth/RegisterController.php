<?php

namespace Trax\Account\Http\Controllers\Auth;

use App\Http\Controllers\Auth\RegisterController as NativeRegisterController;
use Illuminate\Support\Facades\Validator;

use Trax\Account\AccountServices;
use Trax\Account\Models\User;

class RegisterController extends NativeRegisterController
{
    /**
     * Data Store.
     */
    protected $store;

    /**
     * Create a new controller instance.
     */
    public function __construct(AccountServices $account)
    {
        parent::__construct();
        $this->store = $account->users();
    }

    /**
     * Get a validator for an incoming registration request.
     */
    protected function validator(array $data)
    {
        $rules = [
            'username' => 'string|max:255|unique:' . traxConnection('trax-account', 'User') . '.trax_account_users',
            'email' => 'required|string|email|max:255|unique:' . traxConnection('trax-account', 'User') . '.trax_account_users',
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'lang' => 'string|max:2',
            'password' => 'required|string|min:6|confirmed',
        ];
        if (config('trax-account.auth.username')) $rules['username'] .= '|required';
        return Validator::make($data, $rules);
    }

    /**
     * Create a new user instance after a valid registration.
     */
    protected function create(array $data)
    {
        $userId = $this->store->store([
            'username' => $data['username'],
            'firstname' => $data['firstname'],
            'lastname'=>$data['lastname'],
            'email' => $data['email'],
            'lang' => $data['lang'],
            'password' => $data['password'],
        ]);
        return User::find($userId);
    }
}
