<template>
    <div>

        <!-- Table -->

        <trax-ui-card-plain-header-with-actions :title="this.lang.trax_account.common.roles_management" :actions="card_actions" :bus="bus">
            <trax-account-role-crud-table :bus="bus"></trax-account-role-crud-table>
        </trax-ui-card-plain-header-with-actions>

        <!-- Editing modal -->

        <trax-ui-modal-default id="trax-account-role-edit" :bus="bus">
            <trax-account-role-crud-form :bus="bus"></trax-account-role-crud-form>
        </trax-ui-modal-default>

        <!-- Delete confirm modal -->

        <trax-ui-modal-confirm id="trax-account-role-delete" :title="this.lang.trax_account.common.role_deletion" :bus="bus">
            {{ lang.trax_account.common.confirm_delete_role_q }}
        </trax-ui-modal-confirm>

        <!-- Toastr -->

        <trax-ui-toastr id="trax-account-role-toastr" :bus="bus" passed-label="1" no-progress="1">
        </trax-ui-toastr>
    
    </div>
</template>

<script>
    export default {
    
        data: function() {
            return {
                lang: lang,
                card_actions: [
                    { icon: 'add_box', event: 'trax-account-role-edit-create' }
                ],
                bus: new Vue()
            }
        }
    }
</script>
