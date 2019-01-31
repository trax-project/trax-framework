<template>
    <div class="row">
        <div class="col-lg-6">

            <trax-ui-card :title="lang.trax_account.common.user_agreement_new_version" title-no-padding="1" title-bold="1">

                <!-- Form -->

                <form autocomplete="off" class="trax-form">
                    
                    <trax-ui-input :placeholder="lang.trax_account.common.user_agreement_enter" :rows="20" 
                        v-model="form.content" :error="errors.content">
                    </trax-ui-input>

                    <div class="trax-form-actions text-right">

                        <button type="button" class="btn btn-round btn-default" @click="save"> 
                            {{ lang.trax_ui.form.save }} 
                        </button>

                        <button type="button" class="btn btn-round btn-primary" @click="publish"> 
                            {{ lang.trax_account.common.user_agreement_publish }} 
                        </button>

                    </div>
                </form>

                <!-- Toastr -->

                <trax-ui-toastr :id="id+'-toastr'" :bus="bus">
                </trax-ui-toastr>

                <!-- Publish confirm modal -->

                <trax-ui-modal-confirm :id="id+'-publish'" :title="this.lang.trax_account.common.user_agreement_publish" :bus="bus">
                    {{ lang.trax_account.common.confirm_user_agreement_publish_q }}
                </trax-ui-modal-confirm>

            </trax-ui-card>

        </div>
        <div class="col-lg-6">

            <trax-ui-card>
                <div v-html="agreementContent"></div>
            </trax-ui-card>

        </div>
    </div>


</template>

<script>
    export default {
    
        props: {
            id: {default: 'trax-account-agreement'},
        },

        data: function() {
            return {
                lang: lang,
                endpoint: app_url+"trax/ajax/account/agreements",
                form: {
                    content: null,
                },
                errors: {},
                agreement: data.draftAgreement,
                agreementId: null,
                bus: this.$bus
            }
        },

        computed: {

            agreementContent: function() {
                if (this.form.content) return this.form.content;
                return '<p class="mt-4"></p>';
            }
        },

        created: function() {
            if (this.agreement) {
                this.form.content = this.agreement.content;
                this.agreementId = this.agreement.id;
            }
            this.bus.$on(this.id+'-publish-confirmed', this.publishConfirmed);
        },

        methods: {

            save() {
                this.errors = {};
                if (this.agreementId) this.putData(this.agreementId, this.form);
                else this.postData(this.form);
            },

            publish() {
                this.bus.$emit(this.id+'-publish-open');
            },

            publishConfirmed() {
                this.errors = {};
                if (this.agreementId) this.putData(this.agreementId, {content: this.form.content, published: 1});
                else this.postData({content: this.form.content, published: 1});
            },

            published(data) {
                this.form.content = null;
                this.agreementId = null;
            },

            putData(id, data) {
                var that = this;
                axios.put(this.endpoint+'/'+id, data)
                    .then(function (response) {
                        that.bus.$emit(that.id+'-toastr-success');
                        if (data.published) that.published(data);
                    })
                    .catch(function (error) {
                        if (error.response.data.errors) {
                            that.processErrors(error.response.data.errors);
                        } else {
                            that.bus.$emit(that.id+'-toastr-error', error.response.data);
                        }
                    });
            },
            
            postData(data) {
                var that = this;
                axios.post(this.endpoint, data)
                    .then(function (response) {
                        that.agreementId = parseInt(response.data);
                        that.bus.$emit(that.id+'-toastr-success');
                        if (data.published) that.published(data);
                    })
                    .catch(function (error) {
                        if (error.response.data.errors) {
                            that.processErrors(error.response.data.errors);
                        } else {
                            that.bus.$emit(that.id+'-toastr-error', error.response.data);
                        }
                    });
            },

            processErrors(errors) {
                for (var name in errors) {
                    var error = errors[name];
                    if (error instanceof Array) errors[name] = error[0];
                }
                this.errors = errors;
            }

        }
    }
</script>
