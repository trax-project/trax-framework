<template>

    <div class="card card-testimonial mt-5">
        <div class="card-avatar">

            <img :src="userPictureFolder+userPicture" style="width:100px;height:100px;" v-if="disabled">

            <label class="file-select" style="cursor:pointer;" v-else>
                <img :src="userPictureFolder+userPicture" style="width:100px;height:100px;">
                <input type="file" accept="image/jpg, image/jpeg" @change="fileChanged" class="d-none" />
            </label>

        </div>
        <div :class="'card-body mt-0 pt-0 text-'+align">
            <p class="text-center"><small class="text-muted" v-if="!disabled">JPEG 130 x 130 px</small></p>
            <slot></slot>
        </div>
    </div>
    
</template>

<script>
    export default {

        props: {
            id: {default: 'trax-account-user-edit'},
            align:  {default: 'left'},
            userPictureFolder: {default: app_url},
            endpoint: {default: app_url+'trax/ajax/account/users'},
            disabled: null
        },

        data: function() {
            return {
                userPicture: 'img/lib/misc/avatar.jpg',
                userId: null,
                bus: this.$bus
            };
       },

        created: function() {
            this.bus.$on(this.id+'-data', this.setData);
        },

        methods: {

            setData(data) {
                this.userId = data.id;
                if (data.picture) this.userPicture = data.picture;
            },

            fileChanged(e) {
                var that = this;
                var formData = new FormData();
                formData.append('picture', e.target.files[0]);
                axios.post(
                        this.endpoint+'/'+this.userId+'/picture', 
                        formData,
                        {headers: {'Content-Type': 'multipart/form-data'}}
                    )
                    .then(function (response) {
                        that.userPicture = response.data.picture;
                    })
                    .catch(function (error) {
                        if (typeof error.response.data == 'string') {
                            that.bus.$emit(that.id+'-toastr-error', error.response.data);
                        } else if (error.response.data.message) {
                            that.bus.$emit(that.id+'-toastr-error', error.response.data.message);
                        }
                    });
            }

        }

    }
</script>

