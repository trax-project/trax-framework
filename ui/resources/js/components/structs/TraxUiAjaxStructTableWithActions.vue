<template>
    <div class="table-responsive " v-show="loaded">
        <p class="pt-2" v-show="!data.length">
            {{ lang.trax_ui.form.no_item }}
        </p>
        <table class="table trax-simple-table" v-show="data.length">
            <thead v-if="header" :class="'text-'+headerColor">
                <th v-for="(title, index) in titles" :key="index">{{ title }}</th>
            </thead>
            <tbody :id="id+'-struct'">
                <tr v-for="item in data" :key="item.id" :data-id="item.id">
                    <td width="20%">
                        <strong>{{ item.data.name }}</strong>
                    </td>
                    <td>
                        {{ truncate(item.data.description, 150) }}
                    </td>
                    <td class="trax-actions-2">
                        <trax-ui-struct-actions :bus="bus" :id="id" :data="item" :edit-url="editUrl" :edit-url-map="editUrlMap" />
                    </td>
                    <td class="trax-actions-1" v-if="drag">
                        <div class="btn btn-link btn-just-icon btn-sm trax-drag-handler">
                            <i class="material-icons">drag_indicator</i>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {

        props: {
            header: { default: false },
            headerColor: { default: 'primary' },
            bus: null,
            id: null,
            indexId: null,
            drag: null,
            endpoint: null,
            editUrl: null,
            editUrlMap: null,
            newItemLabel: {default: lang.trax_ui.form.new_item}
        },

        data: function() {
            return {
                lang: lang,
                titles: [
                    lang.trax_ui.form.name,
                    lang.trax_ui.form.description,
                    '',
                    ''
                ],
                loaded: false,
                type_code: null,
                data: []
            }
        },

        created: function() {
            this.bus.$on(this.id+'-delete-confirmed', this.deleteItem);
            this.bus.$on(this.id+'-edit-created', this.refresh);
            this.bus.$on(this.id+'-edit-updated', this.refresh);
            this.getData();
        },

        updated: function () {
            this.initDrag();
        },

        methods: {

            initDrag() {
                if (!this.drag) return;
                var el = document.getElementById(this.id+'-struct');
                Sortable.create(el, { handle: '.trax-drag-handler', animation: 150, onUpdate: this.itemSorted});
            },
            
            itemSorted(event) {
                var data = {id: event.item.dataset.id, order: event.newIndex};
                this.bus.$emit(this.id+'-edit-sorted', data);
            },

            addItem(event, categoryId) {
                var data = {index_id: this.indexId, type_code: this.type_code};
                this.bus.$emit(this.id+'-edit-create', data);
                this.bus.$emit(this.id+'-edit-open', {title: this.newItemLabel} );
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

            truncate(text, cutoff) {
                return text ? text.substr(0, cutoff) +'…' : '';
            },

            refresh() {
                this.getData(this.type_code);
            }
        }
    }
</script>
