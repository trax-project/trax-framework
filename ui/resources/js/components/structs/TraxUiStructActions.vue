<template>

    <div class="trax-actions">

        <button class="btn btn-link btn-just-icon btn-sm" @click="editItem">
            <i class="material-icons">edit</i>
        </button>

        <button class="btn btn-link btn-just-icon btn-sm" @click="deleteItem">
            <i class="material-icons">close</i>
        </button>

    </div>
    
</template>

<script>
    export default {

        props: {
            bus: null, 
            id: null,
            editUrl: null,
            editUrlMap: null,
            data: null
        },

        created: function() {
            this.bus.$on(this.id+'-edit-sorted', this.itemSorted);
        },

        methods: {

            editItem() {
                if (this.editUrlMap) {
                    var propVal = this.data[this.editUrlMap.prop];
                    var url = this.editUrlMap.map[propVal];
                    window.location = url + '/' + this.data.id;
                } else if (this.editUrl) {
                    window.location = this.editUrl + '/' + this.data.id;
                } else {
                    this.bus.$emit(this.id+'-edit-update', this.data);
                } 
            },

            deleteItem() {
                this.bus.$emit(this.id+'-delete-open', {data: this.data});
            },

            itemSorted(data) {
                if (data.id == this.data.id) {
                    this.data.data.order = data.order;
                    if (data.parent_id) this.data.parent_id = data.parent_id;
                    this.bus.$emit(this.id+'-edit-send', this.data);
                }
            }
        }
    }
</script>
