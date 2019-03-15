<template>

    <trax-ui-table-with-actions 
        :id="id" :titles="titles" :actions="allActions" :header="header" :header-color="headerColor">
    </trax-ui-table-with-actions>
    
</template>

<script>
    export default {

        props: {
            id: null,
            titles: null,
            actions: null,
            props: null,
            endpoint: null,
            endpointParams: null,
            header: { default: 1 },
            headerColor: { default: 'primary' },
            duplicate: null,
            editUrl: null,
            editUrlMap: null,
            orderColumn: null,
            orderDir: null,
            bus: null
        },

        data: function() {
            if (this.editUrlMap) {
                var editAction = {name: 'edit', icon: 'edit', class: 'btn-default'};
            } else if (this.editUrl) {
                var editAction = {name: 'edit', icon: 'edit', class: 'btn-default', url: this.editUrl};
            } else {
                var editAction = {name: 'edit', icon: 'edit', class: 'btn-default', event: 'edit-update'};
            } 
            var duplicateAction = {name: 'duplicate', icon: 'file_copy', class: 'btn-default', event: 'edit-duplicate'};
            var deleteAction = {name: 'delete', icon: 'close', class: 'btn-default', event: 'delete-open', wrapper: true};

            var actions = [];
            actions.push(editAction);
            if (this.duplicate) actions.push(duplicateAction);
            actions.push(deleteAction);

            return {
                dataTable: null,
                defaultActions: actions
            };
        },

        computed: {

            order: function() {
                return this.orderColumn && this.orderDir ? [[this.orderColumn, this.orderDir]] : [];
            },

            params: function () {
                return this.endpointParams ? this.endpointParams : {};
            },

            allActions: function () {
                return this.actions ? this.actions.concat(this.defaultActions) : this.defaultActions;
            }
        },

        created: function() {
            this.bus.$on(this.id+'-edit-created', this.created);
            this.bus.$on(this.id+'-edit-updated', this.refresh);
            this.bus.$on(this.id+'-delete-confirmed', this.deleteItem);
        },
        
        mounted: function() {
            this.init();
        },
        
        methods: {
        
            init() {
                var that = this;

                // Initialize datatable

                $.fn.dataTable.ext.errMode = function (settings, help_page_number, message) {
                    console.log('DataTables error: '+message);
                };

                $.fn.dataTable.render.ellipsis = function (cutoff) {
                    return function ( data, type, row ) {
                        return type === 'display' && data && data.length > cutoff ?
                            data.substr(0, cutoff) +'…' :
                            data;
                    }
                };

                var settings = this.settings();
                that.dataTable = $(that.$el).find('table').DataTable({
                    serverSide: true,
                    deferRender: true,
                    processing: false,
                    ajax: {
                        url: that.endpoint,
                        data: that.params
                    },
                    columns: settings.columns,
                    ordering: settings.ordering,
                    order: that.order,
                    language: lang.trax_ui.datatables,
                    info: false
                });

                // Change search trigger event (key up > enter)
                
                $('#'+that.id+'-table_filter input[type=search]').unbind();
                $('#'+that.id+'-table_filter input[type=search]').bind('keyup', function(e) {
                    if(e.keyCode == 13) {
                        that.dataTable.search(this.value).draw();   
                    }
                });

                // Buttons event

                $('#'+that.id+'-table tbody').on( 'click', '.btn', function() {
                    var data = that.dataTable.row( $(this).parents('tr') ).data();
                    if ($(this).data('wrapper')) data = {data};
                    if ($(this).data('event')) {
                        that.bus.$emit(that.id+'-'+$(this).data('event'), data);
                    } else if ($(this).data('url')) {
                        window.location = $(this).data('url') + '/' + data.id;
                    } else if (that.editUrlMap) {
                        var propVal = data[that.editUrlMap.prop];
                        var url = that.editUrlMap.map[propVal];
                        window.location = url + '/' + data.id;
                    }
                });
            },

            settings() {
                var ordering = false;
                var columns = [];
                for (var index in this.props) {
                    var prop = this.props[index];
                    if (typeof prop.source === "function") {
                        if (prop.order == undefined) {
                            var cell = {data: null, name: prop.source.name, orderable: false, render: prop.source};
                        } else {
                            ordering = true;
                            var cell = {data: null, name: prop.order, render: prop.source};
                        }
                    } else {
                        if (prop.orderable === undefined || prop.orderable) {
                            ordering = true;
                            var cell = {data: prop.source, name: prop.source, defaultContent: ''};
                        } else {
                            var cell = {data: prop.source, name: prop.source, defaultContent: '', orderable: false};
                        }
                        if (prop.truncate) cell.render = $.fn.dataTable.render.ellipsis(prop.truncate);
                    }
                    if (prop.class) cell.className = prop.class;
                    if (prop.width) cell.width = prop.width;
                    columns.push(cell);
                }
                var actionsWith =  (this.allActions.length * 40) + 10;
                columns.push({ data: null, orderable: false, defaultContent: $('#'+this.id+'-table-actions').html(), className: "text-right pl-0 pr-0 trax-width-"+actionsWith });
                return {columns: columns, ordering: ordering};
            },

            truncate() {
            },

            deleteItem(data) {
                var that = this;
                axios.delete(this.endpoint+'/'+data.id)
                    .then(function (response) {
                        that.refresh();
                    })
                    .catch(function (error) {
                        var errorData = {
                            status: error.response.status, 
                            data: error.response.data, 
                            id: data.id 
                        }
                        that.bus.$emit(that.id+'-delete-error', errorData);
                        if (typeof error.response.data == 'string') {
                            that.bus.$emit(that.id+'-toastr-error', error.response.data);
                        }
                    });
            },

            created(request) {
                if (this.editUrl) {
                    window.location = this.editUrl + '/' + request.response.data;
                } else if (this.editUrlMap) {
                    var propVal = request.data[this.editUrlMap.prop];
                    var url = this.editUrlMap.map[propVal];
                    window.location = url + '/' + request.response.data;
                } else {
                    this.refresh();
                }
            },

            refresh() {
                this.dataTable.ajax.reload(null, true);
            }
        }
    }
</script>
