<template>

    <trax-ui-simple-table :id="id" :titles="titles" :header="header" :header-color="headerColor">
    </trax-ui-simple-table>
    
</template>

<script>
    export default {

        props: {
            id: null,
            titles: null,
            props: null,
            endpoint: null,
            endpointParams: null,
            header: { default: 1 },
            headerColor: { default: 'primary' },
            paging: { default: 1 },
            searching: { default: 1 },
            lengthChange: { default: 1 },
            emptyMessage: null,
            orderColumn: null,
            orderDir: null,
            bus: null
        },

        data: function() {
            return {
                dataTable: null,
                filters: {}
            };
        },

        computed: {

            order: function() {
                return this.orderColumn && this.orderDir ? [[this.orderColumn, this.orderDir]] : [];
            },

            params: function () {
                return this.endpointParams ? this.endpointParams : {};
            }
        },

        created: function() {
            if (this.bus) {
                this.bus.$on(this.id+'-refresh', this.refresh);
                this.bus.$on(this.id+'-filter', this.filter);
            }
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
                var datatableSettings = {
                    serverSide: true,
                    deferRender: true,
                    processing: false,
                    ajax: {
                        url: that.endpoint,
                        data: function(data) {
                            data.filters = that.filters;
                            Object.keys(that.params).forEach(function(key) { data[key] = that.params[key]; });
						},
                    },
                    columns: settings.columns,
                    ordering: settings.ordering,
                    order: that.order,
                    language: lang.trax_ui.datatables,
                    info: false,
                    paging: parseInt(that.paging),
                    searching: parseInt(that.searching),
                    lengthChange: parseInt(that.lengthChange),
                    drawCallback: function () {
                        $('.dataTables_paginate > .pagination').addClass('pagination-'+that.headerColor);
                    }
                };
                if (that.emptyMessage) datatableSettings.language.zeroRecords = that.emptyMessage;
                that.dataTable = $(that.$el).find('table').DataTable(datatableSettings);

                // Change search trigger event (key up > enter)
                
                $('#'+that.id+'-table_filter input[type=search]').unbind();
                $('#'+that.id+'-table_filter input[type=search]').bind('keyup', function(e) {
                    if(e.keyCode == 13) {
                        that.dataTable.search(this.value).draw();   
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
                return {columns: columns, ordering: ordering};
            },

            refresh() {
                this.dataTable.ajax.reload(null, true);
            },

            filter(filters) {
                this.filters = filters ? filters : {};
                this.refresh();
            }
        }
    }
</script>
