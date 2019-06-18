
// Auth
Vue.component('trax-account-auth-login', require('./components/auth/TraxAccountAuthLogin.vue').default);
Vue.component('trax-account-auth-register', require('./components/auth/TraxAccountAuthRegister.vue').default);
Vue.component('trax-account-auth-password-email', require('./components/auth/TraxAccountAuthPasswordEmail.vue').default);
Vue.component('trax-account-auth-password-reset', require('./components/auth/TraxAccountAuthPasswordReset.vue').default);

// Entities CRUD
Vue.component('trax-account-entity-crud', require('./components/entity-crud/TraxAccountEntityCrud.vue').default);

// Roles CRUD
Vue.component('trax-account-role-crud', require('./components/role-crud/TraxAccountRoleCrud.vue').default);
Vue.component('trax-account-role-crud-form', require('./components/role-crud/TraxAccountRoleCrudForm.vue').default);
Vue.component('trax-account-role-crud-table', require('./components/role-crud/TraxAccountRoleCrudTable.vue').default);

// Role edit
Vue.component('trax-account-role-edit', require('./components/role-edit/TraxAccountRoleEdit.vue').default);
Vue.component('trax-account-role-edit-data', require('./components/role-edit/TraxAccountRoleEditData.vue').default);
Vue.component('trax-account-role-edit-permissions', require('./components/role-edit/TraxAccountRoleEditPermissions.vue').default);

// Users CRUD
Vue.component('trax-account-user-crud', require('./components/user-crud/TraxAccountUserCrud.vue').default);
Vue.component('trax-account-user-crud-form', require('./components/user-crud/TraxAccountUserCrudForm.vue').default);
Vue.component('trax-account-user-crud-table', require('./components/user-crud/TraxAccountUserCrudTable.vue').default);

// User edit
Vue.component('trax-account-user-edit', require('./components/user-edit/TraxAccountUserEdit.vue').default);
Vue.component('trax-account-user-edit-data', require('./components/user-edit/TraxAccountUserEditData.vue').default);
Vue.component('trax-account-user-edit-access', require('./components/user-edit/TraxAccountUserEditAccess.vue').default);
Vue.component('trax-account-user-edit-password', require('./components/user-edit/TraxAccountUserEditPassword.vue').default);
Vue.component('trax-account-user-edit-rights', require('./components/user-edit/TraxAccountUserEditRights.vue').default);
Vue.component('trax-account-user-edit-avatar-card', require('./components/user-edit/TraxAccountUserEditAvatarCard.vue').default);

// Basic Clients CRUD
Vue.component('trax-account-basic-client-crud', require('./components/basic-client-crud/TraxAccountBasicClientCrud.vue').default);
Vue.component('trax-account-basic-client-crud-form', require('./components/basic-client-crud/TraxAccountBasicClientCrudForm.vue').default);
Vue.component('trax-account-basic-client-crud-table', require('./components/basic-client-crud/TraxAccountBasicClientCrudTable.vue').default);

// Group CRUD
Vue.component('trax-account-group-crud', require('./components/group-crud/TraxAccountGroupCrud.vue').default);
Vue.component('trax-account-group-crud-form', require('./components/group-crud/TraxAccountGroupCrudForm.vue').default);
Vue.component('trax-account-group-crud-table', require('./components/group-crud/TraxAccountGroupCrudTable.vue').default);
Vue.component('trax-account-group-crud-search', require('./components/group-crud/TraxAccountGroupCrudSearch.vue').default);

// Group edit
Vue.component('trax-account-group-edit-candidates', require('./components/group-edit/TraxAccountGroupEditCandidates.vue').default);
Vue.component('trax-account-group-edit-members', require('./components/group-edit/TraxAccountGroupEditMembers.vue').default);

// Agreements
Vue.component('trax-account-agreement', require('./components/agreement/TraxAccountAgreement.vue').default);
Vue.component('trax-account-agreement-edit', require('./components/agreement/TraxAccountAgreementEdit.vue').default);
