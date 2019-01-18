<template>

    <div class="trax-struct" v-show="loaded">
        <div :id="id+'-struct'" class="trax-struct-body">
            <div class="trax-block trax-level-1 trax-actions-2" v-for="category in data" :key="category.id" :data-id="category.id">

                <div class="btn btn-link btn-just-icon btn-sm trax-drag-handler move-1 float-right" v-if="drag">
                    <i class="material-icons">drag_indicator</i>
                </div>

                <div class="trax-block-header">
                    <trax-ui-struct-actions :bus="bus" :id="id" :data="category"></trax-ui-struct-actions>
                    <h4>{{ category.data.name }}</h4>
                </div>

                <div :id="id+'-struct-'+category.id" class="trax-block-body" :data-id="category.id">
                    <div :class="itemClass(item)" v-for="item in category.children" :key="item.id"  :data-id="item.id">
                        
                        <div class="btn btn-link btn-just-icon btn-sm trax-drag-handler move-2 float-right" v-if="drag">
                            <i class="material-icons">drag_indicator</i>
                        </div>

                        <div class="trax-bullet" v-if="!item.data.title">
                            <i class="material-icons">navigate_next</i>
                        </div>

                        <trax-ui-struct-actions :bus="bus" :id="id" :data="item"></trax-ui-struct-actions>

                        <p :class="itemContentClass(item)" style="white-space: pre-wrap;">{{ item.data.name }}</p>
                    </div>
                </div>

                <div class="trax-block-footer">
                    <button class="btn btn-sm btn-primary" @click="addItem($event, category.id)">
                        {{ newItemLabel }}
                    </button>

                    <button class="btn btn-sm btn-default" @click="addTitle($event, category.id)" v-if="newTitleLabel">
                        {{ newTitleLabel }}
                    </button>
                </div>
            </div>
        </div>
        <div class="trax-struct-footer">
            <button class="btn btn btn-primary" @click="addCategory">
                {{ newCategoryLabel }}
            </button>
        </div>
    </div>
    
</template>

<script>
    export default {

        props: {
            bus: null,
            id: null,
            indexId: null,
            drag: null,
            endpoint: null,
            newItemLabel: {default: lang.trax_ui.form.new_item},
            newCategoryLabel: {default: lang.trax_ui.form.new_category},
            newTitleLabel: null
        },

        data: function() {
            return {
                lang: lang,
                loaded: false,
                type_code: null,
                data: []
            }
        },

        created: function() {
            this.bus.$on(this.id+'-tabs-changed', this.getData);
            this.bus.$on(this.id+'-delete-confirmed', this.deleteItem);
            this.bus.$on(this.id+'-edit-created', this.refresh);
            this.bus.$on(this.id+'-edit-updated', this.refresh);
        },

        updated: function () {
            this.initDrag();
        },

        methods: {

            itemClass(item) {
                var res = 'trax-block trax-level-2 trax-actions-2';
                if (item.data.title) res += ' trax-title';
                return res;
            },

            itemContentClass(item) {
                return item.data.title ? '' : 'trax-with-bullet';
            },

            initDrag() {
                if (!this.drag) return;

                // Level 1 drag
                var el = document.getElementById(this.id+'-struct');
                Sortable.create(el, { handle: '.move-1', animation: 150, onUpdate: this.level1Sorted});

                // Level 2 drag
                for (var index in this.data) {
                    var category = this.data[index];
                    var el = document.getElementById(this.id+'-struct-'+category.id);
                    Sortable.create(el, { handle: '.move-2', animation: 150, group: 'level-2', onUpdate: this.level2Sorted, onAdd: this.level2Added});
                }
            },
            
            level1Sorted(event) {
                var data = {id: event.item.dataset.id, order: event.newIndex};
                this.bus.$emit(this.id+'-edit-sorted', data);
            },

            level2Sorted(event) {
                var data = {id: event.item.dataset.id, order: event.newIndex, parent_id: event.to.dataset.id};
                this.bus.$emit(this.id+'-edit-sorted', data);
            },

            level2Added(event) {
                var data = {id: event.item.dataset.id, order: event.newIndex, parent_id: event.to.dataset.id};
                this.bus.$emit(this.id+'-edit-sorted', data);
            },

            addItem(event, categoryId) {
                var data = {index_id: this.indexId, type_code: this.type_code, parent_id: categoryId};
                this.bus.$emit(this.id+'-edit-create', data);
                this.bus.$emit(this.id+'-edit-open', {title: this.newItemLabel} );
            },
            
            addTitle(event, categoryId) {
                var data = {index_id: this.indexId, type_code: this.type_code, parent_id: categoryId, title: true};
                this.bus.$emit(this.id+'-edit-create', data);
                this.bus.$emit(this.id+'-edit-open', {title: this.newTitleLabel} );
            },
            
            addCategory(event) {
                var data = {index_id: this.indexId, type_code: this.type_code};
                this.bus.$emit(this.id+'-edit-create', data );
                this.bus.$emit(this.id+'-edit-open', {title: this.newCategoryLabel} );
            },

            getData(type_code) {
                var that = this;
                that.type_code = type_code;
                axios.get(this.endpoint, {
                    params: {
                        'search[index_id]': that.indexId,
                        'search[type_code]': type_code,
                    }})
                    .then(function (response) {
                        that.data = response.data;
                        that.loaded = true;
                    })
                    .catch(function (error) {
                    });
            },

            deleteItem(data) {
                var that = this;
                axios.delete(this.endpoint+'/'+data.id)
                    .then(function (response) {
                        that.refresh();
                    })
                    .catch(function (error) {
                        if (typeof error.response.data == 'string') {
                            that.bus.$emit(that.id+'-toastr-error', error.response.data);
                        }
                    });
            },

            refresh() {
                this.getData(this.type_code);
            }
        }
    }
</script>
