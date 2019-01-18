<?php

namespace Trax\Account\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

use Trax\UI\Http\Controllers\ViewsController;

class AccountViewsController extends ViewsController
{
    
    /**
     * Root.
     */
    public function root(Request $request)
    {
        return redirect()->route('home');
    }

    /**
     * Home.
     */
    public function home(Request $request)
    {
        $this->nav->title = __('trax-ui::common.home');
        return $this->view('trax-account::home');
    }

    /**
     * Entities management.
     */
    public function entityCrud(Request $request)
    {
        $this->account->authorizer()->allows('trax_account_entity_crud');
        $this->nav->title = __('trax-account::common.entities_management');
        return $this->view('trax-account::entity-crud', $this->entityData());
    }

    /**
     * Roles management.
     */
    public function roleCrud(Request $request)
    {
        $this->account->authorizer()->allows('trax_account_role_crud');
        $this->nav->title = __('trax-account::common.roles');
        return $this->view('trax-account::role-crud', $this->roleData());
    }

    /**
     * Roles view.
     */
    public function roleEdit(Request $request, $id)
    {
        $this->account->authorizer()->allows('trax_account_role_crud');
        $role = $this->account->roles()->find($id);
        $this->nav->title = $role->data->name;
        $this->nav->breadcrumb = [
            route('trax.ui.account.role.crud') => __('trax-account::common.roles'),
            __('trax-account::common.role_definition'),
        ];
        return $this->view('trax-account::role-edit', $this->roleData($id));
    }

    /**
     * Users management.
     */
    public function userCrud(Request $request)
    {
        $this->account->authorizer()->allows('trax_account_user_crud');
        $this->nav->title = __('trax-account::common.users');
        return $this->view('trax-account::user-crud', $this->userData());
    }

    /**
     * Users view.
     */
    public function userEdit(Request $request, $id)
    {
        $this->account->authorizer()->allows('trax_account_user_crud');
        $user = $this->account->users()->find($id);
        $this->nav->title = $user->data->lastname .' '. $user->data->firstname;
        $this->nav->breadcrumb = [
            route('trax.ui.account.user.crud') => __('trax-account::common.users'),
            __('trax-account::common.user_account'),
        ];
        return $this->view('trax-account::user-edit', $this->userData($id));
    }

    /**
     * Group management.
     */
    public function groupCrud(Request $request)
    {
        $this->account->authorizer()->allows('trax_account_group_crud');
        $this->nav->title = __('trax-account::common.groups');
        return $this->view('trax-account::group-crud', $this->groupData());
    }

    /**
     * Group view.
     */
    public function groupEdit(Request $request, $id)
    {
        $this->account->authorizer()->allows('trax_account_group_crud');
        $group = $this->account->groups()->find($id);
        $this->nav->title = $group->data->name;
        $this->nav->breadcrumb = [
            route('trax.ui.account.group.crud') => __('trax-account::common.groups'),
            __('trax-account::common.group_members'),
        ];
        return $this->view('trax-account::group-edit', $this->groupData($id));
    }

    /**
     * My profile.
     */
    public function myProfile(Request $request)
    {
        $this->nav->title = __('trax-account::common.my_profile');
        return $this->view('trax-account::my-profile', $this->userData());
    }

    /**
     * Basic HTTP clients management.
     */
    public function basicClientCrud(Request $request)
    {
        $this->account->authorizer()->allows('trax_account_basic_client_crud');
        $this->nav->title = __('trax-account::common.basic_clients');
        return $this->view('trax-account::basic-client-crud');
    }

    /**
     * Agreement edit.
     */
    public function agreementEdit(Request $request)
    {
        // Permissions
        $this->account->authorizer()->allows('trax_account_agreement_write');

        // Data
        $agreement = $this->account->agreements()->lastPublished();
        $draft = $this->account->agreements()->draft();

        // View
        $this->nav->title = __('trax-account::common.user_agreement_title');
        return $this->view('trax-account::agreement-edit', ['agreement' => $agreement, 'draftAgreement' => $draft]);
    }

    /**
     * Agreement edit.
     */
    public function agreementApprove(Request $request)
    {
        // Data
        $agreement = $this->account->agreements()->lastPublished();

        // Permissions: check that it is not already approved
        $signedAgreement = Auth::user()->agreements->last();
        if ($signedAgreement && $signedAgreement->id == $agreement->id) return $this->agreementView($request);

        // View
        $this->nav->title = __('trax-account::common.user_agreement_title');
        return $this->view('trax-account::agreement-approve', ['agreement' => $agreement]);
    }

    /**
     * Agreement edit.
     */
    public function agreementView(Request $request)
    {
        // Data
        $agreement = $this->account->agreements()->lastPublished();

        // View
        $this->nav->title = __('trax-account::common.user_agreement_title');
        return $this->view('trax-account::agreement-view', ['agreement' => $agreement]);
    }


    // ------------------------------------- Data --------------------------------- //


    /**
     * Front-end data.
     */
    protected function userData($id = null)
    {
        $res = [
            'lang_select' => $this->ui->languages()->select(),
            'user_sources_select' => $this->account->userSources()->select(),
            'entity_types_select' => $this->account->entityTypes()->select(),
            'rights_levels_select' => $this->account->rightsLevels()->select(),
            'user_functions_select' => $this->account->userFunctions()->select(),
            'roles_select' => $this->account->roles()->select(),
            'notifications_default' => config('trax-notification.default'),
        ];
        if (isset($id)) {
            $res['user_id'] = $id;
        }
        return (object)$res;
    }

    /**
     * Front-end data.
     */
    protected function groupData($id = null)
    {
        $res = [
            'entity_types_select' => $this->account->entityTypes()->select(),
            'roles_select' => $this->account->roles()->select(),
            'status_select' => $this->trax->standardStatus()->select(),
        ];
        if (isset($id)) {
            $res['group_id'] = $id;
        }
        return (object)$res;
    }

    /**
     * Front-end data.
     */
    protected function roleData($id = null)
    {
        $res = [
            'permissions_select' => $this->account->permissions()->select(),
            'role_permissions_default' => $this->account->permissions()->defaultValues(false),
        ];
        if (isset($id)) {
            $res['role_id'] = $id;
        }
        return (object)$res;
    }

    /**
     * Front-end data.
     */
    protected function entityData($id = null)
    {
        $res = [
            'entity_types_select' => $this->account->entityTypes()->select(true),
        ];
        if (isset($id)) {
            $res['entity_id'] = $id;
        }
        return (object)$res;
    }


}
