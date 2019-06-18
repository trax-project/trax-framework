<template>
    <div>

        <p v-if="hasLoggedin">
            <strong>{{ lang.trax_account.common.last_connection }}: </strong>
            {{ loggedin }}
        </p>

        <form class="trax-form pb-3" v-show="loaded">

            <!-- Account activation -->

            <trax-ui-toggle :text="lang.trax_account.common.active_account" :disabled="selfEdit==1" v-model="active">
            </trax-ui-toggle>

            <!-- LDAP -->

            <trax-ui-toggle :text="lang.trax_account.common.ldap_account" :disabled="selfEdit==1" v-model="ldap">
            </trax-ui-toggle>
            
        </form>

        <div v-show="active && invitation">

            <!-- Invitation button -->

            <div class="trax-form-actions text-right">
                <button type="button" class="btn btn-round btn-primary" data-toggle="modal" :data-target="'#'+id+'-send-invitation'">
                    {{ lang.trax_account.common.invitation_email }}
                </button>
            </div>

            <!-- Invitation confirm modal -->

            <trax-ui-modal-confirm :id="id+'-send-invitation'" :title="lang.trax_account.common.invitation_email" :bus="bus">
                {{ lang.trax_account.common.confirm_invitation_email_q }}
            </trax-ui-modal-confirm>
    
        </div>
    </div>
</template>

<script>
    export default {
    
        props: {
            selfEdit: null,
            invitation: null,
        },
        
        data: function() {
            return {
                loaded: false,
                id: 'trax-account-user-edit',
                lang: lang,
                data: null,
                active: null,
                ldap: null,
                password_endpoint: app_url+'invitation/email',
                bus: this.$bus
            }
        },

        created: function() {
            this.bus.$on(this.id+'-data', this.setData);
            this.bus.$on(this.id+'-send-invitation-confirmed', this.resetPassword);
        },

        watch: {

            active: function (value) {
                if (this.loaded) this.bus.$emit(this.id+'-changed', {active: value});
            },

            ldap: function (value) {
                if (this.loaded) this.bus.$emit(this.id+'-changed', {source_code: value ? 'ldap' : 'internal'});
                this.loaded = true;
            }
        },

        computed: {

            hasLoggedin: function () {
                return this.data && this.data.status && this.data.status.loggedin;
            },

            loggedin: function () {
                if (!this.hasLoggedin) return '';
                return moment(this.data.status.loggedin).format('DD MMM YYYY');;
            }
        },

        methods: {

            setData(data) {
                this.data = data;
                this.active = data.active;
                this.ldap = (data.source_code == 'ldap');
            },

            resetPassword() {
                var that = this;
                axios.post(this.password_endpoint, {email: this.data.email})
                    .then(function (response) {
                        that.bus.$emit(that.id+'-toastr-success', response.data);
                    })
                    .catch(function (error) {
                        that.bus.$emit(that.id+'-toastr-error', error.response.data);
                    });
            }
        }
    }
</script>
