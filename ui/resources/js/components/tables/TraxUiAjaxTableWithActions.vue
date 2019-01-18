<template>

    <trax-ui-table-with-actions 
        :id="id" :titles="titles" :actions="actions" :header="header" :header-color="headerColor">
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
            header: { default: 1 },
            headerColor: { default: 'primary' },
            urlMap: null,
            paging: { default: 1 },
            searching: { default: 1 },
            lengthChange: { default: 1 },
            orderColumn: null,
            orderDir: null,
            bus: null
        },

        data: function() {
            return {
                lang: lang,
                dataTable: null,
                filters: {}
            };
        },

        computed: {

            order: function() {
                return this.orderColumn && this.orderDir ? [[this.orderColumn, this.orderDir]] : [];
            }
        },

        created: function() {
            this.bus.$on(this.id+'-refresh', this.refresh);
        },
        
        mounted: function() {
            this.init();
        },
        
        methods: {
        
            init() {
                var that = this;

                // Initialize datatable

                $.fn.dataTable.ext.errMode = function(settings, help_page_number, message) {
                    console.log('DataTables error: '+message);
                };

                $.fn.dataTable.render.ellipsis = function(cutoff) {
                    return function (data, type, row) {
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
                        data:function(data) {
							data.filters = that.filters;
						},
                    },
                    columns: settings.columns,
                    ordering: settings.ordering,
                    order: that.order,
                    paging: parseInt(that.paging),
                    searching: parseInt(that.searching),
                    lengthChange: parseInt(that.lengthChange),
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
                    } else if (that.urlMap) {
                        var propVal = data[that.urlMap.prop];
                        var url = that.urlMap.map[propVal];
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
                    cell.visible = prop.visible !== undefined ? prop.visible : true;
                    columns.push(cell);
                }
                var actionsWith =  (this.actions.length * 40) + 10;
                columns.push({ data: null, orderable: false, defaultContent: $('#'+this.id+'-table-actions').html(), className: "text-right pl-0 pr-0 trax-width-"+actionsWith });
                return {columns: columns, ordering: ordering};
            },

            refresh(filters) {
                this.filters = filters ? filters : {};
                this.dataTable.ajax.reload(null, true);
            }
        }
    }
</script>
