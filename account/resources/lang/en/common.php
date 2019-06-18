<?php

return [

    // Home
    'custom_dashboard' => 'A dashboard should be created for your project.',

    // Auth
    'log_in' => 'Log in',
    'login' => 'Login',
    'logout' => 'Logout',
    'register' => 'Registrer',
    'registration' => "Registration",
    'change_password' => 'Change Password',
    'reset_password' => 'Reset Password',
    'choose_password' => 'Choose a password',
    'forgot_password_q' => 'Forgot your password?',
    'password' => 'Password',
    'password_reset' => 'Password reset',
    'password_has_been_reset' => 'Your password has been reset',
    'password_defined' => 'Your password has been defined',
    'password_confirmation' => 'Password confirmation',
    'current_password' => 'Current password',
    'new_password' => 'New password',
    'new_password_confirmation' => 'New password confirmation',
    'remember_me' => 'Remember me',
    'sent_password_email_success' => 'Please, check your emails. A reset link has been sent.',
    'sent_password_email_error' => 'You are not allowed to reset your password. Please, contact the administrator.',
    'reset_password_error' => 'Something goes wrong. Please, check that you entered the correct email.',
    'invitation' => 'Invitation',
    'invitation_email' => 'Invitation e-Mail',
    'confirm_invitation_email_q' => '
        If you confirm, an invitation email will be sent to the user. 
        Do you really want to do that?
    ',
    'sent_invitation_email_success' => 'The invitation has been sent.',
    'sent_invitation_email_error' => 'Sorry, we encountered a problem. The invitation has not been sent.',
    'last_connection' => 'Last connection',

    // Roles
    'role' => 'Role',
    'role_deletion' => "Role Deletion",
    'role_update' => "Role Update",
    'roles' => 'Roles',
    'roles_management' => 'Roles Management',
    'role_definition' => 'Role Definition',
    'new_role' => "New Role",
    'permissions' => 'Permissions',
    'confirm_delete_role_q' => '
        If you confirm, the role will be permanently deleted! 
        Do you really want to do that?
    ',
    'role_deletion_exception' => "
        This role can't be deleted because it is used by one or more users.
    ",

    // Entities
    'entities' => 'Entities',
    'entities_management' => 'Entities Management',
    'organization' => 'Organization',
    'entity' => 'Entity',
    'new_entity' => 'New Entity',
    'new_organization' => 'New Organization',
    'entity_deletion_exception' => "
        This entity can't be deleted because it is used by one or more users.
    ",

    // Users
    'users' => 'Users',
    'user_account' => 'User Account',
    'user_accounts' => 'Users Accounts',
    'users_management' => 'Users Management',
    'user_update' => "User Update",
    'new_user' => "New User",
    'user_deletion' => 'User Deletion',
    'email' => 'Email',
    'firstname' => 'First name',
    'lastname' => 'Last name',
    'fullname' => 'Full name',
    'personal_data' => 'Personal Data',
    'account_type' => 'Account Type',
    'no_organization' => 'No organization',
    'no_entity' => 'No entity',
    'no_function' => "No function",
    'no_role' => "No role",
    'admin_account' => 'Super administrator',
    'user_deletion_exception' => "
        This user can't be deleted because it belongs to a group of learners.
        Related learning data may exist.
    ",
    'user_self_deletion_exception' => "
        You can't delete your own account.
    ",

    // My profile
    'my_profile' => 'My Profile',
    
    // Basic clients
    'basic_clients' => 'Basic HTTP Clients',
    'basic_clients_management' => 'Basic Clients Management',
    'client_update' => 'Client Update',
    'new_client' => 'New Client',
    'client_deletion' => 'Client Deletion',

    // Groups
    'group' => "Group",
    'groups' => "Groups",
    'groups_management' => 'Groups Management',
    'group_update' => 'Group Update',
    'new_group' => 'New Group',
    'group_deletion' => "Group Deletion",
    'group_members' => "Group Members",
    'confirm_delete_group_q' => '
        If you confirm, the group will be permanently deleted.
        However, user accounts will be preserved. 
        Do you really want to do that?
    ',
    'group_deletion_exception' => "
        This group can't be deleted because it has been registered to one or more trainings.
    ",
    'cant_update_group_with_archived_registration' => "
        This group can't be modified because one of its registrations has been archived.
    ",
    'cant_update_active_and_archived_group' => "
        This group can't be modified because it is in service or archived.
    ",
    'cant_update_archived_group' => "
        This group can't be modified because it is archived.
    ",
    'cant_remove_user_from_group' => "
        This user can't be removed from this group because some learning data exists.
    ",

    // Agreements
    'user_agreement_title' => 'Terms of Service',
    'user_agreement_empty' => 'There is currently no defined terms of service.',
    'user_agreement_new_version' => 'New version (HTML)',
    'user_agreement_enter' => "Enter the terms of service here...",
    'user_agreement_publish' => 'Publish',
    'user_agreement_preview' => 'Preview',
    'user_agreement_approve' => 'I have read and I agree with the terms of service',
    'confirm_user_agreement_publish_q' => 
        'If you confirm, all users will have to approve the new terms of service.
        Do you really want to do that?',

    // Commons
    'accounts' => 'Accounts',
    'accounts_management' => 'Accounts Management',
    'active_account' => 'Active account',
    'ldap_account' => "LDAP authentication",
    'username' => 'User name',
    'access' => 'Access',
    'rights' => 'Rights',
    'confirm_delete_account_q' => '
        If you confirm, the account will be permanently deleted! 
        Do you really want to do that?',
    'not_allowed' => "You are not allowed to do that!",

    // Permissions
    'perm_users_management' => 'Admin: Users',
    'perm_groups_management' => 'Admin: Groups',
    'perm_roles_management' => 'Admin: Roles',
    'perm_entities_management' => 'Admin: Entities',
    'perm_basic_clients_management' => 'Settings: Basic HTTP Clients',
    'perm_agreement_write' => 'Settings: Legal Agreement Editing',

    // Others
    'all_status' => 'All status',

];
